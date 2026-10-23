<?php
/**
 * Small rendering helpers shared by templates.
 */

/**
 * Theme image resolution. For each slot key, sources are tried in this order:
 *   1. A real file in /assets/images/{$key}.*       (e.g. brand assets)
 *   2. A client photo from the Media Library        (pps_photo_map() in config.php)
 *   3. A temporary stock photo in /assets/images/stock/{$key}.*
 * $key may be an array of fallback keys: every key is checked for a real/client photo
 * before any stock photo is used, so real photos always win.
 *
 * Returns array('file', $theme_relative_path) | array('attachment', $id) | null.
 */
function pps_resolve_image($key, $include_stock = true) {
  $theme_file = function ($dir, $k) {
    foreach (array('webp', 'avif', 'jpg', 'jpeg', 'png', 'svg') as $ext) {
      if (file_exists(get_theme_file_path("{$dir}{$k}.{$ext}"))) {
        return "{$dir}{$k}.{$ext}";
      }
    }
    return '';
  };

  foreach ((array) $key as $k) {
    if ($file = $theme_file('/assets/images/', $k)) {
      return array('file', $file);
    }
    if ($id = pps_photo_attachment_id($k)) {
      return array('attachment', $id);
    }
  }
  if ($include_stock) {
    foreach ((array) $key as $k) {
      if ($file = $theme_file('/assets/images/stock/', $k)) {
        return array('file', $file);
      }
    }
  }
  return null;
}

/** Attachment ID of the client photo mapped to $key, or 0. One query per request. */
function pps_photo_attachment_id($key) {
  static $ids = null;
  if ($ids === null) {
    global $wpdb;
    $map = pps_photo_map();
    $ids = array();
    if ($map) {
      $placeholders = implode(',', array_fill(0, count($map), '%s'));
      $rows = $wpdb->get_results($wpdb->prepare(
        "SELECT post_id, meta_value FROM {$wpdb->postmeta} WHERE meta_key = '_wp_attached_file' AND meta_value IN ($placeholders)",
        array_values($map)
      ));
      $by_file = wp_list_pluck($rows, 'post_id', 'meta_value');
      foreach ($map as $slot => $file) {
        $ids[$slot] = (int) ($by_file[$file] ?? 0);
      }
    }
  }
  return $ids[$key] ?? 0;
}

function pps_asset_url($key, $size = 'medium_large') {
  $found = pps_resolve_image($key);
  if (!$found) {
    return '';
  }
  return $found[0] === 'file' ? get_theme_file_uri($found[1]) : (string) wp_get_attachment_image_url($found[1], $size);
}

/** True when a real (non-stock) photo exists for $key. */
function pps_has_real_image($key) {
  return (bool) pps_resolve_image($key, false);
}

/**
 * Outputs a photo (real, else stock), or a labeled placeholder when neither exists.
 * Media Library photos get a responsive srcset; $sizes defaults to full width for
 * background images (absolute inset-0) and half width otherwise.
 */
function pps_image($key, $alt, $class = '', $eager = false, $sizes = null) {
  $found = pps_resolve_image($key);

  if ($found && $found[0] === 'attachment') {
    $sizes = $sizes ?? (str_contains($class, 'inset-0') ? '100vw' : '(min-width: 1024px) 50vw, 100vw');
    $attrs = array(
      'alt'      => $alt,
      'class'    => $class,
      'loading'  => $eager ? 'eager' : 'lazy',
      'decoding' => 'async',
      'sizes'    => $sizes,
    );
    if ($eager) {
      $attrs['fetchpriority'] = 'high';
    }
    echo wp_get_attachment_image($found[1], '1536x1536', false, $attrs);
    return;
  }

  if ($found) {
    printf(
      '<img src="%s" alt="%s" class="%s" loading="%s" decoding="async"%s>',
      esc_url(get_theme_file_uri($found[1])),
      esc_attr($alt),
      esc_attr($class),
      $eager ? 'eager' : 'lazy',
      $eager ? ' fetchpriority="high"' : ''
    );
    return;
  }

  $first = ((array) $key)[0];
  printf(
    '<div class="pps-placeholder %s" role="img" aria-label="%s"><span>%s</span></div>',
    esc_attr($class),
    esc_attr($alt),
    esc_html("assets/images/{$first}")
  );
}

