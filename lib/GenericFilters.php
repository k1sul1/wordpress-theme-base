<?php
/**
 * Generic filters when there's no better place to put them
 * and you're too lazy to create a new one..
 */
namespace k1\GenericFilters;

function titlePrefix($title) {
  $dev = "D";
  $staging = "S";
  $production = "P";

  if (\k1\isProd() && is_user_logged_in()) {
    return $title;
  } else if (\k1\isDev()) {
    return "[$dev] $title";
  } elseif (!empty($_COOKIE["seravo_shadow"]) || \k1\isStaging()) {
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
    if (strpos(\k1\currentUrl(), $domain) > -1) {
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

add_filter("the_seo_framework_title_from_generation", "\\k1\\GenericFilters\\titlePrefix");
add_filter("admin_title", "\\k1\\GenericFilters\\titlePrefix");
add_filter("wp_title", "\\k1\\GenericFilters\\titlePrefix");

/**
 * Strip empty paragraphs
 *
 * @param mixed $content
 */
function stripEmptyParagraphs($content) {
  return str_replace("<p>&nbsp;</p>", "", $content);
}

add_filter("the_content", "\\k1\\GenericFilters\\stripEmptyParagraphs");

function removeFUCKINGmargin() {
  remove_action('wp_head', '_admin_bar_bump_cb');
}

add_action('get_header', '\\k1\\GenericFilters\\removeFUCKINGmargin');

// Disable "traffic lights"
add_filter("the_seo_framework_show_seo_column", "__return_false");


function fixArchiveTitle($title) {
  $app = \k1\app();

  if (is_home()) {
    $title = $app->i18n->getText('Uutiset');
  } elseif (is_category()) {
      $title = single_cat_title($app->i18n->getText('Category') . ': ', false);
  } elseif (is_tag()) {
      $title = single_tag_title($app->i18n->getText('Tag') . ': ', false);
  } elseif (is_author()) {
      $title = '<span class="vcard">' . get_the_author() . '</span>';
  } elseif (is_year()) {
      $title = get_the_date(_x('Y', 'yearly archives date format'));
  } elseif (is_month()) {
      $title = get_the_date(_x('F Y', 'monthly archives date format'));
  } elseif (is_day()) {
      $title = get_the_date(_x('F j, Y', 'daily archives date format'));
  } elseif (is_tax('post_format')) {
      // Who uses these? I don't.
      if ( is_tax('post_format', 'post-format-aside')) {
          $title = _x('Asides', 'post format archive title');
      } elseif ( is_tax('post_format', 'post-format-gallery')) {
          $title = _x('Galleries', 'post format archive title' );
      } elseif ( is_tax('post_format', 'post-format-image')) {
          $title = _x('Images', 'post format archive title');
      } elseif ( is_tax('post_format', 'post-format-video')) {
          $title = _x('Videos', 'post format archive title');
      } elseif ( is_tax('post_format', 'post-format-quote')) {
          $title = _x('Quotes', 'post format archive title');
      } elseif ( is_tax('post_format', 'post-format-link')) {
          $title = _x('Links', 'post format archive title');
      } elseif ( is_tax('post_format', 'post-format-status')) {
          $title = _x('Statuses', 'post format archive title');
      } elseif ( is_tax('post_format', 'post-format-audio')) {
          $title = _x('Audio', 'post format archive title');
      } elseif ( is_tax('post_format', 'post-format-chat')) {
          $title = _x('Chats', 'post format archive title');
      }
  } elseif (is_post_type_archive()) {
      $title = post_type_archive_title('', false);
  } elseif (is_tax()) {
      $title = single_term_title('', false);
  } else {
      $title = $app->i18n->getText('Archive');
  }
  return $title;
}

add_filter('get_the_archive_title', "\\k1\\GenericFilters\\fixArchiveTitle");


/**
 * Add %home% tag to Breadcrumb NavXT
 */
add_filter("bcn_template_tags", function ($replacements, $type, $id) {
  $app = \k1\app();
  $replacements["%home%"] = $app->i18n->getText("Home");

  return $replacements;
}, 3, 10);

/**
 * OG tag image urls must be absolute
 *
 * @param string $image The social image URL.
 * @param int    $id    The page or term ID.
*/
function setAbsoluteImageUrl($image, $id) {
  if (strpos($image, "http") === 0) {
    return esc_url($image);
  } else {
    return esc_url(home_url($image));
  }
}

// OG image
add_filter("the_seo_framework_ogimage_output", "\\k1\\GenericFilters\\setAbsoluteImageUrl", 10, 2);

// Twitter card image
add_filter("the_seo_framework_twitterimage_output", "\\k1\\GenericFilters\\setAbsoluteImageUrl", 10, 2);


/**
 * Wrap embedded media as suggested by Readability
 *
 * @link https://gist.github.com/965956
 * @link http://www.readability.com/publishers/guidelines#publisher
 */
function embedWrap($cache) {
  return '<div class="responsive-embed">' . $cache . '</div>';
}

add_filter('embed_oembed_html', '\\k1\\GenericFilters\\embedWrap');

add_action('after_setup_theme', function () {
  remove_filter('embed_oembed_html', 'Roots\\Soil\\CleanUp\\embedWrap');
}, 101);

// Increase srcset max image width from default value of 1600
add_filter('max_srcset_image_width', function () {
  return 2560;
});
