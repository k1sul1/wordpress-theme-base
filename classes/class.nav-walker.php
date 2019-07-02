<?php

namespace k1;

/**
 * Cleaner walker for wp_nav_menu()
 *
 * roots/soil nav walker with minor modifications.
 * https://github.com/roots/soil/blob/master/modules/nav-walker.php
 */

class NavWalker extends \Walker_Nav_Menu {
  private $cpt; // Boolean, is current post a custom post type
  private $archive; // Stores the archive page for current URL

  public function __construct() {
    add_filter('nav_menu_css_class', [$this, 'cssClasses'], 10, 2);
    add_filter('nav_menu_submenu_css_class', [$this, 'submenuCssClasses'], 10, 2);
    add_filter('nav_menu_item_id', '__return_null');

    $cpt = get_post_type();
    $this->cpt = in_array($cpt, get_post_types(array('_builtin' => false)));
    $this->archive = get_post_type_archive_link($cpt);
  }

  public function checkCurrent($classes) {
    return preg_match('/(current[-_])|active/', $classes);
  }

  /**
   * Method override from \Walker_Nav_Menu
   */
  public function display_element($element, &$childrenElements, $maxDepth, $depth = 0, $args, &$output) {
    $element->is_subitem = ((!empty($childrenElements[$element->ID]) && (($depth + 1) < $maxDepth || ($maxDepth === 0))));

    // Variable naming seems to be bit confusing here, because $element->is_subitem actually mean that it's a parent item.

    if ($element->is_subitem) {
      foreach ($childrenElements[$element->ID] as $child) {
        if ($child->current_item_parent || \k1\Menus\urlCompare($this->archive, $child->url)) {
          $element->classes[] = 'active';
        }
      }
    }

    $element->is_active = (!empty($element->url) && strpos($this->archive, $element->url));

    if ($element->is_active) {
      $element->classes[] = 'active';
    }

    parent::display_element($element, $childrenElements, $maxDepth, $depth, $args, $output);
  }

  public function cssClasses($classes, $item) {
    $slug = sanitize_title($item->title);

    // Fix core `active` behavior for custom post types
    if ($this->cpt) {
      $classes = str_replace('current_page_parent', '', $classes);

      if ($this->archive) {
        if (\k1\Menus\urlCompare($this->archive, $item->url)) {
          $classes[] = 'active';
        }
      }
    }

    // Remove most core classes
    $classes = preg_replace('/(current(-menu-|[-_]page[-_])(item|parent|ancestor))/', 'active', $classes);
    $classes = preg_replace('/^((menu|page)[-_\w+]+)+/', '', $classes);

    // Re-add core `menu-item` class
    $classes[] = 'menu-item';

    // Re-add core `menu-item-has-children` class on parent elements
    if ($item->is_subitem) {
      $classes[] = 'menu-item-has-children';
    }

    // Add `menu-<slug>` class
    $classes[] = 'menu-' . $slug;

    $classes = array_unique($classes);
    $classes = array_map('trim', $classes);

    return array_filter($classes);
  }

  public function submenuCssClasses($classes, $item) {
    $classes[] = 'k1-scheme--base-invert';

    return array_filter($classes);
  }
}
