<?php
/**
 * Generic filters when there's no better place to put them
 * and you're too lazy to create a new one..
 */
namespace k1\GenericFilters;

function title_prefix($title) {
  $dev = "D";
  $staging = "S";

  if (\k1\is_prod() && is_user_logged_in()) {
    return $title;
  } else if (\k1\is_dev()) {
    return "[$dev] $title";
  } elseif (!empty($_COOKIE["seravo_shadow"]) || \k1\is_staging()) {
    return "[$staging] $title";
  }

  // If both fail, fallback into this.
  $domains = [
    ".dev" => $dev,
    ".local" => $dev,
    "localhost" => $dev,
    ".seravo" => $production,
    ".wp-palvelu" => $production,
    get_site_url() => $production,
  ];

  foreach ($domains as $domain => $tag) {
    if (strpos(\k1\current_url(), $domain) > -1) {
      if ($tag === $production) {
        if (!is_user_logged_in()) {
          return $title;
        }
      }
      return "[$tag] $title";
    }
  }
  return $title;
}

add_filter("the_seo_framework_pro_add_title", "\\k1\\GenericFilters\\title_prefix");
add_filter("admin_title", "\\k1\\GenericFilters\\title_prefix");
add_filter("wp_title", "\\k1\\GenericFilters\\title_prefix");

/**
 * Strip empty paragraphs
 *
 * @param mixed $content
 */
function strip_empty_paragraphs($content) {
  return str_replace("<p>&nbsp;</p>", "", $content);
}

add_filter("the_content", "\\k1\\GenericFilters\\strip_empty_paragraphs");

// Disable "traffic lights"
add_filter("the_seo_framework_show_seo_column", "__return_false");

// Add %home% tag to bcn breadcrumbs
add_filter("bcn_template_tags", function ($replacements, $type, $id) {
  // d(...func_get_args());
  $replacements["%home%"] = gs("Breadcrumb: Home");

  return $replacements;
}, 3, 10);

/**
 * OG tag image urls must be absolute
 *
 * @param string $image The social image URL.
 * @param int    $id    The page or term ID.
*/
function set_absolute_image_url($image, $id) {
  if (strpos($image, "http") === 0) {
    return esc_url($image);
  } else {
    return esc_url(home_url($image));
  }
}

// OG image
add_filter("the_seo_framework_ogimage_output", "\\k1\\GenericFilters\\set_absolute_image_url", 10, 2);

// Twitter card image
add_filter("the_seo_framework_twitterimage_output", "\\k1\\GenericFilters\\set_absolute_image_url", 10, 2);


/**
 * Wrap embedded media as suggested by Readability
 *
 * @link https://gist.github.com/965956
 * @link http://www.readability.com/publishers/guidelines#publisher
 */
function embed_wrap($cache) {
  return '<div class="responsive-embed">' . $cache . '</div>';
}

add_filter('embed_oembed_html', '\\k1\\GenericFilters\\embed_wrap');

add_action('after_setup_theme', function () {
  remove_filter('embed_oembed_html', 'Roots\\Soil\\CleanUp\\embed_wrap');
}, 101);

// Increase srcset max image width from default value of 1600
add_filter('max_srcset_image_width', function () {
  return 2560;
});
