<?php
namespace k1\Blocks;

use \k1\Templates as T;

/**
 * Sample implementation. Works just fine in the frontend but admin is messy.
 */
class Section extends \k1\Block {
  public function render($data = []) {
    $scheme = get_field('colourscheme');
    $width = get_field('width');
    $align = $data['align'];

    T\Section([
      'width' => $width,
      'align' => $align,
      'backgroundImage' => get_field('backgroundImage'),
      'colourscheme' => $scheme,
    ]);

    if (is_admin()) {
      echo "<h3>Width: $width Align: $align Colourscheme: $scheme</h3>";
    }
  }

  public function getSettings() {
    $data = parent::getSettings();
    $data['mode'] = 'preview';
    $data['supports']['align'] = true;

    return $data;
  }
}

