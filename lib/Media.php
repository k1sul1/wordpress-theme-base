<?php
namespace k1\Media;

/**
 * Get SVG from the theme dist folder
 */
function svg(string $filename, array $data = []) {
  $data = \k1\params([
    'wrapperClass' => '',
  ], $data);
  $wrapper = function($svgEl) use ($data) {
    return "<div class='k1-svg $data[wrapperClass]'>$svgEl</div>";
  };

  return $wrapper(file_get_contents(
    \k1\app()->getAssetFilename('img/' . $filename, 'client', false)
  ));
}

/**
 * Get an image from WordPress. Caption and srcset support.
 */
function image($image = null, array $data = []) {
  $data = \k1\params([
    'size' => 'medium',
    'class' => 'k1-image',
    'responsive' => true,
    'sizes' => null,
    'allowCaption' => false,
  ], $data);

  $image = getImageData($image, $data['size']);

  if (!$image) {
    return false;
  }

  $tag = "<img src='$image[src]' class='$data[class]' alt='$image[alt]'";

  if ($data['responsive']) {
      $tag .= " srcset='$image[srcset]' sizes='$data[sizes]'";
  }

  if ($image['title']) {
      $tag .= " title='$image[title]'";
  }

  $tag .= '>';

  if ($data['allowCaption'] && $image['caption']) {
    return "
      <figure class='k1-figure'>
        $tag

        <figcaption class='k1-figure__caption'>
          $image[caption]
        </figcaption>
      </figure>
    ";
  }

  return $tag;
}

/**
 * Get image data from WordPress.
 */
function getImageData($image = null, string $size = 'medium') {
  if (is_array($image)) {
    $id = $image['ID'];
  } else if (is_numeric($image)) {
    $id = absint($image);
  } else {
    return false;
  }

  $x = get_post($id);
  $data = [
    'src' => wp_get_attachment_image_url($id, $size),
    'srcset' => wp_get_attachment_image_srcset($id, $size),
    'description' => \esc_html($x->post_content),
    'title' => \esc_attr($x->post_title),
    'alt' => get_post_meta($id, '_wp_attachment_image_alt', true),
    'caption' => \esc_html($x->post_excerpt),
  ];

  return $data;
}
