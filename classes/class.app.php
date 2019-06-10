<?php
namespace k1;

/**
 * Singleton class that configures the WordPress site
 */
class App {
  public $i18n;
  public $manifests;

  protected $blocks = [];
  protected static $instance;

  public static function init($options = []) {
    if (self::$instance) {
      return self::$instance;
    }

    self::$instance = new App($options);

    return self::$instance;
  }

  public function enqueue(string $assetName, string $manifest, $dependencies = []) {
    $filename = $this->getAssetFilename($assetName, $manifest);
    $basename = basename($filename);
    $isWDS = isWDS(); // To force cache disable and to run scripts in head in dev
    $isJS = strpos($filename, '.js') !== false;
    $isCSS = !$isJS && strpos($filename, '.css') !== false;

    // var_dump($isJS); var_dump($isCSS); die();

    if($isJS) {
      // die("FUCKING CUNT");
    }

    if ($isJS) {
      // die("PLS");
      wp_enqueue_script(
        "k1-$basename",
        $filename,
        $dependencies,
        $isWDS ? date('U') : null,
        !$isWDS
      );
    } else if ($isCSS) {
      die("PLS2");
      wp_enqueue_style(
        "k1-$basename",
        $filename,
        $dependencies,
        $isWDS ? date('U') : null,
      );
    } else {
      // throw new \Exception("Unable to enqueue $assetName $filename as it's extension is unsupported");
    }

    // Return the handle for use in wp_localize_script
    return "k1-$basename";
  }

  public function getAssetFilename(string $assetName, string $manifest) {
    if (isset($this->manifests[$manifest]) && isset($this->manifests[$manifest][$assetName])) {
      $filename = $this->manifests[$manifest][$assetName];

      return get_stylesheet_directory() . "/dist/$filename";
    }

    return false;
  }

  /**
   * Get option from ACF options page
   */
  public function getOption($x, $languageSlug = null) {
    $optionName = $this->i18n->getOptionName($x, $languageSlug);

    return \get_field($optionName, 'options');
  }

  /**
   * Forbid initialization by new by making the constructor private.
   * Use the \k1\app() function.
   */
  private function __construct($options = []) {
    $options = array_merge([
      'blocks' => [/* fill with file paths */],
      'templates' => [/* fill with file paths */],
      'languageSlugs' => ['en'],
      'manifests' => [
        'client' => __DIR__ . '/../dist/client-manifest.json',
        'admin' => __DIR__ . '/../dist/admin-manifest.json'
      ]
    ], $options);

    $this->manifests['client'] = (array) json_decode(file_get_contents($options['manifests']['client']));
    $this->manifests['admin'] = (array) json_decode(file_get_contents($options['manifests']['admin']));
    $this->i18n = new i18n($options['languageSlugs']);

    /**
     * Load & initialize blocks and templates
     */

    foreach($options['templates'] as $template) {
      require_once $template;
    }

    foreach ($options['blocks'] as $block) {
      require_once $block;

      $className = basename($block, '.php');
      $Class = "\\k1\Blocks\\$className";

      if (!class_exists($Class)) {
        throw new \Exception("Block $block is invalid");
      }

      $instance = new $Class();
      $this->blocks[$instance->getName()] = $instance;
    }
  }
}
