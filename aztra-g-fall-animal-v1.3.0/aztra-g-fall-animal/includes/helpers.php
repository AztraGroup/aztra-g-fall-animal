<?php
if (!defined('ABSPATH')) exit;

if (!function_exists('aztra_log')) {
  function aztra_log($msg){
    if (defined('WP_DEBUG') && WP_DEBUG) {
      error_log( is_scalar($msg) ? $msg : print_r($msg, true) );
    }
  }
}

if (!function_exists('aztra_arr_lines')) {
  function aztra_arr_lines($s){
    $arr = array_map('trim', explode("\n", (string)$s));
    return array_values(array_filter($arr));
  }
}
