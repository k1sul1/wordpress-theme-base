<?php
/**
 * The search page. Will list search results.
 */
namespace k1;

get_header(); ?>

<div class="k1-root--search-page">
  <div class="k1-search-container container">
    <form class="k1-search-form" action="/">
      <input type="search" name="s" placeholder="Search from site">
      <button type="submit">Search</button>
    </form>

    <h1>
      Search: <?=get_search_query()?>
    </h1>
  </div>

  <div class="container">
    <?=Templates\PostList()?>
  </div>
</div>

<?php get_footer(); ?>
