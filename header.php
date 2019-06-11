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

  <a class="skip-link screen-reader-text" href="#content">
    Skip to content
  </a>


  <header class="site-header">

  </header>

  <main id="content">


  <?=T\OpenSection(['width' => '100', 'align' => 'center'])?>
