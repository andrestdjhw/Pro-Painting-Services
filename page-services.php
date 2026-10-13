<?php
get_header();
$core      = array('commercial-exterior-painting', 'multifamily-exterior-painting', 'interior-unit-painting', 'hotel-painting', 'office-renovations');
$specialty = array('pool-area-painting', 'parking-area-painting', 'metal-painting', 'signage-painting');
?>

<!-- 01 | Services overview -->
<?php pps_page_hero('Painting Services for Commercial & Managed Properties.', 'Find a service that matches your property and scope. From exterior buildings and interior units to specialty surface painting, explore the work Pro Painting Services offers.', 'services-hero'); ?>

<section class="pps-section">
  <div class="pps-container">
    <h2 class="font-display text-3xl font-bold uppercase">Property Painting Services</h2>
    <div class="mt-8"><?php pps_service_cards($core); ?></div>

    <div id="specialty" class="mt-20">
      <h2 class="font-display text-3xl font-bold uppercase">Specialty Surface Painting</h2>
      <p class="mt-2 max-w-3xl text-steel-600">Painting for specific property surfaces and elements. Surface suitability and scope are reviewed for each request.</p>
      <div class="mt-8"><?php pps_service_cards($specialty); ?></div>
    </div>
  </div>
</section>

<!-- 02 | Service hub CTA -->
<section class="relative isolate overflow-hidden bg-ink py-16 text-white md:py-24">
  <?php pps_image('services-cta', '', 'absolute inset-0 -z-10 h-full w-full object-cover opacity-30'); ?>
  <div class="pps-container flex flex-col gap-8 lg:flex-row lg:items-center lg:justify-between">
    <div class="max-w-2xl">
      <h2 class="pps-heading">Not Sure Which Service Fits?</h2>
      <p class="mt-4 text-lg text-steel-200">Tell us about your property and what you need painted. Our team can help clarify the next step.</p>
    </div>
    <div class="flex flex-wrap gap-4">
      <?php pps_button('Request a Quote', home_url('/contact/'), 'primary', 'data-track="service_quote_click"'); ?>
      <?php pps_call_button('light'); ?>
    </div>
  </div>
</section>

<?php get_footer();
