<?php

namespace k1\Templates;

/**
 * While have posts loop that displays posts with the provided template.
 * Uses the main query if no query is provided.
 */
function PostList($data = []) {
  $data = \k1\params([
    'query' => null,
    'template' => __NAMESPACE__ . '\PostPreview',
  ], $data);
  $app = \k1\app();

  if (is_null($data["query"])) {
    $havePosts = "have_posts";
    $thePost = "the_post";
  } else {
    // https://stackoverflow.com/questions/16380745/is-is-possible-to-store-a-reference-to-an-object-method
    $havePosts = [$data["query"], "have_posts"];
    $thePost = [$data["query"], "the_post"];
  } ?>

  <div class="k1-container k1-postlist">
    <?php
    while ($havePosts()) { $thePost();
      $data['template']();
    } ?>

    <div class="k1-postlist__pagination">
      <?=\paginate_links([
        'prev_text' => $app->i18n->getText('Previous'),
        'next_text' => $app->i18n->getText('Next'),
      ])?>
    </div>
  </div><?php
}
