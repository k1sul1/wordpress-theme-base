<?php
/**
 * The header template. Included by get_header().
 */
namespace k1;

use \k1\Media;
use \k1\Templates as T;

?>
<!doctype html>
<html <?php language_attributes(); ?>>
  <head>
    <meta name="viewport" content="initial-scale=1">
    <?php wp_head(); ?>
  </head>
  <?php
  global $is_anon_user;
  $is_anon_user = !is_user_logged_in();
  ?>
  <body <?php body_class([
    !$is_anon_user ? 'user-logged-in' : 'user-not-logged-in',
  ]);?>>

  <!-- Prevent FOUC in firefox, see https://bugzilla.mozilla.org/show_bug.cgi?id=1404468#c68 -->
  <script>0</script>
    
  <a class="skip-link sr-text" href="#content">
    Skip to content
  </a>


  <header class="k1-header k1-header--site">
    <div class="k1-header__beforeMenu k1-container">
      <?php
      if (\has_custom_logo()) {
        \the_custom_logo();
      } ?>

      <?=T\Search()?>
    </div>

    <nav class="k1-navigation k1-navigation--main k1-scheme--base-invert">
      <div class="k1-container">
        <?php if (\has_nav_menu('header-menu')) {
          \wp_nav_menu([
            "theme_location" => "header-menu",
          ]);
        } else {
          echo "<p>Header menu is empty.</p>";
        }?>
      </div>
    </nav>
  </header>


  <main id="content">
