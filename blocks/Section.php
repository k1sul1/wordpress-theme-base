<?php
namespace k1\Blocks;

use \k1\Templates as T;

/**
 * Sample implementation. Works just fine in the frontend but admin is messy.
 * This is one way of having easy control over element sizing and colourschemes,
 * but not necessarily the way I'd recommend. This method worked nicely with flexible content
 * but Gutenberg is different.
 *
 * It's better to create classes like .align-center, .width-100, and apply them to your blocks.
 * Use the clone field to your advantage.
 *
 * This also shows that if you don't like "mixing HTML with PHP", you don't have to with this theme.
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

