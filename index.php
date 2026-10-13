<?php get_header(); ?>

<section class="pps-section">
  <div class="pps-container prose prose-lg max-w-3xl prose-a:text-brand">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
      <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
      <?php the_excerpt(); ?>
    <?php endwhile; endif; ?>
  </div>
</section>

<?php get_footer();
