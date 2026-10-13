<?php get_header(); ?>

<section class="pps-section">
  <div class="pps-container max-w-3xl text-center">
    <p class="pps-eyebrow">404</p>
    <h1 class="pps-heading">Page Not Found.</h1>
    <p class="mt-4 text-lg text-steel-600">The page you're looking for doesn't exist. Explore our services or contact our team.</p>
    <div class="mt-8 flex flex-wrap justify-center gap-4">
      <?php pps_button('View Services', home_url('/services/'), 'secondary'); ?>
      <?php pps_button('Request a Quote', home_url('/contact/'), 'primary'); ?>
    </div>
  </div>
</section>

<?php get_footer();
