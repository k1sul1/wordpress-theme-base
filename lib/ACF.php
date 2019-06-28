<?php
/**
 * ACF related filters and functions live here.
 * If it's a template tag, it doesn't belong here.
 */

namespace k1\ACF;

if (is_admin()) {
  $templateDir = get_template_directory();
  $schemes = json_decode(file_get_contents($templateDir . "/src/schemes.json"));
  $schemes = array_keys((array) $schemes); // Only keys are needed

  $options = [];
  foreach ($schemes as $key) {
    $options[$key] = ucfirst(str_replace("-", " ", $key));
  }

  $populator = function($field) use ($options) {
    $field['choices'] = $options;

    return $field;
  };

  add_filter('acf/load_field/key=field_5d1636cfee8cf', $populator);
  add_filter('acf/load_field/key=field_5d163631ee8cd', $populator);
  add_filter('acf/load_field/key=field_5d163687ee8ce', $populator);
  add_filter('acf/load_field/key=field_5d163783ee8d1', $populator);
  add_filter('acf/load_field/key=field_5d16379aee8d2', $populator);
}
