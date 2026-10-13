    </main>

    <?php $biz = pps_business(); ?>
    <!-- React: src/components/Footer.js. The markup inside is the crawlable fallback React replaces. -->
    <div id="pps-footer" class="bg-ink text-steel-300">
      <footer class="mx-auto max-w-7xl px-4 py-12">
        <p class="font-display text-xl font-bold uppercase text-white"><?php echo esc_html($biz['name']); ?></p>
        <address class="mt-2 not-italic">
          <?php echo esc_html("{$biz['street']}, {$biz['city']}, {$biz['region']} {$biz['postal']}"); ?><br>
          <a href="<?php echo esc_url($biz['phone_href']); ?>"><?php echo esc_html($biz['phone']); ?></a> ·
          <a href="mailto:<?php echo esc_attr($biz['email']); ?>"><?php echo esc_html($biz['email']); ?></a>
        </address>
        <ul class="mt-4">
          <?php foreach (pps_services() as $slug => $service) : ?>
            <li><a href="<?php echo esc_url(pps_service_url($slug)); ?>"><?php echo esc_html($service['name']); ?></a></li>
          <?php endforeach; ?>
        </ul>
      </footer>
    </div>

    <!-- React: src/components/Chatbot.js (pending) -->
    <div id="pps-chatbot"></div>

    <?php wp_footer(); ?>
  </body>
</html>
