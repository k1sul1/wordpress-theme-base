<?php
/**
 * Theme related configuration.
 */
namespace k1\Theme;

add_action("after_setup_theme", function () {
  $app = \k1\app();

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

  // Gutenberg support
  add_theme_support('align-wide');
  add_theme_support('responsive-embeds');

  // Disable the colour options.
  // You don't want that kind of power for your users, schemes are a much better option.
  add_theme_support('editor-color-palette');
  add_theme_support('disable-custom-colors');

  // Edit the available font sizes.
  // NOTE: The font-size selection element is hidden with CSS in this theme.
  add_theme_support('editor-font-sizes', [
    ['name' => $app->i18n->getText('Normal'), 'slug' => 'normal', 'size' => 18],
    ['name' => $app->i18n->getText('Large'), 'slug' => 'large', 'size' => 22],
  ]);
  add_theme_support('disable-custom-font-sizes');

  // https://developer.wordpress.org/block-editor/developers/themes/theme-support/#editor-styles
  // TL;DR editor-styles it not really required. The built admin.css handles styling in Gutenberg.
  // The classic editor remains unstyled, if you want to style it, I recommend that you create
  // a new entry to webpack.admin.js that will build editor.css.
  // Enqueue that file in functions.php using add_editor_style.
  //
  // add_theme_support('editor-styles');
  // add_theme_support('dark-editor-style');

  // Add core styles for blocks. There's a 99% chance that this will cause problems in the future.
  // NOTE: Styles seem to be present regardless if this is set or not.
  // add_theme_support('wp-block-styles');
});
