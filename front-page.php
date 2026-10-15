<?php
get_header();
$biz      = pps_business();
$services = pps_services();
?>

<!-- 01 | Hero + property-types ticker: together they fill the screen below the header -->
<div class="pps-hero-screen">
<section data-hero class="relative isolate flex flex-1 items-center overflow-hidden bg-ink text-white">
  <?php pps_image('home-hero', 'Two-story commercial building exterior in New Mexico', 'absolute inset-0 -z-10 h-full w-full object-cover', true); ?>
  <!-- Darkening: an even base tint (keeps the right side calm behind the form) + a stronger left gradient for the headline -->
  <div class="absolute inset-0 -z-10 bg-ink/55" aria-hidden="true"></div>
  <div class="absolute inset-0 -z-10 bg-linear-to-r from-ink/85 via-ink/45 to-ink/20" aria-hidden="true"></div>
  <div class="pps-container grid items-center gap-10 py-16 lg:grid-cols-[minmax(0,1fr)_minmax(0,28rem)] lg:py-10 xl:grid-cols-[minmax(0,1fr)_minmax(0,31rem)] xl:gap-16">
    <div class="pps-hero-in max-w-2xl">
      <p class="pps-eyebrow text-white">Commercial &bull; Multifamily &bull; Hospitality</p>
      <h1 class="pps-h1">Professional Painting for Properties That <span class="text-brand">Demand More.</span></h1>
      <p class="mt-6 max-w-xl text-lg text-white/85">Pro Painting Services works with commercial properties, apartment communities and hospitality facilities across New Mexico. Tell us about your property and the painting work you need.</p>
      <div class="mt-8 flex flex-wrap items-center gap-x-8 gap-y-4">
        <?php pps_button('Request a Quote', '#home-quote', 'primary', 'data-track="service_quote_click"'); ?>
        <?php pps_call_button(); ?>
      </div>
    </div>
    <!-- Quick quote form (desktop); mobile visitors use the Request a Quote button / bottom form -->
    <div class="pps-hero-form hidden lg:block">
      <?php pps_quote_form('hero', '', 'Request a Project Quote'); ?>
    </div>
  </div>
  <!-- Photo caption: only shown with a real client photo; update the text to match it -->
  <?php if (pps_has_real_image('home-hero')) : ?>
  <div class="absolute right-4 bottom-6 hidden items-center gap-3 rounded-sm bg-ink/50 px-4 py-3 backdrop-blur-sm sm:flex lg:hidden">
    <?php echo pps_icon('pin', 'h-6 w-6 text-white'); ?>
    <div class="text-xs leading-tight tracking-[0.2em] uppercase">
      <p class="font-semibold">New Mexico</p>
      <p class="text-white/70">Commercial Project</p>
    </div>
  </div>
  <?php endif; ?>
</section>

<!-- Property types ticker: infinite left-to-right loop. Two identical halves slide by 50%;
     each half repeats the items so even very wide screens never show a gap. -->
<?php $ticker = array('Commercial Properties', 'Multifamily Communities', 'Hospitality Properties', 'New Mexico'); ?>
<div class="pps-ticker flex-none border-b border-steel-200 bg-steel-50">
  <div class="pps-ticker__track">
    <?php for ($set = 0; $set < 6; $set++) : ?>
      <ul class="pps-ticker__set" <?php echo $set ? 'aria-hidden="true"' : 'aria-label="Property types we serve"'; ?>>
        <?php foreach ($ticker as $item) : ?>
          <li><?php echo esc_html($item); ?></li>
          <li aria-hidden="true" class="pps-ticker__dot"></li>
        <?php endforeach; ?>
      </ul>
    <?php endfor; ?>
  </div>
</div>
</div>

