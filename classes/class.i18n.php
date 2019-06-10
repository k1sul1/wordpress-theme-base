<?php
namespace k1;

/**
 * Handles all i18n configuration and output. "Plugs" into Polylang.
 *
 * @todo Waiting for project to test it
 */
class i18n {
  public $languages;

  public function __construct($languageSlugs = ['fi', 'en']) {
    $this->languages = $languageSlugs;

    $this->maybeRegisterStrings();
  }

  public function getLanguage() {
    if (!function_exists('pll_current_language')) {
      return $this->languages[0];
    }

    return \pll_current_language();
  }

  public function getLanguages() {
    return $this->languages;
  }

  public function getText(string $x, string $languageSlug = null) {
    if (!function_exists('pll_translate_string')) {
      return \__($x, 'wordpress-theme-base');
    }

    return \pll_translate_string($x, $languageSlug);
  }

  public function getOptionName(string $x, string $languageSlug = null) {
    $prefix = $languageSlug ?? $this->getLanguage();

    return "{$prefix}_{$x}";
  }

  /**
   * Add translatable strings here, or create an options field for the translations, and loop that.
   * Or use string translation manager plugin like a normal person.
   */
  public function maybeRegisterStrings() {
    if (function_exists('pll_register_string') && is_admin()) {
      $strings = [
        "Breadcrumb: Home" => "Home",

        "Menu: Close" => "Close",

        "Pagination: First" => "First",
        "Pagination: Last" => "Last",
        "Pagination: Next" => "Next",
        "Pagination: Previous" => "Previous",

        "Button: Read more" => "Read more",
        "Button: Back" => "Back",

        "Share: Follow us" => "Follow us",
        "Share: CTA" => "Share",
        "Share: Share on [some]" => "Share on",

        "Sidebar: Archive" => "Archive",
        "Sidebar: Categories" => "Categories",
        "Sidebar: Tags" => "Tags",

        "Widget: Related posts" => "Related posts",

        "Some: Share" => "Share article",
        "Some: Share on %s" => "Share on %s",
      ];

      foreach ($strings as $key => $value) {
        // Register long strings as text areas
        pll_register_string($key, $value, "wordpress-theme-base", strlen($value) > 60);
      }
    }
  }
}
