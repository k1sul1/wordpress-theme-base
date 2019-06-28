<?php
/**
 * The footer template. Included by get_footer().
 */
namespace k1;
use \k1\Templates as T;

$app = app();

use \k1\Options; ?>

  </main>
  <footer class="site-footer">
    <div class="k1-container">
      <p class="copyright"><?=$app->getOption("copyright_text")?></p>
    </div>
  </footer>
  <?php wp_footer(); ?>
  </body>
</html>
