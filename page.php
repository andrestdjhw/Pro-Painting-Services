<?php get_header(); ?>

<?php while (have_posts()) : the_post(); ?>
  <?php pps_page_hero(get_the_title()); ?>
  <section class="pps-section">
    <div class="pps-container prose prose-lg max-w-3xl prose-a:text-brand">
      <?php the_content(); ?>
    </div>
  </section>
<?php endwhile; ?>

<?php get_footer();
