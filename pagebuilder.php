<?php
/**
 * Template Name: Pagebuilder
 *
 * @package WordPress
 */

get_header(); ?>

<div class="pagebuilder">
  <?=\k1\Pagebuilder::instance()->getLayout()?>
</div>

<?php get_footer();
