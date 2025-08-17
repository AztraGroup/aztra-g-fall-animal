<?php
if (!defined('ABSPATH')) exit;

class Aztra_CPT {
  public static function register(){
    register_post_type('aztra_request',[
      'label'=>'Aztra Requests','public'=>false,'show_ui'=>true,'show_in_menu'=>'aztra-hub',
      'menu_icon'=>'dashicons-video-alt3',
      'supports'=>['title','author','thumbnail'],
    ]);
  }

  public static function store_request($fields, $resp){
    $title = ($resp['outputFileName'] ?? $resp['conceptTitle'] ?? 'Aztra Request').' — '.current_time('mysql');
    $post_id = wp_insert_post([
      'post_type'=>'aztra_request','post_status'=>'publish',
      'post_title'=>$title,'post_author'=> get_current_user_id(),
    ]);

    update_post_meta($post_id,'aztra_fields',$fields);
    update_post_meta($post_id,'aztra_response',$resp);

    // Attach base64 images/videos if present
    if(isset($resp['binary']) && is_array($resp['binary'])){
      foreach($resp['binary'] as $key=>$bin){
        if(!empty($bin['data'])){
          self::attach_b64($post_id, $bin['data'], $bin['fileName'] ?? ($key.'.bin'), $bin['mimeType'] ?? 'application/octet-stream');
        }
      }
    }
    if(isset($resp['data'][0]['b64_json'])){
      self::attach_b64($post_id, $resp['data'][0]['b64_json'], 'image.png', 'image/png');
    }
    if(!empty($resp['webViewLink']) || !empty($resp['webContentLink'])){
      update_post_meta($post_id,'aztra_links', array_filter([$resp['webViewLink'] ?? null, $resp['webContentLink'] ?? null]));
    }
    return $post_id;
  }

  private static function attach_b64($post_id, $b64, $name, $mime){
    $bits = wp_upload_bits($name, null, base64_decode($b64));
    if(!empty($bits['error'])) return;
    $filetype = wp_check_filetype($bits['file']);
    $attach_id = wp_insert_attachment([
      'post_mime_type'=>$filetype['type'] ?: $mime,
      'post_title'=>$name,'post_status'=>'inherit','post_parent'=>$post_id
    ], $bits['file'], $post_id);
    require_once ABSPATH.'wp-admin/includes/image.php';
    wp_update_attachment_metadata($attach_id, wp_generate_attachment_metadata($attach_id, $bits['file']));
  }
}
