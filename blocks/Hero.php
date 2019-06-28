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
