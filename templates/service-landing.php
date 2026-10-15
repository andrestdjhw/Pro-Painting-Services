<?php
/**
 * Shared template for the 9 service landing pages (/services/{slug}/).
 * Copy comes from pps_services() in inc/config.php. Images go in
 * assets/images/ as service-{slug}.*, service-{slug}-fit.*, service-{slug}-process.*
 */
get_header();
$service = pps_current_service();
$slug    = $service['slug'];
$biz     = pps_business();
?>

<!-- 01 | Service hero -->
<section data-hero class="relative isolate flex min-h-[520px] items-center overflow-hidden bg-ink text-white">
  <?php pps_image("service-{$slug}", $service['name'], 'absolute inset-0 -z-10 h-full w-full object-cover', true); ?>
  <div class="absolute inset-0 -z-10 bg-linear-to-r from-ink/85 via-ink/60 to-ink/20" aria-hidden="true"></div>
  <div class="pps-container py-20">
    <nav aria-label="Breadcrumb" class="mb-6 text-sm text-white/75">
      <a href="<?php echo esc_url(home_url('/')); ?>" class="hover:text-white">Home</a>
      <span aria-hidden="true"> / </span>
      <a href="<?php echo esc_url(home_url('/services/')); ?>" class="hover:text-white">Services</a>
      <span aria-hidden="true"> / </span>
      <span aria-current="page"><?php echo esc_html($service['nav_name']); ?></span>
    </nav>
    <div class="pps-hero-in max-w-2xl">
      <p class="pps-eyebrow text-white/90"><?php echo esc_html($service['eyebrow']); ?></p>
      <h1 class="pps-h1"><?php echo esc_html($service['h1']); ?></h1>
      <p class="mt-6 text-lg text-white/90"><?php echo esc_html($service['intro']); ?></p>
      <div class="mt-8 flex flex-wrap gap-4">
        <?php pps_button('Request a Quote', home_url("/contact/?service={$slug}"), 'primary', 'data-track="service_quote_click"'); ?>
        <?php pps_call_button(); ?>
      </div>
    </div>
  </div>
</section>

<!-- 02 | Where this service fits -->
<section class="pps-section">
  <div class="pps-container grid items-center gap-10 lg:grid-cols-2 lg:gap-16">
    <?php pps_image(array("service-{$slug}-fit", "category-{$service['group']}"), "{$service['name']} property", 'aspect-[4/3] w-full rounded-sm object-cover'); ?>
    <div>
      <?php pps_section_heading('Painting for the Properties You Manage.', '', $service['fit'], array('rule' => true)); ?>
      <ul class="mt-8 flex flex-wrap gap-3">
        <?php foreach ($service['tags'] as $tag) : ?>
          <li class="rounded-full bg-steel-100 px-5 py-2 text-sm font-semibold"><?php echo esc_html($tag); ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</section>

<!-- 03 | Scope and project details -->
<section class="pps-section bg-steel-50">
  <div class="pps-container">
    <?php pps_section_heading('What to Include in Your Project Request.'); ?>
    <div class="mt-12 grid gap-6 md:grid-cols-3">
      <?php foreach ($service['scope'] as $i => $card) : ?>
        <div class="pps-card rounded-sm bg-white p-8 shadow-sm ring-1 ring-steel-200">
          <span class="font-display text-lg font-bold text-brand"><?php printf('%02d', $i + 1); ?></span>
          <h3 class="mt-2 pps-title"><?php echo esc_html($card['title']); ?></h3>
          <p class="mt-2 text-steel-600"><?php echo esc_html($card['text']); ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- 04 | Planning and next steps -->
<?php pps_process_band($service['planning'], array("service-{$slug}-process", 'painting-process')); ?>

<!-- 05 | Related work and proof -->
<?php
$projects = pps_get_projects(array('service' => $slug, 'limit' => 3));
if (!$projects) {
  $projects = pps_get_projects(array('category' => $service['group'], 'limit' => 3));
}
?>
<section class="pps-section">
  <div class="pps-container">
    <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
      <?php pps_section_heading('See Related Projects.', '', 'Explore examples of work across commercial and managed properties.'); ?>
      <?php pps_button('View Projects', home_url('/projects/'), 'outline'); ?>
    </div>
    <?php if ($projects) : ?>
      <div class="mt-12 grid gap-6 md:grid-cols-3">
        <?php foreach ($projects as $project) {
          pps_project_card($project);
        } ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<!-- 06 | Service questions -->
<section class="pps-section bg-steel-50">
  <div class="pps-container grid gap-10 lg:grid-cols-3">
    <?php pps_section_heading('Service Questions.'); ?>
    <div class="lg:col-span-2"><?php pps_faq($service['faq']); ?></div>
  </div>
</section>

<!-- 07 | Final conversion -->
<section id="quote" class="pps-section">
  <div class="pps-container grid gap-12 lg:grid-cols-2 lg:gap-16">
    <div>
      <?php pps_section_heading($service['final_cta']['heading'], '', $service['final_cta']['text']); ?>
      <div class="mt-8"><?php pps_contact_panel(); ?></div>
    </div>
    <div>
      <?php pps_quote_form('mini', $slug, 'Send a Project Request'); ?>
    </div>
  </div>
</section>

<?php get_footer();
