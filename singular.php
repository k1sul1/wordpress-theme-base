<?php
/**
 * Singular content. Used by posts, pages and custom post types by default.
 * See https://wphierarchy.com for help.
 */
namespace k1;

get_header(); ?>

<div class="k1-root--single-post">
  <?php

  while (have_posts()) { the_post();
    the_content();
  } ?>
</div>

<?php get_footer();
