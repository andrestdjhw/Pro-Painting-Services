<?php
get_header();
$biz = pps_business();
?>

<!-- 01 | Hero -->
<section class="relative isolate flex min-h-[600px] items-center overflow-hidden bg-ink text-white lg:min-h-[720px]">
  <?php pps_image('home-hero', 'Commercial building painted by Pro Painting Services', 'absolute inset-0 -z-10 h-full w-full object-cover', true); ?>
  <div class="absolute inset-0 -z-10 bg-gradient-to-r from-ink/85 via-ink/55 to-ink/10" aria-hidden="true"></div>
  <div class="pps-container py-24">
    <div class="max-w-2xl">
      <p class="pps-eyebrow text-white/90">Commercial &bull; Multifamily &bull; Hospitality</p>
      <h1 class="font-display text-5xl leading-[1] font-bold uppercase sm:text-6xl lg:text-7xl">Professional Painting for Properties That Demand More.</h1>
      <p class="mt-6 max-w-xl text-lg text-white/90">Pro Painting Services works with commercial properties, apartment communities and hospitality facilities across New Mexico. Tell us about your property and the painting work you need.</p>
      <div class="mt-8 flex flex-wrap gap-4">
        <?php pps_button('Request a Quote', '#home-quote', 'primary', 'data-track="service_quote_click"'); ?>
        <?php pps_call_button('light'); ?>
      </div>
    </div>
  </div>
</section>

<!-- 02 | Property categories -->
<section class="pps-section">
  <div class="pps-container">
    <?php pps_section_heading('Painting Solutions for Managed Properties.'); ?>
    <div class="mt-12 grid gap-6 md:grid-cols-3">
      <?php
      $categories = array(
        array('Commercial Properties', 'Exterior and specialty painting for business facilities.', 'category-commercial'),
        array('Multifamily Communities', 'Exterior buildings and interior unit painting.', 'category-multifamily'),
        array('Hospitality Properties', 'Interior and exterior painting for hotels.', 'category-hospitality'),
      );
      foreach ($categories as [$title, $text, $image]) : ?>
        <div class="group relative isolate flex min-h-[420px] items-end overflow-hidden rounded-lg">
          <?php pps_image($image, $title, 'absolute inset-0 -z-10 h-full w-full object-cover transition duration-500 group-hover:scale-105'); ?>
          <div class="absolute inset-0 -z-10 bg-gradient-to-t from-ink/90 via-ink/30 to-transparent" aria-hidden="true"></div>
          <div class="p-6 text-white">
            <h3 class="font-display text-3xl font-bold uppercase"><?php echo esc_html($title); ?></h3>
            <p class="mt-1 text-white/85"><?php echo esc_html($text); ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="mt-10">
      <?php pps_button('Explore Services', home_url('/services/'), 'outline'); ?>
    </div>
  </div>
</section>

<!-- 03 | Core service sampler -->
<section class="pps-section bg-steel-50">
  <div class="pps-container">
    <?php pps_section_heading('Find the Right Painting Service.'); ?>
    <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
      <?php
      $services = pps_services();
      foreach (array('commercial-exterior-painting', 'multifamily-exterior-painting', 'interior-unit-painting', 'hotel-painting') as $slug) {
        pps_card($services[$slug]['name'], $services[$slug]['teaser'], pps_service_url($slug), "service-{$slug}");
      }
      pps_card('Specialty Surface Painting', 'Painting for pool areas, parking areas, metal elements and existing signage.', home_url('/services/#specialty'), 'service-specialty');
      pps_card('Office Renovations', $services['office-renovations']['teaser'], pps_service_url('office-renovations'), 'service-office-renovations');
      ?>
    </div>
    <div class="mt-10">
      <?php pps_button('View All Services', home_url('/services/'), 'secondary'); ?>
    </div>
  </div>
</section>

<!-- 04 | Image-led property-first statement -->
<section class="pps-section">
  <div class="pps-container grid items-center gap-10 lg:grid-cols-5 lg:gap-16">
    <div class="lg:col-span-3">
      <?php pps_image('home-crew', 'Pro Painting Services crew painting a property', 'aspect-[4/3] w-full rounded-lg object-cover'); ?>
    </div>
    <div class="lg:col-span-2">
      <?php pps_section_heading('Different Properties. Different Painting Needs.', '', 'A commercial storefront, an apartment building and a hotel each bring different surfaces, access needs and project priorities. Share your property details so we can discuss the right scope.'); ?>
      <div class="mt-8">
        <?php pps_button('Request a Quote', '#home-quote', 'primary', 'data-track="service_quote_click"'); ?>
      </div>
    </div>
  </div>
</section>

