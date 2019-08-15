<?php
/**
 * Register and tweak menus here.
 */
namespace k1\Menus;

add_action("init", function () {
  register_nav_menus([
    "header-menu" => "Header menu",
  ]);
});

/*
 * Add stages to menus so if it's required for something it's there.
 */
$menu_lookup = [];
add_filter("nav_menu_css_class", function ($classes, $item) use (&$menu_lookup) {
  $menu_lookup[$item->ID] = [
    "parent" => $item->menu_item_parent,
    "level" => $item->menu_item_parent !== '0' // Why is it a string?
      ? $menu_lookup[(int) $item->menu_item_parent]["level"] + 1
      : 0
  ];

  $classes[] = "level-{$menu_lookup[$item->ID]["level"]}";
  return $classes;
}, 999999, 2); // We want that the class is the last one.

/**
 * Compare URLs to see if they're equal
 */
function urlCompare($x, $y) {
  $url = trailingslashit($x);
  $rel = trailingslashit($y);
  return ((strcasecmp($x, $y) === 0));
}

/**
 * Clean up wp_nav_menu_args
 *
 * Remove the container
 * Remove the id="" on nav menu items
 */
add_filter('wp_nav_menu_args', function($args = '') {
  $navMenuArgs = [];
  $navMenuArgs['container'] = false;

  if (!$args['items_wrap']) {
    $navMenuArgs['items_wrap'] = '<ul class="%2$s">%3$s</ul>';
  }

  if (!$args['walker']) {
    $navMenuArgs['walker'] = new \k1\NavWalker();
  }

  $navMenuArgs['menu_class'] = !empty($navMenuArgs['menuClass'])
   ? "k1-menu $navMenuArgs[menu_class]"
   : 'k1-menu';

  return array_merge($args, $navMenuArgs);
});

add_filter('nav_menu_item_id', '__return_null');
