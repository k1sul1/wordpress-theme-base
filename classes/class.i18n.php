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

    return \pll_current_language();
  }

  public function getOptionName(string $x, string $languageSlug = null) {
    $prefix = $languageSlug ?? $this->getLang();

    return "{$prefix}_{$x}";
  }
}
