<?php
/**
 * Tweaks to admin live here.
 */
namespace k1\Admin;

// Users who will edit navigations will wonder where are the options
// if this doesn't exist.
add_action("user_register", function ($user_id) {
  update_user_option($user_id, "metaboxhidden_nav-menus", [

  ]); // No hidden metaboxes. Show them all.
}, 10);
