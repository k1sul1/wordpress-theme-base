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

  $options = ['choose' => 'Choose'];
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

  $breakpoints = json_decode(file_get_contents($templateDir . "/src/breakpoints.json"));
  $breakpoints = array_keys((array) $breakpoints); // Only keys are needed

  $options = [];
  foreach ($breakpoints as $key) {
    $options[$key] = strtoupper(str_replace("-", " ", $key));
  }

  $populator = function($field) use ($options) {
    $field['instructions'] = \k1\app()->i18n->getText("Avoid hiding elements if possible, and provide an alternate element, visible only to that breakpoint.");
    $field['choices'] = $options;

    return $field;
  };

  add_filter('acf/load_field/key=field_5d16385be0a6b', $populator);
}
