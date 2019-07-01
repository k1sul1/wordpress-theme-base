<?php
namespace k1\Templates;

use \k1\Media as M;

/**
 * Generic search form template.
 */
function Search($data = []) {
  $app = \k1\app();
  $data = \k1\params([
    'classList' => ['k1-search-form'],
    'action' => '/',
    'placeholder' => 'Find from page',
  ], $data);
  ?>

  <form <?=\k1\className(...$data['classList'])?> action="<?=$data['action']?>">
    <input type="search" name="s" placeholder="<?=$app->i18n->getText($data['placeholder'])?>">

    <button type="submit">
      <?=M\svg('magnifying-glass-2.svg'); ?>
      <span class="sr-text">
        Search
      </span>
    </button>
  </form><?php
}
