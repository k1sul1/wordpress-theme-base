<?php
/**
 * Singular content. Used by posts, pages and custom post types by default.
 * See https://wphierarchy.com for help.
 */
namespace k1;

get_header(); ?>

<div class="k1-root k1-root--front-page k1-scheme--base-default">
  <?php

  while (have_posts()) { the_post();
    gutenbergContent();
  } ?>
</div>

<?php get_footer();
