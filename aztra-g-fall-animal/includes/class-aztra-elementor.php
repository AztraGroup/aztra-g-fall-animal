<?php
if (!defined('ABSPATH')) exit;

class Aztra_Elementor {
  public static function register_widgets($widgets_manager){
    $base = AZTRA_DIR.'includes/widgets/';
    require_once $base.'trait-aztra-controls.php';
    require_once $base.'class-aztra-el-login.php';
    require_once $base.'class-aztra-el-signup.php';
    require_once $base.'class-aztra-el-builder.php';
    require_once $base.'class-aztra-el-gallery.php';

    $widgets_manager->register( new \Aztra_El_Login() );
    $widgets_manager->register( new \Aztra_El_Signup() );
    $widgets_manager->register( new \Aztra_El_Builder() );
    $widgets_manager->register( new \Aztra_El_Gallery() );
  }
}
