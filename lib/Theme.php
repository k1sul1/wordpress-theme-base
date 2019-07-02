<?php
/**
 * Theme related configuration.
 */
namespace k1\Theme;

add_action("after_setup_theme", function () {
  // This is a relic from TinyMCE era, still somewhat required.
  $GLOBALS["content_width"] = 1366;

  add_theme_support("custom-logo", [
    "flex-width" => true,
    "flex-height" => true,
  ]);

  add_theme_support("post-thumbnails");
  add_theme_support("title-tag");
  add_theme_support("html5", [
    "search-form",
    "comment-list",
    "gallery",
    "caption",
    // "comment-form" // until someone makes the hard coded novalidate attr filterable this stays off.
  ]);


  // Disable the colour options in Gutenberg.
  // You don't want that kind of power for your users, schemes are a much better option.
  add_theme_support('editor-color-palette');
  add_theme_support('disable-custom-colors');
});
