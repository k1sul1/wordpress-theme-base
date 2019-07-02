<?php
/**
 * Singular content. Used by posts, pages and custom post types by default.
 * See https://wphierarchy.com for help.
 */
namespace k1;

get_header(); ?>

<div class="k1-root k1-root--single-post k1-scheme--base-default">
  <?php

  while (have_posts()) { the_post();
    the_content();
  } ?>

  <?php
  if (\have_comments()) {
    echo "<div class='k1-commentlist'></div>";
    echo "<!-- The above element should be populated with React -->";
  }

  if (\comments_open()) {
    echo "<div class='k1-commentform'></div>";
    echo "<!-- The above element should be populated with React -->";
  }
  ?>
</div>

<?php get_footer();
