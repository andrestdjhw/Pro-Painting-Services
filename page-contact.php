<?php
get_header();
$biz = pps_business();
?>

<section class="pps-section">
  <div class="pps-container grid gap-12 lg:grid-cols-5 lg:gap-16">

    <!-- 01 | Contact details -->
    <div class="lg:col-span-2">
      <div class="lg:sticky lg:top-28">
        <h1 class="pps-h1">Let’s Talk About Your Painting Project.</h1>
        <div class="mt-8 rounded-sm bg-ink p-8 text-white">
          <?php pps_contact_panel(true); ?>
          <div class="mt-6 border-t border-steel-700 pt-6">
            <p class="text-sm tracking-wider text-steel-300 uppercase">Office</p>
            <address class="mt-1 not-italic"><?php echo esc_html($biz['street']); ?><br><?php echo esc_html("{$biz['city']}, {$biz['region']} {$biz['postal']}"); ?></address>
            <p class="mt-4 text-sm text-steel-300"><?php echo esc_html($biz['service_area']); ?></p>
            <?php if ($biz['gbp_url']) : ?>
              <a href="<?php echo esc_url($biz['gbp_url']); ?>" target="_blank" rel="noopener" class="mt-4 inline-block font-semibold text-white underline hover:text-brand">View on Google Maps</a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <!-- 02 | Dedicated contact form -->
    <div class="lg:col-span-3">
      <?php pps_quote_form('full', '', 'Request a Project Quote'); ?>
    </div>

  </div>
</section>

<?php get_footer();
