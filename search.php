<?php
/**
 * The search page. Will list search results.
 */
namespace k1;

use \k1\Templates as T;

get_header(); ?>

<div class="k1-root k1-root--search-page k1-scheme--base-default">
  <div class="k1-search-container k1-container">
    <?=T\Search()?>

    <h1>
      Search: <?=get_search_query()?>
    </h1>
  </div>

  <div class="k1-container">
    <?=Templates\PostList()?>
  </div>
</div>

<?php get_footer(); ?>
