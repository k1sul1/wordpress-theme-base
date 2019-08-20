<?php
namespace k1;

/**
 * Load and initialize k1kit if not done already
 *
 */
if (!class_exists('\k1\App')) {
  if (is_dir(WP_PLUGIN_DIR . '/k1kit')) {
    require_once WP_PLUGIN_DIR . '/k1kit/src/php/init.php';
  } else {
    throw new \Exception("k1kit wasn't found. The theme can't be used without it.");
  }
}

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
  'languageSlugs' => ['en'],
  'manifests' => [
    'client' => __DIR__ . '/dist/client-manifest.json',
    'admin' => __DIR__ . '/dist/admin-manifest.json'
  ],
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
  $jshandle = $app->manifests['client']->enqueue('client.js');

  \wp_enqueue_style(
    'k1-google-fonts',
    'https://fonts.googleapis.com/css?family=Montserrat:700|Source+Sans+Pro:400,700&display=swap',
    [],
    null
  );

  $csshandle = $app->manifests['client']->enqueue('client.css');

  wp_localize_script($jshandle, 'wptheme', array_merge($localizeData, [
    'corejs' => $app->manifests['client']->getAssetFilename('corejs.js'),
    'regeneratorRuntime' => $app->manifests['client']->getAssetFilename('regeneratorRuntime.js'),
  ]));
});

add_action('admin_enqueue_scripts', function() use ($app, $localizeData) {
  $jshandle = $app->manifests['admin']->enqueue('admin.js');
  \wp_enqueue_style(
    'k1-google-fonts',
    'https://fonts.googleapis.com/css?family=Montserrat:700|Source+Sans+Pro:400,700&display=swap',
    [],
    null
  );

  $csshandle = $app->manifests['admin']->enqueue('admin.css');

  wp_localize_script($jshandle, 'wptheme', $localizeData);
});