<!-- 05 | Projects -->
<section class="pps-section bg-ink text-white">
  <div class="pps-container">
    <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
      <div class="max-w-3xl">
        <p class="pps-eyebrow">Selected Work</p>
        <h2 class="pps-heading">Work You Can See.</h2>
        <p class="mt-4 text-lg text-steel-300">Explore photographs from commercial properties, apartment communities and other completed projects.</p>
      </div>
      <?php pps_button('See Our Projects', home_url('/projects/'), 'light'); ?>
    </div>
    <?php $projects = pps_get_projects(array('limit' => 3)); ?>
    <?php if ($projects) : ?>
      <div class="mt-12 grid gap-6 md:grid-cols-3">
        <?php foreach ($projects as $project) {
          pps_project_card($project);
        } ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<!-- 06 | Google reviews (only with approved, verified reviews) -->
<?php $reviews = pps_reviews(); ?>
<?php if ($reviews) : ?>
  <section class="pps-section bg-steel-50">
    <div class="pps-container">
      <?php pps_section_heading('What Property Clients Are Saying.', 'Client Feedback'); ?>
      <div class="mt-12 grid gap-6 md:grid-cols-3">
        <?php foreach ($reviews as $review) : ?>
          <figure class="flex flex-col rounded-lg bg-white p-8 shadow-sm ring-1 ring-steel-200">
            <?php if (!empty($review['rating'])) : ?>
              <p class="text-brand" aria-label="<?php echo esc_attr($review['rating']); ?> out of 5 stars"><?php echo str_repeat('★', (int) $review['rating']); ?></p>
            <?php endif; ?>
            <blockquote class="mt-3 flex-1 text-lg">&ldquo;<?php echo esc_html($review['quote']); ?>&rdquo;</blockquote>
            <figcaption class="mt-4 font-semibold"><?php echo esc_html($review['author']); ?> <span class="font-normal text-steel-500">· Google</span></figcaption>
          </figure>
        <?php endforeach; ?>
      </div>
      <?php if ($biz['reviews_url']) : ?>
        <div class="mt-10"><?php pps_button('Read Our Google Reviews', $biz['reviews_url'], 'outline', 'target="_blank" rel="noopener"'); ?></div>
      <?php endif; ?>
    </div>
  </section>
<?php endif; ?>

<!-- 07 | Areas served -->
<section class="pps-section">
  <div class="pps-container grid items-center gap-10 lg:grid-cols-2 lg:gap-16">
    <div>
      <?php pps_section_heading('Serving Properties Across New Mexico.', 'Where We Work', 'Based in Albuquerque, Pro Painting Services works on projects throughout New Mexico. Have a property in another state? Contact us to discuss potential availability.'); ?>
      <ul class="mt-8 flex flex-wrap gap-3">
        <?php foreach (array('Albuquerque', 'Rio Rancho', 'New Mexico Statewide') as $area) : ?>
          <li class="rounded-full bg-steel-100 px-5 py-2 font-semibold"><?php echo esc_html($area); ?></li>
        <?php endforeach; ?>
      </ul>
      <?php if ($biz['gbp_url']) : ?>
        <div class="mt-8"><?php pps_button('View Our Location', $biz['gbp_url'], 'outline', 'target="_blank" rel="noopener"'); ?></div>
      <?php endif; ?>
    </div>
    <?php pps_image('home-new-mexico', 'Map of New Mexico service area', 'aspect-[4/3] w-full rounded-lg object-cover'); ?>
  </div>
</section>

<!-- 08 | FAQ -->
<section class="pps-section bg-steel-50">
  <div class="pps-container grid gap-10 lg:grid-cols-3">
    <div>
      <?php pps_section_heading('Frequently Asked Questions.'); ?>
    </div>
    <div class="lg:col-span-2">
      <?php pps_faq(array(
        array('q' => 'What types of properties do you paint?', 'a' => 'Our services include work for commercial facilities, apartment communities and hotel properties.'),
        array('q' => 'Do you work outside Albuquerque?', 'a' => 'We serve projects throughout New Mexico. Contact our team to discuss projects in other states.'),
        array('q' => 'Can I request a quote for several buildings or units?', 'a' => 'Yes. Include the number of buildings or units, project location and painting needs when contacting us.'),
        array('q' => 'How do I get started?', 'a' => 'Call our team or complete the project request form with a few details about your property.'),
      )); ?>
    </div>
  </div>
</section>

<!-- 09 | Final CTA + quote form -->
<section id="home-quote" class="pps-section">
  <div class="pps-container grid gap-12 lg:grid-cols-2 lg:gap-16">
    <div>
      <?php pps_section_heading('Let’s Discuss Your Painting Project.', 'Start Your Project', 'Tell us what property you manage, where it is located and what needs to be painted.'); ?>
      <?php pps_image('home-quote', 'Completed painting project by Pro Painting Services', 'mt-8 aspect-[4/3] w-full rounded-lg object-cover'); ?>
      <div class="mt-8"><?php pps_contact_panel(); ?></div>
    </div>
    <div>
      <?php pps_quote_form('full'); ?>
    </div>
  </div>
</section>

<?php get_footer();
