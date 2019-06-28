<?php
namespace k1\Blocks;

class Hero extends \k1\Block {
  public function render() {
    $data = [
      'blockSettings' => get_field('blockSettings'),
      'background' => get_field('background'),
      'content' => get_field('content'),
    ];

    $classes = array_merge(
      [
        "k1-hero",
      ],
      \k1\Template\getScheme($data['blockSettings']['scheme']),
      \k1\Template\getBreakpoints($data['blockSettings']['breakpoints']),
      \k1\Template\getPosition($data['content']['position'])
    );

    $contentClasses = array_merge(
      [
        "k1-hero__content",
      ]
    );
    ?>

    <div <?=\k1\className(...$classes)?>>
      <?=\k1\Templates\BackgroundMedia($data['background'])?>

      <div <?=\k1\className(...$contentClasses)?>>
        <div class="k1-container">
          <?=\k1\content($data['content']['data'])?>
        </div>
      </div>
    </div><?php
  }
}
