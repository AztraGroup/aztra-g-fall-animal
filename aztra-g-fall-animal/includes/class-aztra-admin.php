<?php
if (!defined('ABSPATH')) exit;

class Aztra_Admin {
  public static function menu(){
    add_menu_page('Aztra G','Aztra G','edit_posts','aztra-hub',[__CLASS__,'hub'],'dashicons-art',56);
    add_submenu_page('aztra-hub','Dashboard','Dashboard','edit_posts','aztra-hub',[__CLASS__,'hub']);
    add_submenu_page('aztra-hub','Settings','Settings','manage_options','aztra-settings',[__CLASS__,'settings']);
    add_submenu_page('aztra-hub','Requests','Requests','edit_posts','edit.php?post_type=aztra_request');
  }

  public static function hub(){
    $count = wp_count_posts('aztra_request');
    ?>
    <div class="wrap">
      <h1>Aztra G — Dashboard</h1>
      <p>Use shortcodes or Elementor widgets to build pages.</p>
      <ul>
        <li><b>Total Requests:</b> <?php echo intval($count->publish ?? 0); ?></li>
        <li><a href="<?php echo admin_url('edit.php?post_type=aztra_request'); ?>">View all</a></li>
        <li><a href="<?php echo admin_url('admin.php?page=aztra-settings'); ?>">Settings</a></li>
      </ul>
      <p>Shortcodes: <code>[aztra_login]</code> <code>[aztra_signup]</code> <code>[aztra_builder]</code> <code>[aztra_gallery]</code></p>
    </div>
    <?php
  }

  public static function settings(){
    if(isset($_POST['aztra_save']) && current_user_can('manage_options')){
      check_admin_referer('aztra_save');
      $fields = ['webhook','access_code','animals','scenarios','time_of_day','weather','flight_style','camera_movement','style'];
      $opts = [];
      foreach($fields as $f){ $opts[$f] = wp_unslash($_POST[$f] ?? ''); }
      update_option('aztra_g_settings', $opts, false);
      echo '<div class="updated"><p>Saved.</p></div>';
    }
    $o = get_option('aztra_g_settings',[]);
    ?>
    <div class="wrap"><h1>Aztra G — Settings</h1>
      <form method="post"><?php wp_nonce_field('aztra_save'); ?>
        <table class="form-table">
          <tr><th>Webhook URL (n8n)</th>
            <td><input name="webhook" type="url" style="width:520px" value="<?php echo esc_attr($o['webhook'] ?? ''); ?>"></td></tr>
          <tr><th>Access code (signup)</th>
            <td><input name="access_code" type="text" value="<?php echo esc_attr($o['access_code'] ?? ''); ?>"></td></tr>
          <tr><th>Animals (1 per line)</th>
            <td><textarea name="animals" rows="6" style="width:520px"><?php echo esc_textarea($o['animals'] ?? ''); ?></textarea></td></tr>
          <tr><th>Scenarios</th>
            <td><textarea name="scenarios" rows="6" style="width:520px"><?php echo esc_textarea($o['scenarios'] ?? ''); ?></textarea></td></tr>
          <tr><th>Time of day</th>
            <td><textarea name="time_of_day" rows="4" style="width:520px"><?php echo esc_textarea($o['time_of_day'] ?? ''); ?></textarea></td></tr>
          <tr><th>Weather</th>
            <td><textarea name="weather" rows="4" style="width:520px"><?php echo esc_textarea($o['weather'] ?? ''); ?></textarea></td></tr>
          <tr><th>Flight style</th>
            <td><textarea name="flight_style" rows="4" style="width:520px"><?php echo esc_textarea($o['flight_style'] ?? ''); ?></textarea></td></tr>
          <tr><th>Camera movement</th>
            <td><textarea name="camera_movement" rows="4" style="width:520px"><?php echo esc_textarea($o['camera_movement'] ?? ''); ?></textarea></td></tr>
          <tr><th>Style</th>
            <td><textarea name="style" rows="4" style="width:520px"><?php echo esc_textarea($o['style'] ?? ''); ?></textarea></td></tr>
        </table>
        <p><button class="button-primary" name="aztra_save" value="1">Save</button></p>
      </form>
    </div>
    <?php
  }
}
