<?php
/**
 * "Project" post type for the portfolio.
 *
 * Each project: title, featured image, a one-sentence verified detail (excerpt),
 * category (Commercial / Multifamily / Hospitality / Specialty), city/state,
 * specific painting work, and an optional photo gallery (3–6 images).
 */

function pps_register_projects() {
  register_post_type('pps_project', array(
    'labels'        => array(
      'name'          => 'Projects',
      'singular_name' => 'Project',
      'add_new_item'  => 'Add New Project',
      'edit_item'     => 'Edit Project',
    ),
    // Shown only through the Projects page; no single URLs.
    'public'        => false,
    'show_ui'       => true,
    'menu_icon'     => 'dashicons-format-gallery',
    'supports'      => array('title', 'thumbnail', 'excerpt'),
    'show_in_rest'  => true,
  ));

  register_taxonomy('pps_project_category', 'pps_project', array(
    'labels'            => array('name' => 'Project Categories', 'singular_name' => 'Project Category'),
    'public'            => false,
    'show_ui'           => true,
    'show_admin_column' => true,
    'hierarchical'      => true,
    'show_in_rest'      => true,
  ));

  foreach (array('pps_location', 'pps_scope', 'pps_service', 'pps_gallery') as $key) {
    register_post_meta('pps_project', $key, array('type' => 'string', 'single' => true, 'show_in_rest' => true));
  }
}
add_action('init', 'pps_register_projects');

function pps_seed_project_categories() {
  foreach (pps_project_categories() as $slug => $name) {
    if (!term_exists($slug, 'pps_project_category')) {
      wp_insert_term($name, 'pps_project_category', array('slug' => $slug));
    }
  }
}
add_action('admin_init', 'pps_seed_project_categories');

/* ---------- Admin meta box ---------- */

function pps_project_meta_box() {
  add_meta_box('pps_project_details', 'Project Details (verified data only)', 'pps_project_meta_box_html', 'pps_project', 'normal', 'high');
}
add_action('add_meta_boxes', 'pps_project_meta_box');

function pps_project_meta_box_html($post) {
  wp_nonce_field('pps_project_meta', 'pps_project_nonce');
  wp_enqueue_media();
  $location = get_post_meta($post->ID, 'pps_location', true);
  $scope    = get_post_meta($post->ID, 'pps_scope', true);
  $service  = get_post_meta($post->ID, 'pps_service', true);
  $gallery  = get_post_meta($post->ID, 'pps_gallery', true);
  ?>
  <p><label><strong>City, State</strong><br><input type="text" name="pps_location" value="<?php echo esc_attr($location); ?>" class="widefat" placeholder="Albuquerque, NM"></label></p>
  <p><label><strong>Specific painting work</strong><br><input type="text" name="pps_scope" value="<?php echo esc_attr($scope); ?>" class="widefat" placeholder="Exterior repaint, stucco and trim"></label></p>
  <p><label><strong>Related service</strong><br>
    <select name="pps_service">
      <option value="">—</option>
      <?php foreach (pps_services() as $slug => $s) : ?>
        <option value="<?php echo esc_attr($slug); ?>" <?php selected($service, $slug); ?>><?php echo esc_html($s['name']); ?></option>
      <?php endforeach; ?>
    </select>
  </label></p>
  <p><strong>Gallery (3–6 photos)</strong><br>
    <input type="hidden" name="pps_gallery" id="pps_gallery" value="<?php echo esc_attr($gallery); ?>">
    <button type="button" class="button" id="pps_gallery_btn">Select photos</button>
    <span id="pps_gallery_count"><?php echo $gallery ? count(explode(',', $gallery)) . ' selected' : ''; ?></span>
  </p>
  <p class="description">Use the Excerpt for the one-sentence project detail. Never invent client names, timelines, surfaces or results; show brands only with authorization.</p>
  <script>
    document.getElementById('pps_gallery_btn').addEventListener('click', function () {
      var input = document.getElementById('pps_gallery');
      var frame = wp.media({ title: 'Project gallery', multiple: 'add', library: { type: 'image' } });
      frame.on('open', function () {
        var selection = frame.state().get('selection');
        input.value.split(',').filter(Boolean).forEach(function (id) { selection.add(wp.media.attachment(id)); });
      });
      frame.on('select', function () {
        var ids = frame.state().get('selection').map(function (a) { return a.id; });
        input.value = ids.join(',');
        document.getElementById('pps_gallery_count').textContent = ids.length + ' selected';
      });
      frame.open();
    });
  </script>
  <?php
}

