<!DOCTYPE html>
<html <?php language_attributes(); ?>>
  <head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
  </head>
  <body <?php body_class('bg-stone font-sans text-ink antialiased'); ?>>
    <?php wp_body_open(); ?>
    <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[100] focus:rounded focus:bg-white focus:px-4 focus:py-2">Skip to content</a>

    <?php $biz = pps_business(); ?>
    <!-- React: src/components/Topbar.js (desktop/tablet only; mobile has the bottom call bar). -->
    <div id="pps-topbar" class="hidden h-10 bg-black md:block">
      <div class="mx-auto flex h-full max-w-site items-center gap-6 px-4 text-xs text-steel-300 sm:px-6 lg:px-8 xl:px-12">
        <a href="<?php echo esc_url($biz['phone_href']); ?>"><?php echo esc_html($biz['phone']); ?></a>
        <a href="mailto:<?php echo esc_attr($biz['email']); ?>"><?php echo esc_html($biz['email']); ?></a>
      </div>
    </div>

    <!-- React: src/components/Navbar.js. The markup inside is the crawlable fallback React replaces. -->
    <div id="pps-navbar" class="sticky top-0 z-50 h-[72px] border-t border-white/5 bg-ink text-white shadow-sm lg:h-20">
      <nav aria-label="Main" class="mx-auto flex h-full max-w-site items-center gap-6 px-4">
        <a href="<?php echo esc_url(home_url('/')); ?>"><img src="<?php echo esc_url(pps_asset_url('brand/logo-light')); ?>" alt="Pro Painting Services" width="132" height="56" class="h-12 w-auto lg:h-16"></a>
        <ul class="hidden gap-6 lg:flex">
          <li><a href="<?php echo esc_url(home_url('/about/')); ?>">About</a></li>
          <li><a href="<?php echo esc_url(home_url('/services/')); ?>">Services</a></li>
          <li><a href="<?php echo esc_url(home_url('/projects/')); ?>">Projects</a></li>
          <li><a href="<?php echo esc_url(home_url('/contact/')); ?>">Contact</a></li>
        </ul>
      </nav>
    </div>

    <main id="main">
