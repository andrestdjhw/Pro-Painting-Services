<?php
/**
 * Titles, meta descriptions and structured data.
 *
 * If an SEO plugin (Yoast, Rank Math) is installed later, disable this file's
 * hooks to avoid duplicate tags.
 */

function pps_page_seo() {
  if ($service = pps_current_service()) {
    return array('title' => $service['seo_title'], 'description' => $service['meta']);
  }

  $pages = array(
    'front'    => array('title' => 'Commercial & Multifamily Painting in New Mexico | Pro Painting Services', 'description' => 'Pro Painting Services works with commercial properties, apartment communities and hospitality facilities across New Mexico. Request a project quote.'),
    'about'    => array('title' => 'About Pro Painting Services | Albuquerque, NM', 'description' => 'Commercial, multifamily and hospitality painting services across New Mexico, along with select renovation work for office spaces.'),
    'services' => array('title' => 'Painting Services for Commercial & Managed Properties | PPS', 'description' => 'Commercial exterior, multifamily, interior unit, hotel and specialty surface painting in New Mexico. Find the service that fits your property.'),
    'projects' => array('title' => 'Projects | Pro Painting Services', 'description' => 'Browse Pro Painting Services work by property type and painting scope across New Mexico.'),
    'contact'  => array('title' => 'Contact & Request a Quote | Pro Painting Services', 'description' => 'Call (505) 582-7126 or send a project request to Pro Painting Services. Serving commercial and managed properties across New Mexico.'),
  );

  if (is_front_page()) {
    return $pages['front'];
  }
  if (is_page()) {
    return $pages[get_queried_object()->post_name] ?? null;
  }
  return null;
}

function pps_document_title($title) {
  $seo = pps_page_seo();
  return $seo['title'] ?? $title;
}
add_filter('pre_get_document_title', 'pps_document_title');

function pps_head_meta() {
  $seo = pps_page_seo();
  if (!empty($seo['description'])) {
    echo '<meta name="description" content="' . esc_attr($seo['description']) . "\">\n";
  }
  echo '<meta name="theme-color" content="#111111">' . "\n";

  foreach (pps_structured_data() as $graph) {
    echo '<script type="application/ld+json">' . wp_json_encode($graph, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "</script>\n";
  }
}
add_action('wp_head', 'pps_head_meta', 1);

function pps_structured_data() {
  $biz = pps_business();
  $org = array(
    '@context'   => 'https://schema.org',
    '@type'      => 'HousePainter',
    '@id'        => home_url('/#business'),
    'name'       => $biz['name'],
    'url'        => home_url('/'),
    'telephone'  => '+1-505-582-7126',
    'email'      => $biz['email'],
    'address'    => array(
      '@type'           => 'PostalAddress',
      'streetAddress'   => $biz['street'],
      'addressLocality' => $biz['city'],
      'addressRegion'   => $biz['region'],
      'postalCode'      => $biz['postal'],
      'addressCountry'  => 'US',
    ),
    'areaServed' => array('@type' => 'State', 'name' => 'New Mexico'),
  );
  if ($logo = pps_asset_url('logo')) {
    $org['logo'] = $logo;
  }
  $same_as = array_values(array_filter(array_merge(array($biz['gbp_url']), array_values($biz['socials']))));
  if ($same_as) {
    $org['sameAs'] = $same_as;
  }

  $data = array();
  if (is_front_page() || is_page('contact')) {
    $data[] = $org;
  }

  if ($service = pps_current_service()) {
    $data[] = array(
      '@context'    => 'https://schema.org',
      '@type'       => 'Service',
      'name'        => $service['name'],
      'description' => $service['meta'],
      'url'         => pps_service_url($service['slug']),
      'provider'    => array('@id' => home_url('/#business')),
      'areaServed'  => array('@type' => 'State', 'name' => 'New Mexico'),
    );
    if (!is_front_page()) {
      $data[] = $org;
    }
  }

  if (is_page() && !is_front_page()) {
    $crumbs = array(array('Home', home_url('/')));
    $page   = get_queried_object();
    foreach (array_reverse(get_post_ancestors($page)) as $ancestor) {
      $crumbs[] = array(get_the_title($ancestor), get_permalink($ancestor));
    }
    $crumbs[] = array(get_the_title($page), get_permalink($page));

    $data[] = array(
      '@context'        => 'https://schema.org',
      '@type'           => 'BreadcrumbList',
      'itemListElement' => array_map(fn($crumb, $i) => array(
        '@type'    => 'ListItem',
        'position' => $i + 1,
        'name'     => $crumb[0],
        'item'     => $crumb[1],
      ), $crumbs, array_keys($crumbs)),
    );
  }

  return $data;
}
