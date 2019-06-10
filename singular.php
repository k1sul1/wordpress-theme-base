<?php
/**
 * Singular content. Used by posts, pages and custom post types by default.
 * See https://wphierarchy.com for help.
 */
namespace k1;

get_header(); ?>

<div class="singular">
  <?php

  while (have_posts()) { the_post();
    the_content();
  } ?>
</div>

<?php get_footer();