function pps_save_project_meta($post_id) {
  if (!isset($_POST['pps_project_nonce']) || !wp_verify_nonce($_POST['pps_project_nonce'], 'pps_project_meta')) {
    return;
  }
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE || !current_user_can('edit_post', $post_id)) {
    return;
  }
  update_post_meta($post_id, 'pps_location', sanitize_text_field($_POST['pps_location'] ?? ''));
  update_post_meta($post_id, 'pps_scope', sanitize_text_field($_POST['pps_scope'] ?? ''));
  $service = sanitize_key($_POST['pps_service'] ?? '');
  update_post_meta($post_id, 'pps_service', pps_service($service) ? $service : '');
  $ids = array_filter(array_map('absint', explode(',', $_POST['pps_gallery'] ?? '')));
  update_post_meta($post_id, 'pps_gallery', implode(',', $ids));
}
add_action('save_post_pps_project', 'pps_save_project_meta');

/* ---------- Front-end helpers ---------- */

/**
 * @param array $args 'category' (slug), 'service' (slug), 'limit'
 */
function pps_get_projects($args = array()) {
  $query = array(
    'post_type'      => 'pps_project',
    'posts_per_page' => $args['limit'] ?? 12,
    'no_found_rows'  => true,
  );
  if (!empty($args['category'])) {
    $query['tax_query'] = array(array('taxonomy' => 'pps_project_category', 'field' => 'slug', 'terms' => $args['category']));
  }
  if (!empty($args['service'])) {
    $query['meta_key']   = 'pps_service';
    $query['meta_value'] = $args['service'];
  }
  return get_posts($query);
}

/**
 * Project card: photo, [Title], [City, State] • [Property type] • [Painting work], optional 1-sentence detail.
 * $full adds the gallery carousel and excerpt (Projects page).
 */
function pps_project_card($project, $full = false) {
  $terms    = get_the_terms($project, 'pps_project_category');
  $category = $terms && !is_wp_error($terms) ? $terms[0]->name : '';
  $meta     = array_filter(array(get_post_meta($project->ID, 'pps_location', true), $category, get_post_meta($project->ID, 'pps_scope', true)));
  $gallery  = $full ? array_filter(explode(',', get_post_meta($project->ID, 'pps_gallery', true))) : array();
  ?>
  <article class="pps-card flex flex-col overflow-hidden rounded-sm bg-white shadow-sm ring-1 ring-steel-200" data-project="<?php echo esc_attr($project->post_name); ?>">
    <?php if ($gallery) :
      $title = get_the_title($project);
      $total = count($gallery);
      ?>
      <div class="pps-gallery relative" data-gallery>
        <div class="pps-gallery__track flex snap-x snap-mandatory overflow-x-auto" tabindex="0" role="region" aria-roledescription="carousel" aria-label="<?php echo esc_attr("{$title} photos"); ?>">
          <?php foreach (array_values($gallery) as $i => $id) : ?>
            <?php echo wp_get_attachment_image($id, 'pps-card', false, array(
              'class'   => 'aspect-[4/3] w-full flex-none snap-center object-cover',
              'loading' => 'lazy',
              'alt'     => sprintf('%s, photo %d of %d', $title, $i + 1, $total),
            )); ?>
          <?php endforeach; ?>
        </div>
        <?php if ($total > 1) : ?>
          <button type="button" class="pps-gallery__btn left-3" data-gallery-prev aria-label="Previous photo"><?php echo pps_icon('arrow', 'h-4 w-4 rotate-180'); ?></button>
          <button type="button" class="pps-gallery__btn right-3" data-gallery-next aria-label="Next photo"><?php echo pps_icon('arrow', 'h-4 w-4'); ?></button>
          <span class="pps-gallery__count" aria-live="polite"><span data-gallery-current>1</span> / <?php echo (int) $total; ?></span>
        <?php endif; ?>
      </div>
    <?php elseif (has_post_thumbnail($project)) : ?>
      <?php echo get_the_post_thumbnail($project, 'pps-card', array('class' => 'aspect-[4/3] w-full object-cover', 'loading' => 'lazy', 'alt' => get_the_title($project))); ?>
    <?php else : ?>
      <div class="pps-placeholder aspect-[4/3] w-full"><span>Add a featured image</span></div>
    <?php endif; ?>
    <div class="p-5">
      <h3 class="pps-title text-ink"><?php echo esc_html(get_the_title($project)); ?></h3>
      <?php if ($meta) : ?>
        <p class="mt-1 text-sm text-steel-500"><?php echo esc_html(implode(' • ', $meta)); ?></p>
      <?php endif; ?>
      <?php if ($full && has_excerpt($project)) : ?>
        <p class="mt-3 text-steel-600"><?php echo esc_html(get_the_excerpt($project)); ?></p>
      <?php endif; ?>
    </div>
  </article>
  <?php
}
