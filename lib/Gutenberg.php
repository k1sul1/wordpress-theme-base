<?php
namespace k1\Gutenberg;


/**
 * Wrap certain blocks so styling them is easier.
 */
add_filter('render_block', function ($content, $block) {
  if ($block['blockName'] === null) {
    $content = "<div class='k1-tinymce k1-container'>{$content}</div>";
  } else if (strpos($block['blockName'], 'core/') === 0) {
    $content = "<div class='k1-coreblock k1-container'>{$content}</div>";
  } else if (strpos($block['blockName'], 'gravityforms/') === 0) {
    $content = "<div class='k1-gfblock k1-container'>{$content}</div>";
  }

  return $content;
}, 10, 2);
