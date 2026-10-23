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

  wp_enqueue_style('pps-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@700;800&display=swap', array(), null);
  // Tailwind compiles to /dist (not /build): `wp-scripts start` wipes /build when it boots,
  // which used to delete the CSS that the parallel Tailwind watcher had just written.
  $css = get_theme_file_path('/dist/index.css');
  wp_enqueue_style('pps-main', get_theme_file_uri('/dist/index.css'), array(), file_exists($css) ? filemtime($css) : null);
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

/** Favicon from the logo's building mark, unless a Site Icon is set in Settings → General. */
function pps_favicon() {
  if (has_site_icon()) {
    return;
  }
  $mark = pps_asset_url('brand/logo-mark');
  printf('<link rel="icon" type="image/png" href="%1$s">' . "\n" . '<link rel="apple-touch-icon" href="%1$s">' . "\n", esc_url($mark));
}
add_action('wp_head', 'pps_favicon', 2);

/**
 * Google Consent Mode v2 defaults: everything denied until the visitor accepts in the
 * cookie banner (src/components/CookieBanner.js). Must run before any GA4/GTM tag.
 */
function pps_consent_defaults() {
  ?>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('consent', 'default', {ad_storage: 'denied', ad_user_data: 'denied', ad_personalization: 'denied', analytics_storage: 'denied', wait_for_update: 500});
    try {
      if (localStorage.getItem('pps_cookie_consent') === 'granted') {
        gtag('consent', 'update', {ad_storage: 'granted', ad_user_data: 'granted', ad_personalization: 'granted', analytics_storage: 'granted'});
      }
    } catch (e) {}
  </script>
  <?php
}
add_action('wp_head', 'pps_consent_defaults', 0);

/** Data shared with the React components (Navbar, Footer, QuoteForm). */
function pps_js_data() {
  $biz = pps_business();

  $services = array();
  foreach (pps_services() as $slug => $service) {
    $services[] = array(
      'slug'      => $slug,
      'name'      => $service['nav_name'],
      'url'       => pps_service_url($slug),
      'summary'   => $service['summary'],
      'image'     => pps_asset_url(array("service-{$slug}", "category-{$service['group']}")),
      'specialty' => $service['group'] === 'specialty',
    );
  }

  $property_types = array();
  foreach (pps_property_types() as $value => $label) {
    $property_types[] = array('value' => $value, 'label' => $label);
  }

  return array(
    'homeUrl'       => home_url('/'),
    'logoUrl'       => pps_asset_url('brand/logo'),
    'logoLightUrl'  => pps_asset_url('brand/logo-light'),
    'markUrl'       => pps_asset_url('brand/logo-mark'),
    'siteName'      => $biz['name'],
    'phone'         => $biz['phone'],
    'phoneHref'     => $biz['phone_href'],
    'email'         => $biz['email'],
    'address'       => array($biz['street'], "{$biz['city']}, {$biz['region']} {$biz['postal']}"),
    'serviceArea'   => $biz['service_area'],
    'gbpUrl'        => $biz['gbp_url'],
    'socials'       => array_map(fn($label, $url) => array('label' => $label, 'url' => $url), array_keys($biz['socials']), $biz['socials']),
    'privacyUrl'    => home_url('/privacy-policy/'),
    'termsUrl'      => home_url('/terms-and-conditions/'),
    'agencyName'    => '828 Marketing Solutions',
    'agencyUrl'     => 'https://828marketingsolutions.com',
    'quoteUrl'      => home_url('/contact/'),
    'nav'           => array(
      array('label' => 'Home', 'url' => home_url('/')),
      array('label' => 'About', 'url' => home_url('/about/')),
      array('label' => 'Services', 'url' => home_url('/services/'), 'mega' => true),
      array('label' => 'Projects', 'url' => home_url('/projects/')),
      array('label' => 'Contact', 'url' => home_url('/contact/'), 'children' => array(
        array('label' => 'Contact Us', 'url' => home_url('/contact/')),
        array('label' => 'Service Areas', 'url' => home_url('/contact/service-areas/')),
      )),
    ),
    'services'      => $services,
    'areas'         => pps_service_areas(),
    'propertyTypes' => $property_types,
    'quoteEndpoint' => rest_url('pps/v1/quote'),
  );
}

/**
 * Creates the site's pages once (idempotent) and sets the static front page.
 * Bump PPS_PAGES_VERSION to run it again after adding pages.
 */
const PPS_PAGES_VERSION = 3;

function pps_create_pages() {
  if ((int) get_option('pps_pages_version') >= PPS_PAGES_VERSION) {
    return;
  }

  // $content is only used when the page is created or still empty, so editor changes are never overwritten.
  $ensure = function ($slug, $title, $parent = 0, $content = '') {
    $path = $parent ? get_post_field('post_name', $parent) . '/' . $slug : $slug;
    $page = get_page_by_path($path);
    if ($page) {
      $update = array();
      if ($page->post_status !== 'publish') {
        $update['post_status'] = 'publish';
      }
      if ($content && trim($page->post_content) === '') {
        $update['post_content'] = $content;
      }
      if ($update) {
        wp_update_post(array('ID' => $page->ID) + $update);
      }
      return $page->ID;
    }
    return wp_insert_post(array(
      'post_type'    => 'page',
      'post_status'  => 'publish',
      'post_title'   => $title,
      'post_name'    => $slug,
      'post_parent'  => $parent,
      'post_content' => $content,
    ));
  };

  $home = $ensure('home', 'Home');
  $ensure('about', 'About');
  $services = $ensure('services', 'Services');
  foreach (pps_services() as $slug => $service) {
    $ensure($slug, $service['name'], $services);
  }
  $ensure('projects', 'Projects');
  $contact = $ensure('contact', 'Contact');
  $ensure('service-areas', 'Service Areas', $contact);
  $ensure('privacy-policy', 'Privacy Policy');
  $ensure('terms-and-conditions', 'Terms and Conditions', 0, file_get_contents(get_theme_file_path('/inc/content/terms-and-conditions.html')));

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