/** Never send the ~2 MB originals: cap responsive images at the 1536px rendition. */
add_filter('max_srcset_image_width', fn() => 1536);

/** Inline SVG icons used across templates. */
function pps_icon($name, $class = 'h-5 w-5') {
  $paths = array(
    'arrow' => '<path d="M5 12h14M13 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
    'phone' => '<path d="M6.6 10.8a15.1 15.1 0 0 0 6.6 6.6l2.2-2.2a1 1 0 0 1 1-.25 11.4 11.4 0 0 0 3.6.57 1 1 0 0 1 1 1V20a1 1 0 0 1-1 1A17 17 0 0 1 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1c0 1.25.2 2.45.57 3.57a1 1 0 0 1-.25 1z" fill="currentColor"/>',
    'pin'   => '<path d="M12 2a7 7 0 0 0-7 7c0 5.25 7 13 7 13s7-7.75 7-13a7 7 0 0 0-7-7zm0 9.5A2.5 2.5 0 1 1 12 6.5a2.5 2.5 0 0 1 0 5z" fill="currentColor"/>',
    'mail'  => '<path d="M4 5h16a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1zm0 2v.4l8 5 8-5V7l-8 5-8-5z" fill="currentColor"/>',
    'star'  => '<path d="M12 2.5l2.9 6.1 6.6.8-4.9 4.6 1.3 6.6L12 17.3l-5.9 3.3 1.3-6.6-4.9-4.6 6.6-.8z" fill="currentColor"/>',
  );
  return '<svg class="' . esc_attr($class) . '" viewBox="0 0 24 24" aria-hidden="true">' . ($paths[$name] ?? '') . '</svg>';
}

/** Button link with trailing arrow. Variants: primary / secondary / outline / light. */
function pps_button($label, $url, $variant = 'primary', $attrs = '', $arrow = true) {
  $variant = in_array($variant, array('primary', 'secondary', 'outline', 'light'), true) ? $variant : 'primary';
  printf(
    '<a href="%s" class="pps-btn pps-btn-%s" %s><span class="pps-btn__text">%s</span>%s</a>',
    esc_url($url),
    esc_attr($variant),
    $attrs,
    esc_html($label),
    $arrow ? pps_icon('arrow', 'pps-arrow') : ''
  );
}

/** Phone link with icon. 'link' renders as plain text (hero style); other variants as buttons. */
function pps_call_button($variant = 'link', $label = null) {
  $biz   = pps_business();
  $label = $label ?? "Call {$biz['phone']}";
  if ($variant === 'link' || $variant === 'link-dark') {
    $color = $variant === 'link' ? 'text-white hover:text-white/80' : 'text-ink hover:text-brand';
    printf(
      '<a href="%s" class="inline-flex min-h-12 items-center gap-3 font-semibold %s">%s%s</a>',
      esc_url($biz['phone_href']),
      esc_attr($color),
      pps_icon('phone', 'h-5 w-5'),
      esc_html($label)
    );
    return;
  }
  printf(
    '<a href="%s" class="pps-btn %s">%s<span class="pps-btn__text">%s</span></a>',
    esc_url($biz['phone_href']),
    $variant === 'light' ? 'pps-btn-light' : 'pps-btn-outline',
    pps_icon('phone', 'h-4 w-4'),
    esc_html($label)
  );
}

/**
 * Eyebrow + heading + optional intro.
 * $opts: 'align' (left|center), 'tag' (h2), 'rule' (red line above), 'dark' (light text).
 */
function pps_section_heading($heading, $eyebrow = '', $intro = '', $opts = array()) {
  $opts  = array_merge(array('align' => 'left', 'tag' => 'h2', 'rule' => false, 'dark' => false), $opts);
  $wrap  = ($opts['align'] === 'center' ? 'max-w-3xl mx-auto text-center' : 'max-w-3xl') . ($opts['rule'] ? ' pps-rule' : '');
  $intro_color = $opts['dark'] ? 'text-steel-300' : 'text-steel-600';
  echo '<div class="' . esc_attr($wrap) . '">';
  if ($eyebrow) {
    echo '<p class="pps-eyebrow">' . esc_html($eyebrow) . '</p>';
  }
  printf('<%1$s class="pps-heading">%2$s</%1$s>', tag_escape($opts['tag']), esc_html($heading));
  if ($intro) {
    echo '<p class="mt-4 text-lg ' . esc_attr($intro_color) . '">' . esc_html($intro) . '</p>';
  }
  echo '</div>';
}

