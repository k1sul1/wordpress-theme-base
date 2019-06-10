<?php
/**
 * The header template. Included by get_header().
 */
namespace k1;
use \k1\Templates as T;

$app = app();

use \k1\Options; ?>

  <?=T\CloseSection()?>
  </main>
  <footer class="site-footer">
    <div class="container">
      <p class="copyright"><?=$app->getOption("copyright_text")?></p>
    </div>
  </footer>
  <?php wp_footer(); ?>
  </body>
</html>
