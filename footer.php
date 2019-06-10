<?php
/**
 * The header template. Included by get_header().
 */
namespace k1;

use \k1\Options; ?>

  </main>
  <footer class="site-footer">
    <div class="container">
      <p class="copyright"><?=Options\get("copyright_text")?></p>
    </div>
  </footer>
  <?php wp_footer(); ?>
  </body>
</html>