/** Accessible accordion using native <details>; answers stay in the HTML for crawlers. */
function pps_faq($items) {
  echo '<div class="divide-y divide-steel-200 border-y border-steel-200">';
  foreach ($items as $item) {
    printf(
      '<details class="group py-5"><summary class="flex cursor-pointer list-none items-center justify-between gap-6 text-lg font-semibold text-ink"><span>%s</span><span class="pps-faq-icon" aria-hidden="true"></span></summary><p class="mt-3 max-w-3xl text-steel-600">%s</p></details>',
      esc_html($item['q']),
      esc_html($item['a'])
    );
  }
  echo '</div>';
}

/**
 * Mount point for the React quote form.
 * $variant: 'full' (Home / Contact) or 'mini' (service landing pages).
 */
function pps_quote_form($variant = 'full', $service = '', $heading = 'Request a Project Quote') {
  printf(
    '<div class="pps-quote-form" data-variant="%s" data-service="%s" data-heading="%s"><noscript><p>Please call %s or email %s to request a quote.</p></noscript></div>',
    esc_attr($variant),
    esc_attr($service),
    esc_attr($heading),
    esc_html(pps_business()['phone']),
    esc_html(pps_business()['email'])
  );
}

/** Phone + email side panel used next to forms. */
function pps_contact_panel($dark = false) {
  $biz   = pps_business();
  $muted = $dark ? 'text-steel-300' : 'text-steel-600';
  $text  = $dark ? 'text-white' : 'text-ink';
  ?>
  <ul class="space-y-4">
    <li>
      <p class="text-sm uppercase tracking-wider <?php echo esc_attr($muted); ?>">Call</p>
      <a href="<?php echo esc_url($biz['phone_href']); ?>" class="text-xl font-semibold hover:text-brand <?php echo esc_attr($text); ?>"><?php echo esc_html($biz['phone']); ?></a>
    </li>
    <li>
      <p class="text-sm uppercase tracking-wider <?php echo esc_attr($muted); ?>">Email</p>
      <a href="mailto:<?php echo esc_attr($biz['email']); ?>" class="text-xl font-semibold break-all hover:text-brand <?php echo esc_attr($text); ?>"><?php echo esc_html($biz['email']); ?></a>
    </li>
  </ul>
  <?php
}

/** Photo card linking to a page: image with title overlay, optional text below. */
function pps_card($title, $text, $url, $image_key) {
  ?>
  <a href="<?php echo esc_url($url); ?>" class="pps-card group flex flex-col overflow-hidden rounded-sm bg-white shadow-sm ring-1 ring-steel-200">
    <div class="relative isolate overflow-hidden">
      <?php pps_image($image_key, $title, 'aspect-[4/3] w-full object-cover transition duration-500 group-hover:scale-105'); ?>
      <div class="absolute inset-0 bg-linear-to-t from-ink/85 via-ink/10 to-transparent" aria-hidden="true"></div>
      <h3 class="pps-title absolute inset-x-0 bottom-0 p-5 text-white"><?php echo esc_html($title); ?></h3>
    </div>
    <?php if ($text) : ?>
      <div class="flex flex-1 flex-col p-5">
        <p class="flex-1 text-steel-600"><?php echo esc_html($text); ?></p>
        <span class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-brand">Learn more <?php echo pps_icon('arrow', 'pps-arrow'); ?></span>
      </div>
    <?php endif; ?>
  </a>
  <?php
}

/** Service cards grid. $slugs limits / orders the services shown. */
function pps_service_cards($slugs = null) {
  $services = pps_services();
  echo '<div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">';
  foreach ($slugs ?? array_keys($services) as $slug) {
    pps_card($services[$slug]['name'], $services[$slug]['summary'], pps_service_url($slug), "service-{$slug}");
  }
  echo '</div>';
}

