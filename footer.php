    </main>

    <?php $biz = pps_business(); ?>
    <!-- React: src/components/Footer.js. The markup inside is the crawlable fallback React replaces. -->
    <div id="pps-footer" class="bg-ink text-steel-300">
      <footer class="mx-auto max-w-site px-4 py-12">
        <img src="<?php echo esc_url(pps_asset_url('brand/logo-light')); ?>" alt="<?php echo esc_attr($biz['name']); ?>" width="132" height="56" class="h-14 w-auto" loading="lazy">
        <address class="mt-2 not-italic">
          <?php echo esc_html("{$biz['street']}, {$biz['city']}, {$biz['region']} {$biz['postal']}"); ?><br>
          <a href="<?php echo esc_url($biz['phone_href']); ?>"><?php echo esc_html($biz['phone']); ?></a> ·
          <a href="mailto:<?php echo esc_attr($biz['email']); ?>"><?php echo esc_html($biz['email']); ?></a>
        </address>
        <p class="mt-4 text-xs">
          &copy; <?php echo esc_html(date('Y') . " {$biz['name']}"); ?>. All rights reserved. ·
          <a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>">Privacy Policy</a> ·
          <a href="<?php echo esc_url(home_url('/terms-and-conditions/')); ?>">Terms and Conditions</a> ·
          Site by <a href="https://828marketingsolutions.com" target="_blank" rel="noopener">828 Marketing Solutions</a>
        </p>
        <ul class="mt-4">
          <?php foreach (pps_services() as $slug => $service) : ?>
            <li><a href="<?php echo esc_url(pps_service_url($slug)); ?>"><?php echo esc_html($service['name']); ?></a></li>
          <?php endforeach; ?>
        </ul>
      </footer>
    </div>

    <!-- React: src/components/Chatbot.js (pending) -->
    <div id="pps-chatbot"></div>

    <!-- React: src/components/CookieBanner.js -->
    <div id="pps-cookie"></div>

    <?php wp_footer(); ?>
  </body>
</html>