<!-- 02 | Property categories -->
<section class="py-12 md:py-16">
  <div class="pps-container">
    <h2 class="sr-only">Painting Solutions for Managed Properties.</h2>
    <div class="grid gap-5 md:grid-cols-3">
      <?php
      $categories = array(
        array('Commercial', 'Exterior and specialty painting for business facilities.', 'category-commercial', pps_service_url('commercial-exterior-painting')),
        array('Multifamily', 'Exterior buildings and interior unit painting.', 'category-multifamily', pps_service_url('multifamily-exterior-painting')),
        array('Hospitality', 'Interior and exterior painting for hotels.', 'category-hospitality', pps_service_url('hotel-painting')),
      );
      foreach ($categories as [$title, $text, $image, $url]) : ?>
        <a href="<?php echo esc_url($url); ?>" class="pps-card group relative isolate flex aspect-[4/3] items-end overflow-hidden rounded-sm md:aspect-[5/3]">
          <?php pps_image($image, "{$title} property exterior", 'absolute inset-0 -z-10 h-full w-full object-cover transition duration-500 group-hover:scale-105'); ?>
          <div class="absolute inset-0 -z-10 bg-linear-to-t from-ink/90 via-ink/25 to-transparent" aria-hidden="true"></div>
          <div class="p-6 text-white">
            <h3 class="font-display text-2xl font-extrabold tracking-wide uppercase"><?php echo esc_html($title); ?></h3>
            <p class="sr-only"><?php echo esc_html($text); ?></p>
            <span class="mt-1 inline-flex items-center gap-2 text-sm font-semibold">Explore <?php echo pps_icon('arrow', 'pps-arrow text-brand'); ?></span>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- 06 | Google reviews: Trustindex widget (plugin) with manual approved reviews as fallback -->
<?php
$reviews_widget = shortcode_exists('trustindex') ? do_shortcode($biz['reviews_shortcode']) : '';
$reviews        = $reviews_widget ? array() : pps_reviews();
?>
<?php if ($reviews_widget || $reviews) : ?>
  <section class="pps-section bg-steel-50">
    <div class="pps-container">
      <div class="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
        <?php pps_section_heading('What Property Clients Are Saying.', 'Client Feedback', '', array('rule' => true)); ?>
        <div class="flex flex-wrap items-center gap-x-8 gap-y-4">
          <?php pps_button('Leave a Review', $biz['write_review_url'], 'primary', 'target="_blank" rel="noopener" data-track="leave_review_click"'); ?>
          <a href="<?php echo esc_url($biz['reviews_url']); ?>" target="_blank" rel="noopener" class="group inline-flex items-center gap-2 font-semibold hover:text-brand">Read Our Google Reviews <?php echo pps_icon('arrow', 'pps-arrow text-brand'); ?></a>
        </div>
      </div>
      <?php if ($reviews_widget) : ?>
        <div class="pps-reviews-widget mt-12"><?php echo $reviews_widget; // Plugin output (already escaped by the plugin). ?></div>
      <?php else : ?>
        <div class="mt-12 grid gap-5 md:grid-cols-3">
        <?php foreach ($reviews as $review) : ?>
          <figure class="pps-card flex flex-col rounded-sm bg-white p-6 shadow-sm ring-1 ring-steel-200">
            <svg class="h-6 w-6" viewBox="0 0 48 48" aria-label="Google review"><path fill="#4285F4" d="M45 24.5c0-1.6-.1-3.1-.4-4.5H24v8.5h11.8a10 10 0 0 1-4.4 6.6v5.5h7.1C42.6 36.8 45 31.2 45 24.5z"/><path fill="#34A853" d="M24 46c5.9 0 10.9-2 14.5-5.4l-7.1-5.5c-2 1.3-4.5 2.1-7.4 2.1-5.7 0-10.5-3.8-12.2-9H4.5v5.7A22 22 0 0 0 24 46z"/><path fill="#FBBC05" d="M11.8 28.2a13 13 0 0 1 0-8.4v-5.7H4.5a22 22 0 0 0 0 19.8z"/><path fill="#EA4335" d="M24 10.8c3.2 0 6.1 1.1 8.4 3.3l6.3-6.3A22 22 0 0 0 4.5 14.1l7.3 5.7c1.7-5.2 6.5-9 12.2-9z"/></svg>
            <blockquote class="mt-4 flex-1 text-steel-700">&ldquo;<?php echo esc_html($review['quote']); ?>&rdquo;</blockquote>
            <span class="mt-5 block h-0.5 w-8 bg-brand" aria-hidden="true"></span>
            <figcaption class="mt-4">
              <p class="font-semibold"><?php echo esc_html($review['author']); ?></p>
              <?php if (!empty($review['role'])) : ?>
                <p class="text-sm text-steel-500"><?php echo esc_html($review['role']); ?></p>
              <?php endif; ?>
              <?php if (!empty($review['rating'])) : ?>
                <p class="mt-2 flex text-amber-400" aria-label="<?php echo esc_attr($review['rating']); ?> out of 5 stars">
                  <?php echo str_repeat(pps_icon('star', 'h-4 w-4'), (int) $review['rating']); ?>
                </p>
              <?php endif; ?>
            </figcaption>
          </figure>
        <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </section>
