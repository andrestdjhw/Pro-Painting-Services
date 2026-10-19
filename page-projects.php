<?php
get_header();
$categories = pps_project_categories();
$active     = sanitize_key($_GET['property'] ?? '');
$active     = isset($categories[$active]) ? $active : '';
$projects   = pps_get_projects(array('category' => $active, 'limit' => 24));
?>

<!-- 01 | Portfolio -->
<?php pps_page_hero('Real Projects. Real Property Work.', 'Browse Pro Painting Services work by property type and painting scope.', 'projects-hero'); ?>

<section class="pps-section">
  <div class="pps-container">
    <nav aria-label="Filter projects" class="flex flex-wrap gap-3">
      <?php
      $filters = array('' => 'All') + $categories;
      foreach ($filters as $slug => $label) :
        $url     = $slug ? add_query_arg('property', $slug, get_permalink()) : get_permalink();
        $current = $slug === $active;
        ?>
        <a href="<?php echo esc_url($url); ?>" <?php echo $current ? 'aria-current="page"' : ''; ?> class="rounded-full px-5 py-2 font-semibold transition <?php echo $current ? 'bg-ink text-white' : 'bg-steel-100 hover:bg-steel-200'; ?>"><?php echo esc_html($label); ?></a>
      <?php endforeach; ?>
    </nav>

    <?php if ($projects) : ?>
      <div class="mt-12 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        <?php foreach ($projects as $project) {
          pps_project_card($project, true);
        } ?>
      </div>
    <?php else : ?>
      <p class="mt-12 text-lg text-steel-600">Project photos for this category are coming soon. Contact us to discuss work similar to yours.</p>
    <?php endif; ?>
  </div>
</section>

<!-- 02 | CTA -->
<section class="pps-section bg-sand">
  <div class="pps-container flex flex-col gap-8 lg:flex-row lg:items-center lg:justify-between">
    <?php pps_section_heading('Have a Similar Property?', '', 'Tell us about your property, its location and the painting work you need.'); ?>
    <div class="flex flex-wrap gap-4">
      <?php pps_button('Discuss a Similar Project', home_url('/contact/'), 'primary', 'data-track="service_quote_click"'); ?>
      <?php pps_call_button('link-dark'); ?>
    </div>
  </div>
</section>

<?php get_footer();
