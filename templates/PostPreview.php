<?php
namespace k1\Templates;

/**
 * Generic template for displaying posts in a list.
 */
function PostPreview($data = []) {
  $data = \k1\params([
    'title' => \k1\title(),
    'date' => \get_post_type() === 'post' ? \get_the_date('d.m.Y') : null,
    'content' => \k1\Post\getPreview(),
    'link' => \get_permalink(),
  ], $data);?>

  <article class="k1-postpreview">
    <a href="<?=\esc_attr($data['link'])?>">
      <h2><?=\esc_html($data['title'])?></h2>

      <?php if ($data['date']) { ?>
        <span class="k1-date">
          <?=\esc_html($data['date'])?>
        </span>
      <?php }

      if ($data['content']) { ?>
        <div class="k1-postpreview__content">
          <?=\esc_html(\strip_shortcodes($data['content']))?>
        </div>
      <?php } ?>
    </a>
  </article><?php
}