<?php else : ?>
  <div class="pps-container py-6"><?php pps_admin_hint('Reviews section is hidden: activate the Trustindex "Widgets for Google Reviews" plugin, or add approved reviews in inc/config.php → pps_reviews().'); ?></div>
<?php endif; ?>

<!-- Process band (same as service landings): how a project request moves forward -->
<?php pps_process_band('Start with the property address, number of buildings and images of the existing surfaces. We can then discuss the scope and next steps.', array('home-process', 'painting-process')); ?>

<!-- 03 | Core service sampler -->
<?php
$sampler = array(
  array('Commercial Exterior', pps_service_url('commercial-exterior-painting'), 'service-commercial-exterior-painting', 'Commercial Exterior Painting'),
  array('Multifamily Exterior', pps_service_url('multifamily-exterior-painting'), 'service-multifamily-exterior-painting', 'Multifamily Exterior Painting'),
  array('Interior Units', pps_service_url('interior-unit-painting'), 'service-interior-unit-painting', 'Interior Unit Painting'),
  array('Hotel Painting', pps_service_url('hotel-painting'), 'service-hotel-painting', 'Hotel Painting'),
  array('Specialty Surfaces', home_url('/services/#specialty'), 'service-specialty', 'Specialty Surface Painting'),
  array('Office Renovations', pps_service_url('office-renovations'), 'service-office-renovations', 'Office Renovations'),
);
?>
<section class="pps-section">
  <div class="pps-container grid items-center gap-12 lg:grid-cols-2">
    <div>
      <?php pps_section_heading('Find the Right Painting Service.', '', '', array('rule' => true)); ?>
      <ul class="mt-8 grid gap-x-8 gap-y-4 sm:grid-cols-2">
        <?php foreach ($sampler as [$short, $url, $image, $name]) : ?>
          <li>
            <a href="<?php echo esc_url($url); ?>" class="group inline-flex items-center gap-3 font-medium hover:text-brand">
              <?php echo pps_icon('arrow', 'pps-arrow text-brand'); ?><?php echo esc_html($name); ?>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
      <div class="mt-10">
        <?php pps_button('View All Services', home_url('/services/')); ?>
      </div>
    </div>
    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
      <?php foreach ($sampler as [$short, $url, $image, $name]) : ?>
        <a href="<?php echo esc_url($url); ?>" class="pps-card group relative isolate block overflow-hidden rounded-sm" aria-label="<?php echo esc_attr($name); ?>">
          <?php pps_image($image, $name, 'aspect-[4/3] w-full object-cover transition duration-500 group-hover:scale-105'); ?>
          <div class="absolute inset-0 bg-linear-to-t from-ink/85 via-transparent to-transparent" aria-hidden="true"></div>
          <span class="absolute inset-x-0 bottom-0 p-3 text-sm font-semibold text-white"><?php echo esc_html($short); ?></span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Partners / affiliations: monochrome logos on a dark band -->
<section class="bg-ink py-12 md:py-16" aria-labelledby="pps-partners-title">
  <div class="pps-container">
    <p id="pps-partners-title" class="pps-eyebrow text-center text-steel-300">Partners &amp; Affiliations</p>
    <ul class="mt-8 grid grid-cols-2 items-center justify-items-center gap-x-8 gap-y-10 md:grid-cols-4">
      <?php foreach (pps_partners() as $key => $name) : ?>
        <li>
          <img src="<?php echo esc_url(pps_asset_url("partners/{$key}")); ?>" alt="<?php echo esc_attr($name); ?>" loading="lazy" decoding="async" class="pps-partner-logo">
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>

