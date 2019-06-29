<?php
/**
 * The index page. The last file WordPress will try to load when resolving template.
 * See http://wphierarchy.com for help.
 */
namespace k1;

$obj = \get_queried_object();
$thumb = \get_post_thumbnail_id($obj->ID);
$title = \is_archive() ? get_the_archive_title() : $obj->post_title;

$app = app();
$hero = $app->getBlock('Hero');

get_header(); ?>

<div class="k1-root k1-root--archive">
  <?php
  echo withTransient(capture([$hero, 'render'], [
    'blockSettings' => [ // cloned field
      'scheme' => [
        'base' => 'invert',
        'advancedMode' => false,
      ],
    ],
    'content' => [
      'data' => '<h1>' . title($title) . '</h1>',
      'position' => 'centerBottom',
    ],
    'background' => [
      'backgroundMedia' => [
        'type' => 'image',
        'image' => [
          'data' => $thumb,
          'imagePosition' => 'centerCenter'
        ]
      ]
    ]
  ]), [
    'key' => 'indexHero',
    'options' => [
      'type' => 'manual-block',
    ]
  ], $missReason);

  echo "\n\n\n<!-- Block " . $hero->getName() . " cache: " . transientResult($missReason) . " -->";

  Templates\PostList(); ?>
</div>

<?php get_footer();
