<?php
namespace k1\Templates;

function Section($data = []) {
  CloseSection();
  OpenSection($data);
}

function CloseSection() { ?>
  </section>
<?php
}

function OpenSection($data = []) {
  $data = \k1\params([
    'width' => '100',
    'align' => 'center',
    'backgroundImage' => null,
    'colourscheme' => 'default'
  ], $data); ?>

  <section
    <?=isset($data['backgroundImage']) ? "style='background-image: url(\"$data[backgroundImage]\");'" : ''?>
    <?=\k1\className(
    'k1-section',
    "k1-section--width-$data[width]",
    "k1-section--align-$data[align]",
    "k1-section--colourscheme-$data[colourscheme]"
  )?>>
<?php
}
