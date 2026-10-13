<?php
/**
 * Small rendering helpers shared by templates.
 */

/**
 * URL of a theme image in /assets/images/{$key}.(webp|jpg|png|svg), or '' if missing.
 */
function pps_asset_url($key) {
  foreach (array('webp', 'avif', 'jpg', 'png', 'svg') as $ext) {
    $file = "/assets/images/{$key}.{$ext}";
    if (file_exists(get_theme_file_path($file))) {
      return get_theme_file_uri($file);
    }
  }
  return '';
}

/**
 * Outputs a real project photo, or a labeled placeholder until the photo exists.
 * Drop the file into /assets/images/ using $key as the file name.
 */
function pps_image($key, $alt, $class = '', $eager = false) {
  $url = pps_asset_url($key);
  if ($url) {
    printf(
      '<img src="%s" alt="%s" class="%s" loading="%s" decoding="async"%s>',
      esc_url($url),
      esc_attr($alt),
      esc_attr($class),
      $eager ? 'eager' : 'lazy',
      $eager ? ' fetchpriority="high"' : ''
    );
    return;
  }
  printf(
    '<div class="pps-placeholder %s" role="img" aria-label="%s"><span>%s</span></div>',
    esc_attr($class),
    esc_attr($alt),
    esc_html("assets/images/{$key}")
  );
}

/** Primary / secondary / light button link. */
function pps_button($label, $url, $variant = 'primary', $attrs = '') {
  $classes = array(
    'primary'   => 'bg-brand text-white hover:bg-brand-dark',
    'secondary' => 'bg-ink text-white hover:bg-ink/85',
    'outline'   => 'border-2 border-ink text-ink hover:bg-ink hover:text-white',
    'light'     => 'border-2 border-white text-white hover:bg-white hover:text-ink',
  );
  printf(
    '<a href="%s" class="pps-btn %s" %s>%s</a>',
    esc_url($url),
    esc_attr($classes[$variant] ?? $classes['primary']),
    $attrs,
    esc_html($label)
  );
}

function pps_call_button($variant = 'light', $label = null) {
  $biz = pps_business();
  pps_button($label ?? "Call {$biz['phone']}", $biz['phone_href'], $variant);
}

/** Eyebrow + heading + optional intro. */
function pps_section_heading($heading, $eyebrow = '', $intro = '', $align = 'left', $tag = 'h2') {
  $center = $align === 'center' ? 'text-center mx-auto' : '';
  echo '<div class="max-w-3xl ' . esc_attr($center) . '">';
  if ($eyebrow) {
    echo '<p class="pps-eyebrow">' . esc_html($eyebrow) . '</p>';
  }
  printf('<%1$s class="pps-heading">%2$s</%1$s>', tag_escape($tag), esc_html($heading));
  if ($intro) {
    echo '<p class="mt-4 text-lg text-steel-600">' . esc_html($intro) . '</p>';
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

/** Photo card linking to a page. */
function pps_card($title, $text, $url, $image_key) {
  ?>
  <a href="<?php echo esc_url($url); ?>" class="group flex flex-col overflow-hidden rounded-lg bg-white shadow-sm ring-1 ring-steel-200 transition hover:-translate-y-1 hover:shadow-lg">
    <?php pps_image($image_key, $title, 'aspect-[4/3] w-full object-cover'); ?>
    <div class="flex flex-1 flex-col p-6">
      <h3 class="font-display text-2xl font-bold uppercase text-ink"><?php echo esc_html($title); ?></h3>
      <p class="mt-2 flex-1 text-steel-600"><?php echo esc_html($text); ?></p>
      <span class="mt-4 inline-flex items-center gap-2 font-semibold text-brand">Learn more <span aria-hidden="true" class="transition group-hover:translate-x-1">&rarr;</span></span>
    </div>
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

/** Three-step horizontal timeline. */
function pps_steps($steps) {
  echo '<ol class="grid gap-8 md:grid-cols-3">';
  foreach ($steps as $i => $step) {
    printf(
      '<li class="relative border-t-4 border-brand pt-6"><span class="font-display text-6xl font-bold text-brand/90">%02d</span><p class="mt-2 text-lg font-semibold">%s</p></li>',
      $i + 1,
      esc_html($step)
    );
  }
  echo '</ol>';
}

/** Compact hero for inner pages. */
function pps_page_hero($h1, $intro = '', $image_key = '') {
  ?>
  <section class="relative isolate overflow-hidden bg-ink py-20 text-white md:py-28">
    <?php if ($image_key) {
      pps_image($image_key, '', 'absolute inset-0 -z-10 h-full w-full object-cover opacity-35', true);
    } ?>
    <div class="pps-container">
      <div class="max-w-3xl">
        <h1 class="font-display text-5xl leading-[1] font-bold uppercase sm:text-6xl"><?php echo esc_html($h1); ?></h1>
        <?php if ($intro) : ?>
          <p class="mt-6 text-lg text-white/85"><?php echo esc_html($intro); ?></p>
        <?php endif; ?>
      </div>
    </div>
  </section>
  <?php
}
