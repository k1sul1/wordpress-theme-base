<?php
namespace k1\GF;

// Gravity Forms makes some absolutely mental decisions.
// Loading scripts in head? Not on my watch.
add_filter("gform_tabindex", "\\__return_false");
add_filter("gform_init_scripts_footer", "\\__return_true");

add_filter("gform_cdata_open", function () {
  return "document.addEventListener('DOMContentLoaded', function() { ";
});

add_filter("gform_cdata_close", function () {
  return "}, false);";
});
