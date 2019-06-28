<?php
/**
 * The index page. The last file WordPress will try to load when resolving template.
 * See http://wphierarchy.com for help.
 */
namespace k1;

get_header(); ?>

<div class="k1-root k1-root--archive">
  <?php
  while (have_posts()) { the_post();
    the_content();
  } ?>
</div>

<?php get_footer();
