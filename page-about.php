<?php get_header(); ?>

<!-- 01 | Who we are -->
<section class="pps-section">
  <div class="pps-container grid items-center gap-10 lg:grid-cols-2 lg:gap-16">
    <div>
      <p class="pps-eyebrow">About Pro Painting Services</p>
      <h1 class="pps-h1">Painting Services Focused on Commercial and Managed Properties.</h1>
      <p class="mt-6 text-lg text-steel-600">Pro Painting Services provides commercial, multifamily and hospitality painting services across New Mexico, along with select renovation work for office spaces. We understand that every property has different needs, and the first step is to understand yours.</p>
    </div>
    <?php pps_image('about-team', 'Painters working on a building exterior', 'aspect-[4/3] w-full rounded-sm object-cover', true); ?>
  </div>
</section>

<!-- 02 | What we serve -->
<section class="pps-section bg-steel-50">
  <div class="pps-container">
    <?php pps_section_heading('Built for the Properties You Operate.', '', '', array('rule' => true)); ?>
    <div class="mt-12 grid gap-6 md:grid-cols-3">
      <?php
      $serve = array(
        array('Commercial Facilities', 'Painting for buildings and business spaces.', 'category-commercial'),
        array('Apartment Communities', 'Exterior buildings and interior units.', 'category-multifamily'),
        array('Hotels', 'Interior and exterior surfaces.', 'category-hospitality'),
      );
      foreach ($serve as [$title, $text, $image]) : ?>
        <div class="pps-card overflow-hidden rounded-sm bg-white shadow-sm ring-1 ring-steel-200">
          <?php pps_image($image, $title, 'aspect-[4/3] w-full object-cover'); ?>
          <div class="p-6">
            <h3 class="pps-title"><?php echo esc_html($title); ?></h3>
            <p class="mt-1 text-steel-600"><?php echo esc_html($text); ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- 03 | Project conversation -->
<section class="pps-section">
  <div class="pps-container">
    <?php pps_section_heading('A Clear Starting Point.', '', '', array('rule' => true)); ?>
    <div class="mt-12">
      <?php pps_steps(array('Share your property and location.', 'Describe the surfaces and scope.', 'Connect with our team to discuss next steps.')); ?>
    </div>
  </div>
</section>

<!-- 04 | Proof and next step -->
<section class="pps-section relative isolate overflow-hidden bg-ink text-white">
  <?php pps_watermark(); ?>
  <div class="pps-container grid items-center gap-10 lg:grid-cols-2 lg:gap-16">
    <div class="grid grid-cols-2 gap-4">
      <?php pps_image('about-project-1', 'Modern apartment building exterior', 'aspect-[3/4] w-full rounded-sm object-cover'); ?>
      <?php pps_image('about-project-2', 'Hotel lobby interior', 'mt-10 aspect-[3/4] w-full rounded-sm object-cover'); ?>
    </div>
    <div>
      <h2 class="pps-heading">Our Projects Tell the Story.</h2>
      <p class="mt-4 text-lg text-steel-300">Explore real work completed across the properties we serve.</p>
      <div class="mt-8 flex flex-wrap gap-4">
        <?php pps_button('View Projects', home_url('/projects/'), 'light'); ?>
        <?php pps_button('Request a Quote', home_url('/contact/'), 'primary', 'data-track="service_quote_click"'); ?>
      </div>
    </div>
  </div>
</section>

<?php get_footer();
