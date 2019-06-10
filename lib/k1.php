<?php
namespace k1;

/**
 * Helpers that you want to access with a short namespace. You can also create aliases
 * using the splat (...$data) operator.
 */


/**
 * Check if request originated from webpack-dev-server
 */
function isWDS($req = null) {
  if (!$req) {
    $req = $_REQUEST;
  }

  return !empty($req["HTTP_X_PROXY"]) && $req["HTTP_X_PROXY"] === 'webpack-dev-server';
}

function env() {
  if (defined('WP_ENV')) {
    return WP_ENV;
  } else {
    define('WP_ENV', getenv('WP_ENV') ?? 'production');
  }
  return WP_ENV;
}

function isProd() {
  return env() === 'production';
}

function isStaging() {
  return env() === 'staging';
}

function isDev() {
  return env() === 'development';
}

/**
 * Return the current, full URL.
 * 21 years of PHP and still no function on server variable capable of doing this.
 *
 * @return string
 */
function currentUrl() {
  $protocol = (isset($_SERVER['HTTPS']) ? "https" : "http");

  return "$protocol://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
}

/**
 * Return string in slugish format.
 *
 * @param string $string
 * @return string
 */
function slugify(string $string = '') {
  $string = str_replace(' ', '-', $string);
  $string = strtolower($string);
  return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
}
