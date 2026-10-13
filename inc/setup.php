<?php
/**
 * Theme supports, assets, page bootstrap and template routing.
 */

function pps_theme_support() {
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
  add_theme_support('html5', array('search-form', 'gallery', 'caption', 'style', 'script'));
  add_image_size('pps-card', 900, 675, true);
  add_image_size('pps-wide', 1920, 1080, true);
}
add_action('after_setup_theme', 'pps_theme_support');

function pps_load_assets() {
  $asset = include get_theme_file_path('/build/index.asset.php');

  wp_enqueue_style('pps-fonts', 'https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700&family=Inter:wght@400;500;600;700&display=swap', array(), null);
  wp_enqueue_style('pps-main', get_theme_file_uri('/build/index.css'), array(), filemtime(get_theme_file_path('/build/index.css')));
  wp_enqueue_script('pps-main', get_theme_file_uri('/build/index.js'), $asset['dependencies'], $asset['version'], true);
  wp_add_inline_script('pps-main', 'window.ppsData = ' . wp_json_encode(pps_js_data()) . ';', 'before');
}
add_action('wp_enqueue_scripts', 'pps_load_assets');

function pps_resource_hints($urls, $relation) {
  if ($relation === 'preconnect') {
    $urls[] = 'https://fonts.googleapis.com';
    $urls[] = array('href' => 'https://fonts.gstatic.com', 'crossorigin');
  }
  return $urls;
}
add_filter('wp_resource_hints', 'pps_resource_hints', 10, 2);

/** Data shared with the React components (Navbar, Footer, QuoteForm). */
function pps_js_data() {
  $biz = pps_business();

  $services = array();
  foreach (pps_services() as $slug => $service) {
    $services[] = array('slug' => $slug, 'name' => $service['nav_name'], 'url' => pps_service_url($slug));
  }

  $property_types = array();
  foreach (pps_property_types() as $value => $label) {
    $property_types[] = array('value' => $value, 'label' => $label);
  }

  return array(
    'homeUrl'       => home_url('/'),
    'logoUrl'       => pps_asset_url('logo'),
    'siteName'      => $biz['name'],
    'phone'         => $biz['phone'],
    'phoneHref'     => $biz['phone_href'],
    'email'         => $biz['email'],
    'address'       => array($biz['street'], "{$biz['city']}, {$biz['region']} {$biz['postal']}"),
    'serviceArea'   => $biz['service_area'],
    'gbpUrl'        => $biz['gbp_url'],
    'socials'       => array_map(fn($label, $url) => array('label' => $label, 'url' => $url), array_keys($biz['socials']), $biz['socials']),
    'privacyUrl'    => home_url('/privacy-policy/'),
    'quoteUrl'      => home_url('/contact/'),
    'nav'           => array(
      array('label' => 'Home', 'url' => home_url('/')),
      array('label' => 'About', 'url' => home_url('/about/')),
      array('label' => 'Services', 'url' => home_url('/services/'), 'children' => true),
      array('label' => 'Projects', 'url' => home_url('/projects/')),
      array('label' => 'Contact', 'url' => home_url('/contact/')),
    ),
    'services'      => $services,
    'propertyTypes' => $property_types,
    'quoteEndpoint' => rest_url('pps/v1/quote'),
  );
}

/**
 * Creates the site's pages once (idempotent) and sets the static front page.
 * Bump PPS_PAGES_VERSION to run it again after adding pages.
 */
const PPS_PAGES_VERSION = 1;

function pps_create_pages() {
  if ((int) get_option('pps_pages_version') >= PPS_PAGES_VERSION) {
    return;
  }

  $ensure = function ($slug, $title, $parent = 0) {
    $path = $parent ? get_post_field('post_name', $parent) . '/' . $slug : $slug;
    $page = get_page_by_path($path);
    if ($page) {
      if ($page->post_status !== 'publish') {
        wp_update_post(array('ID' => $page->ID, 'post_status' => 'publish'));
      }
      return $page->ID;
    }
    return wp_insert_post(array(
      'post_type'   => 'page',
      'post_status' => 'publish',
      'post_title'  => $title,
      'post_name'   => $slug,
      'post_parent' => $parent,
    ));
  };

  $home = $ensure('home', 'Home');
  $ensure('about', 'About');
  $services = $ensure('services', 'Services');
  foreach (pps_services() as $slug => $service) {
    $ensure($slug, $service['name'], $services);
  }
  $ensure('projects', 'Projects');
  $ensure('contact', 'Contact');
  $ensure('privacy-policy', 'Privacy Policy');

  update_option('show_on_front', 'page');
  update_option('page_on_front', $home);

  if (!get_option('permalink_structure')) {
    update_option('permalink_structure', '/%postname%/');
  }
  flush_rewrite_rules();

  update_option('pps_pages_version', PPS_PAGES_VERSION);
}
add_action('admin_init', 'pps_create_pages');
add_action('after_switch_theme', 'pps_create_pages');

/** Child pages of /services/ that match a configured service use the shared landing template. */
function pps_current_service() {
  if (!is_page()) {
    return null;
  }
  $page = get_queried_object();
  if (!$page->post_parent || get_post_field('post_name', $page->post_parent) !== 'services') {
    return null;
  }
  return pps_service($page->post_name);
}

function pps_template_include($template) {
  if (pps_current_service()) {
    return get_theme_file_path('/templates/service-landing.php');
  }
  return $template;
}
add_filter('template_include', 'pps_template_include');
