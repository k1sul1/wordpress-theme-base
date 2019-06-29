<?php
namespace k1\Blocks;

class Hero extends \k1\Block {
  public function render($fields) {
    $data = \k1\params([
      'blockSettings' => [ // cloned field
        'scheme' => [
          'base' => 'default',
          'advancedMode' => false,
        ],
        'breakpoints' => [
          'hideIn' => [],
        ]
      ],
      'background' => [
        'backgroundMedia' => [], // cloned field
      ],
      'content' => [
        'data' => '',
        'position' => 'leftCenter',
      ],
    ], $fields);

    $classes = array_merge(
      [
        "k1-hero",
      ],
      \k1\Template\getScheme($data['blockSettings']['scheme']),
      \k1\Template\getBreakpoints($data['blockSettings']['breakpoints']),
    );

    $containerClasses = array_merge(
      [
        "k1-container",
      ],
      \k1\Template\getPosition($data['content']['position'])
    );
    ?>

    <div <?=\k1\className(...$classes)?>>
      <?=\k1\Templates\BackgroundMedia($data['background'])?>

      <div <?=\k1\className(...$containerClasses)?>>
        <div class="k1-hero__content">
          <?=\k1\content($data['content']['data'])?>
        </div>
      </div>
    </div><?php
  }
}
