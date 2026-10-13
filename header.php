<!DOCTYPE html>
<html <?php language_attributes(); ?>>
  <head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
  </head>
  <body <?php body_class('bg-white pb-14 font-sans text-ink antialiased lg:pb-0'); ?>>
    <?php wp_body_open(); ?>
    <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[100] focus:rounded focus:bg-white focus:px-4 focus:py-2">Skip to content</a>

    <!-- React: src/components/Navbar.js. The markup inside is the crawlable fallback React replaces. -->
    <div id="pps-navbar" class="sticky top-0 z-50 h-[72px] bg-white shadow-sm lg:h-20">
      <nav aria-label="Main" class="mx-auto flex h-full max-w-7xl items-center gap-6 px-4">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="font-display text-xl font-bold uppercase">Pro Painting Services</a>
        <ul class="hidden gap-6 lg:flex">
          <li><a href="<?php echo esc_url(home_url('/about/')); ?>">About</a></li>
          <li><a href="<?php echo esc_url(home_url('/services/')); ?>">Services</a></li>
          <li><a href="<?php echo esc_url(home_url('/projects/')); ?>">Projects</a></li>
          <li><a href="<?php echo esc_url(home_url('/contact/')); ?>">Contact</a></li>
        </ul>
      </nav>
    </div>

    <main id="main">
