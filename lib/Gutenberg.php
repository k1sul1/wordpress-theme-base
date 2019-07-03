<?php
namespace k1\Gutenberg;


/**
 * Wrap certain blocks so styling them is easier.
 */
add_filter('render_block', function ($content, $block) {
  /**
   * If there's no blockName and the content more than 2 characters long,
   * it's most likely editor content from the classic editor.
   */
  if ($block['blockName'] === null && (strlen($content) < 3 && strpos($content, "\n") !== false)) {
    $content = "<div class='k1-tinymce k1-container'>{$content}</div>";
  } else if (strpos($block['blockName'], 'core/') === 0) {
    return $content;
  } else if (strpos($block['blockName'], 'gravityforms/') === 0) {
    $content = "<div class='k1-gfblock k1-container'>{$content}</div>";
  }

  return $content;
}, 10, 2);
