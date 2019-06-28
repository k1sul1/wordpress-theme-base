<?php
/**
 * Helpers and template tags, related to posts.
 */

namespace k1\Post;

/**
 * Return post excerpt. Tries to get a dedicated custom field first, then falls
 * back to native excerpt and finally gets a preview from content unless told not to.
 *
 * @param mixed $post_id
 * @param boolean $fallback
 */
function getExcerpt($post_id = null, $fallback = true) {
  if (is_null($post_id)) {
    $post_id = get_the_ID();
  }

  if ($acf_ingress = get_field("ingress", $post_id)) {
    return $acf_ingress;
  } elseif (has_excerpt($post_id)) {
    return get_the_excerpt($post_id);
  } elseif ($fallback) {
    return getPreview($post_id);
  }

  return false;
}

/**
 * Get preview from the content. Stops at first paragraph.
 *
 * @param mixed $post_id
 */
function getPreview($post_id = null) {
  if (is_null($post_id)) {
    $post_id = get_the_ID();
  }

  $str = wpautop(get_post($post_id)->post_content);
  $str = substr($str, 0, strpos($str, '</p>') + 4);
  $str = strip_tags($str, '<a><strong><em>');

  return $str;
}

/**
 * Return the excerpt as a template tag, using getExcerpt().
 *
 * @param mixed $post_id
 * @param boolean $fallback
 */
function excerpt($post_id = null, $fallback = true) {
  $excerpt = getExcerpt($post_id, $fallback);

  return \k1\tag([
    "<div class='k1sul1-excerpt'>",
    strpos($excerpt, "<p>") > -1
    ? $excerpt
    : "<p>$excerpt</p>",
    "</div>"
  ]);
}

/**
 * Return a preview of the post as a template tag, using getPreview().
 *
 * @param int $word_count
 * @param string $more
 * @param mixed $post_id
 */
function preview($word_count = 30, $more = "&hellip;", $post_id = null) {
  $preview = getPreview($post_id);

  return \k1\tag([
    "<div class='k1sul1-preview'>",
    "<p>" . wp_trim_words($preview, $word_count, $more) . "</p>",
    "</div>"
  ]);
}

/**
 * Returns post type archive link, in a consistent way.
 *
 * @param string $post_type
 */
function archiveLink($post_type = 'post') {
  if ($post_type === 'post') {
    return get_permalink(get_option('page_for_posts'));
  }
  $archive = get_post_type_archive_link($post_type);
  if (!$archive) {
    throw new \Exception("Post type has no archive or it doesn't exist.");
  }
  return $archive;
}
