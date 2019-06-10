<?php
/**
 * The header template. Included by get_header().
 */
namespace k1;

$app = app();

use \k1\Options; ?>

  </main>
  <footer class="site-footer">
    <div class="container">
      <p class="copyright"><?=$app->getOption("copyright_text")?></p>
    </div>
  </footer>
  <?php wp_footer(); ?>
  </body>
</html>
