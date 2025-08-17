<?php
/**
 * Plugin Name: Aztra G Fall Animal
 * Description: Hub multiuser with Elementor widgets, shortcodes and secure proxy to n8n workflow (Animal Flight). Includes Elementor "Aztra" top tab with background animations.
 * Version: 1.2.0
 * Author: Aztragroup
 * Text Domain: aztra
 */

if (!defined('ABSPATH')) exit;

// ---- Requirements ----
if (version_compare(PHP_VERSION, '7.4', '<')) {
  add_action('admin_notices', function(){
    echo '<div class="notice notice-error"><p><b>Aztra G Fall Animal:</b> requires PHP 7.4 or higher.</p></div>';
  });
  return;
}

define('AZTRA_VER', '1.2.0');
define('AZTRA_DIR', plugin_dir_path(__FILE__));
define('AZTRA_URL', plugin_dir_url(__FILE__));

// safe require helper
if (!function_exists('aztra_require')) {
  function aztra_require($rel){
    $path = AZTRA_DIR . ltrim($rel, '/');
    if (file_exists($path)) { require_once $path; return true; }
    add_action('admin_notices', function() use ($rel){
      echo '<div class="notice notice-warning"><p><b>Aztra:</b> missing file: <code>'.esc_html($rel).'</code></p></div>';
    });
    return false;
  }
}

// includes
aztra_require('includes/helpers.php');
aztra_require('includes/activator.php');
aztra_require('includes/class-aztra-cpt.php');
aztra_require('includes/class-aztra-admin.php');
aztra_require('includes/class-aztra-rest.php');
aztra_require('includes/class-aztra-shortcodes.php');
aztra_require('includes/class-aztra-elementor.php');

class AztraG_Fall_Animal_Plugin {
  public function __construct(){
    if (class_exists('Aztra_Activator')) {
      register_activation_hook(__FILE__, ['Aztra_Activator','activate']);
    }
    add_action('init', function(){
      if (class_exists('Aztra_CPT')) Aztra_CPT::register();
      if (class_exists('Aztra_Shortcodes')) Aztra_Shortcodes::register();
    });
    add_action('admin_menu', function(){
      if (class_exists('Aztra_Admin')) Aztra_Admin::menu();
    });
    add_action('rest_api_init', function(){
      if (class_exists('Aztra_REST')) Aztra_REST::routes();
    });
    add_action('plugins_loaded', function(){
      if ( did_action('elementor/loaded') && class_exists('Aztra_Elementor') ) {
        add_action('elementor/widgets/register', ['Aztra_Elementor','register_widgets']);
      }
    });
    add_action('wp_enqueue_scripts', [$this,'register_assets']);
    add_action('elementor/editor/after_enqueue_scripts', [$this,'editor_assets']);
  }

  public function register_assets(){
    wp_register_style('aztra-app', AZTRA_URL.'assets/app.css', [], AZTRA_VER);
    wp_register_style('aztra-el', AZTRA_URL.'assets/elementor.css', [], AZTRA_VER);
    wp_register_script('aztra-app', AZTRA_URL.'assets/app.js', ['jquery'], AZTRA_VER, true);
    wp_register_script('aztra-el', AZTRA_URL.'assets/elementor.js', [], AZTRA_VER, true);
    wp_localize_script('aztra-app','AZTRA_CFG',[
      'rest'=> esc_url_raw( rest_url('aztra/v1') ),
      'nonce'=> wp_create_nonce('wp_rest'),
      'tz'=> 'America/Sao_Paulo',
      'chat_url'=> esc_url_raw( get_permalink( get_page_by_title('Aztra — Chat') ) ),
    ]);
  }

  public function editor_assets(){
    wp_enqueue_script('aztra-editor', AZTRA_URL.'assets/editor.js', ['jquery','elementor-editor'], AZTRA_VER, true);
  }
}
new AztraG_Fall_Animal_Plugin();
