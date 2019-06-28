<?php
namespace k1\Blocks;

class WysiwygColumns extends \k1\Block {
  public function render() {
    $data = [
      'blockSettings' => get_field('blockSettings'), // [],
      'columns' => get_field('columns'), // [],
    ];

    $classes = array_merge(
      [
        "k1-wcolumns",
        "k1-wcolumns--count-" . count($data['columns']),
      ],
      \k1\Template\getScheme($data['blockSettings']['scheme']),
      \k1\Template\getBreakpoints($data['blockSettings']['breakpoints'])
    );
    ?>

    <div <?=\k1\className(...$classes)?>>
      <div class="k1-container">
        <?php
        foreach ($data['columns'] as $k => $column) { ?>
          <div <?=\k1\className("k1-wcolumns__column", "k1-wcolumns__column--$k")?>>
            <?=\k1\content($column['wysiwyg'])?>
          </div><?php
        } ?>
      </div>
    </div><?php
  }
}
