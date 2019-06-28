<?php
namespace k1;

foreach (glob(dirname(__FILE__) . "/lib/*.php") as $filename) {
  require_once($filename);
}

foreach (glob(dirname(__FILE__) . "/classes/class.*.php") as $filename) {
  require_once($filename);
}

function app($options = []) {
  return App::init($options);
}

$app = app([
  'blocks' => glob(__DIR__ . '/blocks/*.php'),
  'templates' => glob(__DIR__ . '/templates/*.php'),
  'languageSlugs' => ['en']
]);

$siteurl = get_site_url();

/**
 * Pass useful data to the frontend, instead of crawling these from the DOM.
 * Path can be used for dynamic imports, and wpurl for making HTTP requests,
 * if you absolutely have to have absolute urls in your code.
 */
$localizeData = [
  'lang' => $app->i18n->getLanguage(),
  'path' => str_replace($siteurl, '', get_stylesheet_directory_uri()),
  'wpurl' => $siteurl
];

add_action('wp_enqueue_scripts', function() use ($app, $localizeData) {
  $jshandle = $app->enqueue('client.js', 'client');
  $csshandle = $app->enqueue('client.css', 'client');

  wp_localize_script($jshandle, 'wptheme', array_merge($localizeData, [
    'corejs' => $app->getAssetFilename('corejs.js', 'client'),
    'regeneratorRuntime' => $app->getAssetFilename('regeneratorRuntime.js', 'client'),
  ]));
});

add_action('admin_enqueue_scripts', function() use ($app, $localizeData) {
  $siteurl = get_site_url();
  $jshandle = $app->enqueue('admin.js', 'admin');
  $csshandle = $app->enqueue('admin.css', 'admin');

  wp_localize_script($jshandle, 'wptheme', $localizeData);
});

/**
 * Create options pages for all languages.
 * Please note that you have to create the field groups yourself,
 * and use the clone field with the prefix setting in it.
 */
if (function_exists('acf_add_options_page')) {
  $languages = $app->i18n->getLanguages();
  $parent = acf_add_options_page([
    "page_title" => "Options Page",
    "menu_slug" => "acf-opts",
  ]);

  foreach ($languages as $lang) {
    $fields = [
      "page_title" => "Options $lang",
      "menu_title" => "Options $lang",
      "parent_slug" => $parent["menu_slug"],
    ];

    // Set first language as first
    if ($lang === $languages[0]) {
      $fields["menu_slug"] = "acf-options";
    }

    acf_add_options_sub_page($fields);
  }
}
