<?php
/**
 * /contact/service-areas/ — one Google map per city / county served.
 * Areas come from pps_service_area_maps() in inc/config.php.
 */
get_header();
$areas = pps_service_area_maps();
?>

<?php pps_page_hero('Areas We Serve.', 'Based in Albuquerque, Pro Painting Services works with commercial, multifamily and hospitality properties throughout New Mexico. Select out-of-state project inquiries are welcome.', 'projects-hero'); ?>

<!-- Quick jump list -->
<nav aria-label="Service areas" class="border-b border-steel-200 bg-sand">
  <ul class="pps-container flex flex-wrap items-center gap-3 py-5">
    <?php foreach ($areas as $area) : ?>
      <li>
        <a href="#<?php echo esc_attr(sanitize_title($area['name'])); ?>" class="inline-flex items-center gap-2 rounded-full bg-white px-5 py-2 font-semibold ring-1 ring-steel-200 transition hover:text-brand hover:ring-brand">
          <?php echo pps_icon('pin', 'h-4 w-4 text-brand'); ?><?php echo esc_html($area['name']); ?>
        </a>
      </li>
    <?php endforeach; ?>
  </ul>
</nav>

<!-- One section per area, alternating map side -->
<?php foreach ($areas as $i => $area) :
  $flip = $i % 2 === 1;
  ?>
  <section id="<?php echo esc_attr(sanitize_title($area['name'])); ?>" class="pps-section <?php echo $i ? 'border-t border-steel-200' : ''; ?>">
    <div class="pps-container grid items-center gap-10 lg:grid-cols-2 lg:gap-16">
      <div class="pps-map aspect-[4/3] overflow-hidden rounded-sm shadow-sm ring-1 ring-steel-200 <?php echo $flip ? 'lg:order-2' : ''; ?>">
        <?php pps_map($area['zoom'], '', $area['map'], "Map of {$area['name']}, {$area['county']}"); ?>
      </div>
      <div>
        <?php pps_section_heading($area['name'], $area['county'], $area['text'], array('rule' => true)); ?>
        <div class="mt-8 flex flex-wrap items-center gap-x-8 gap-y-4">
          <?php pps_button('Request a Quote', home_url('/contact/'), 'primary', 'data-track="service_quote_click"'); ?>
          <?php pps_call_button('link-dark'); ?>
        </div>
      </div>
    </div>
  </section>
<?php endforeach; ?>

<!-- Out-of-area CTA -->
<section data-parallax class="relative isolate overflow-hidden bg-ink py-16 text-white md:py-24">
  <?php pps_image('services-cta', '', 'absolute inset-0 -z-20 h-full w-full object-cover opacity-30'); ?>
  <?php pps_watermark(); ?>
  <div class="pps-container flex flex-col gap-8 lg:flex-row lg:items-center lg:justify-between">
    <div class="max-w-2xl">
      <h2 class="pps-heading">Don’t See Your City?</h2>
      <p class="mt-4 text-lg text-steel-200">Tell us where your property is located. Our team can review the request and discuss availability, including select projects outside New Mexico.</p>
    </div>
    <div class="flex flex-wrap items-center gap-x-8 gap-y-4">
      <?php pps_button('Contact Our Team', home_url('/contact/'), 'primary', 'data-track="service_quote_click"'); ?>
      <?php pps_call_button(); ?>
    </div>
  </div>
</section>

<?php get_footer();