/** Three-step horizontal timeline; numbers use the logo's chrome lettering. */
function pps_steps($steps, $dark = false) {
  echo '<ol class="grid gap-8 md:grid-cols-3">';
  foreach ($steps as $i => $step) {
    printf(
      '<li class="relative border-t-[3px] border-brand pt-6"><span class="font-display text-6xl font-extrabold %s">%02d</span><p class="mt-3 text-lg font-semibold">%s</p></li>',
      $dark ? 'pps-chrome' : 'pps-chrome-dark',
      $i + 1,
      esc_html($step)
    );
  }
  echo '</ol>';
}

/** Faint logo building mark for dark sections (the section needs `relative isolate overflow-hidden`). */
function pps_watermark($class = '-right-16 -bottom-20 w-[28rem]') {
  $mark = pps_asset_url('brand/logo-mark');
  if ($mark) {
    printf('<img src="%s" alt="" aria-hidden="true" loading="lazy" class="pps-watermark %s">', esc_url($mark), esc_attr($class));
  }
}

/** Compact hero for inner pages. */
function pps_page_hero($h1, $intro = '', $image_key = '') {
  ?>
  <section data-hero class="relative isolate overflow-hidden bg-ink py-20 text-white md:py-28">
    <?php if ($image_key) {
      pps_image($image_key, '', 'absolute inset-0 -z-20 h-full w-full object-cover opacity-35', true);
    }
    pps_watermark('-right-12 -bottom-28 w-[26rem]'); ?>
    <div class="pps-container">
      <div class="pps-hero-in max-w-3xl">
        <h1 class="pps-h1"><?php echo esc_html($h1); ?></h1>
        <?php if ($intro) : ?>
          <p class="mt-6 text-lg text-white/85"><?php echo esc_html($intro); ?></p>
        <?php endif; ?>
      </div>
    </div>
  </section>
  <?php
}

/** Note visible only to editors, for sections waiting on verified content. */
function pps_admin_hint($message, $class = '') {
  if (!current_user_can('edit_posts')) {
    return;
  }
  printf(
    '<p class="rounded-sm border-2 border-dashed border-brand/50 bg-brand/5 p-4 text-sm text-brand-dark %s"><strong>Editor note:</strong> %s</p>',
    esc_attr($class),
    esc_html($message)
  );
}

/**
 * Google Maps embed of the office address (no API key needed). Lazy-loaded so it
 * doesn't slow the first paint. The section needs a sized wrapper; the iframe fills it.
 */
function pps_map($zoom = 11, $class = '', $place = '', $title = '') {
  $biz   = pps_business();
  $query = rawurlencode($place ?: "{$biz['street']}, {$biz['city']}, {$biz['region']} {$biz['postal']}");
  $title = $title ?: "Map: {$biz['name']}, {$biz['city']}, {$biz['region']}";
  printf(
    '<iframe src="%s" class="h-full w-full border-0 %s" title="%s" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>',
    esc_url("https://www.google.com/maps?q={$query}&z=" . (int) $zoom . '&hl=en&output=embed'),
    esc_attr($class),
    esc_attr($title)
  );
}

/**
 * Dark "Let's Understand Your Project." band: photo background (parallax), logo watermark,
 * heading + intro and the 3 brief steps. Used on the Home page and every service landing.
 */
function pps_process_band($text, $image_keys = array('painting-process')) {
  ?>
  <section data-parallax class="relative isolate overflow-hidden bg-ink py-16 text-white md:py-24">
    <?php pps_image($image_keys, 'Painters at work', 'absolute inset-0 -z-20 h-full w-full object-cover opacity-30'); ?>
    <?php pps_watermark(); ?>
    <div class="pps-container">
      <div class="max-w-3xl">
        <h2 class="pps-heading">Let’s Understand Your Project.</h2>
        <p class="mt-4 text-lg text-steel-200"><?php echo esc_html($text); ?></p>
      </div>
      <div class="mt-12">
        <?php pps_steps(array('Share property details', 'Discuss scope', 'We get the job done'), true); ?>
      </div>
    </div>
  </section>
  <?php
}
