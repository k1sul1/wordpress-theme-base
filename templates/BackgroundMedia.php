<?php
namespace k1\Templates;

function BackgroundMedia($data = []) {
  $data = \k1\params([
    'backgroundMedia' => [
      'type' => 'image', // or 'video',
      'video' => [
        'type' => null,
        'videoId' => null,
        'file' => null,
      ],
      'image' => [
        'data' => null, // ACF image array
        'position' => 'centerCenter', // Cloned position field
        'backgroundSize' => 'cover', // Cloned background size field
      ]
    ],
  ], $data); ?>

  <div <?=\k1\className("k1-bgmedia", "k1-bgmedia--type-". $data['backgroundMedia']['type'])?>>
    <?php
    switch ($data['backgroundMedia']['type']) {
      case 'image':
      $image = $data['backgroundMedia']['image']['data'];
      $position = $data['backgroundMedia']['image']['position'];
      $bgSize = $data['backgroundMedia']['image']['backgroundSize'];

      echo \k1\Media\image($image, [
        'size' => 'large',
        'className' => array_merge(["k1-image", "k1-bgmedia__image"], \k1\Template\getPosition($position), \k1\Template\getBackgroundSize($bgSize)),
      ]);
      break;

      case 'video':

      break;

      default:
        echo "<!-- Invalid type for BackgroundMedia -->";
    } ?>
  </div><?php
}