<!-- 04 | Image-led property-first statement (60 / 40) -->
<section class="grid bg-white lg:grid-cols-5">
  <div class="overflow-hidden lg:col-span-3" data-reveal="wipe">
    <?php pps_image('home-crew', 'Painting crew on a boom lift at a mixed-use building, with a Pro Painting Services truck in front', 'aspect-[16/10] w-full object-cover', false, '(min-width: 1024px) 60vw, 100vw'); ?>
  </div>
  <div class="flex items-center lg:col-span-2" data-reveal="right">
    <div class="px-4 py-14 sm:px-6 lg:px-14">
      <?php pps_section_heading('Different Properties. Different Painting Needs.', '', 'A commercial storefront, an apartment building and a hotel each bring different surfaces, access needs and project priorities. Share your property details so we can discuss the right scope.', array('rule' => true)); ?>
    </div>
  </div>
</section>

<!-- 05 | Projects -->
<?php $projects = pps_get_projects(array('limit' => 3)); ?>
<section class="pps-section border-t border-steel-200">
  <div class="pps-container grid gap-12 <?php echo $projects ? 'lg:grid-cols-3' : ''; ?>">
    <div>
      <?php pps_section_heading('Work You Can See.', 'Selected Work', 'Explore photographs from commercial properties, apartment communities and other completed projects.'); ?>
      <div class="mt-8">
        <?php pps_button('See Our Projects', home_url('/projects/')); ?>
      </div>
    </div>
    <div class="<?php echo $projects ? 'lg:col-span-2' : ''; ?>">
      <?php if ($projects) : ?>
        <div class="grid gap-5 sm:grid-cols-3">
          <?php foreach ($projects as $project) {
            pps_project_card($project);
          } ?>
        </div>
      <?php else : ?>
        <?php pps_admin_hint('Add 3+ real projects in WP Admin → Projects to fill this area (photo, city, category and painting work).'); ?>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- 07 | Areas served -->
<section class="pps-section border-t border-steel-200">
  <div class="pps-container grid items-center gap-10 lg:grid-cols-2 lg:gap-16">
    <div class="pps-map aspect-[4/3] overflow-hidden rounded-sm shadow-sm ring-1 ring-steel-200">
      <?php pps_map(); ?>
    </div>
    <div>
      <?php pps_section_heading('Serving Properties Across New Mexico.', 'Where We Work', 'Based in Albuquerque, Pro Painting Services works on projects throughout New Mexico. Have a property in another state? Contact us to discuss potential availability.'); ?>
      <ul class="mt-8 grid gap-3 sm:grid-cols-2">
        <?php foreach (pps_service_areas() as $area) : ?>
          <li class="flex items-center gap-3 font-medium"><?php echo pps_icon('pin', 'h-4 w-4 text-brand'); ?><?php echo esc_html($area); ?></li>
        <?php endforeach; ?>
      </ul>
      <div class="mt-10 flex flex-wrap items-center gap-x-8 gap-y-4">
        <?php pps_button('Discuss Your Project', '#home-quote'); ?>
        <?php if ($biz['gbp_url']) : ?>
          <a href="<?php echo esc_url($biz['gbp_url']); ?>" target="_blank" rel="noopener" class="text-sm font-semibold underline hover:text-brand">View Our Location</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<!-- 08 | FAQ -->
<section class="pps-section bg-steel-50">
  <div class="pps-container grid gap-10 lg:grid-cols-3">
    <?php pps_section_heading('Frequently Asked Questions.', '', '', array('rule' => true)); ?>
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
<section id="home-quote" data-parallax class="relative isolate overflow-hidden bg-ink py-16 text-white md:py-24">
  <?php pps_image('home-cta', '', 'absolute inset-0 -z-10 h-full w-full object-cover opacity-25'); ?>
  <div class="absolute inset-0 -z-10 bg-linear-to-r from-ink via-ink/90 to-ink/60" aria-hidden="true"></div>
  <?php pps_watermark('-left-20 -bottom-24 w-[30rem]'); ?>
  <div class="pps-container grid gap-12 lg:grid-cols-2 lg:gap-16">
    <div>
      <?php pps_section_heading('Let’s Discuss Your Painting Project.', 'Start Your Project', 'Tell us what property you manage, where it is located and what needs to be painted.', array('dark' => true, 'rule' => true)); ?>
      <div class="mt-10"><?php pps_contact_panel(true); ?></div>
    </div>
    <div class="text-ink">
      <?php pps_quote_form('full'); ?>
    </div>
  </div>
</section>

<?php get_footer();
