<?php
if (!defined('ABSPATH')) exit;

class Aztra_REST {
  public static function routes(){
    register_rest_route('aztra/v1','/generate',[
      'methods'=>'POST',
      'permission_callback'=>function(){ return is_user_logged_in() && wp_verify_nonce($_REQUEST['_wpnonce'] ?? '', 'wp_rest'); },
      'callback'=>[__CLASS__,'generate'],
    ]);
    register_rest_route('aztra/v1','/signup',[
      'methods'=>'POST',
      'permission_callback'=>'__return_true',
      'callback'=>[__CLASS__,'signup'],
    ]);
    register_rest_route('aztra/v1','/lists',[
      'methods'=>'GET',
      'permission_callback'=>'__return_true',
      'callback'=>[__CLASS__,'lists'],
    ]);
  }

  public static function settings(){
    $defaults = [
      'webhook'=>'https://n8n.srv957470.hstgr.cloud/webhook/444b9fdb-cdf0-43b5-81d8-7126b2a8f5ec',
      'access_code'=>'444b9fdb',
      'animals'=>"camel\ncapybara\nred fox\njaguar\npanda\nkoala\nkangaroo\nwolf\nbear",
      'scenarios'=>"Egypt • Giza Pyramids • low desert dunes\nBrazil • Rio • Copacabana + Sugarloaf\nIceland • black-sand beach\nUSA • Grand Canyon",
      'time_of_day'=>"sunrise\ngolden hour\nsunset\nblue hour\nnight sky",
      'weather'=>"clear\nclear with soft haze\nscattered clouds\nfoggy\nlight snow",
      'flight_style'=>"slow hover\nslow glide with gentle banking\nsmooth lateral drift",
      'camera_movement'=>"smooth forward drift with slight parallax\ngentle push-in\nlow skim over ground",
      'style'=>"cinematic photorealism\nhyperreal nature doc\ndreamlike realism",
    ];
    return wp_parse_args(get_option('aztra_g_settings',[]), $defaults);
  }

  public static function lists($req){
    $o = self::settings();
    require_once AZTRA_DIR.'includes/helpers.php';
    return [
      'animals'=> aztra_arr_lines($o['animals']),
      'scenarios'=> aztra_arr_lines($o['scenarios']),
      'time_of_day'=> aztra_arr_lines($o['time_of_day']),
      'weather'=> aztra_arr_lines($o['weather']),
      'flight_style'=> aztra_arr_lines($o['flight_style']),
      'camera_movement'=> aztra_arr_lines($o['camera_movement']),
      'style'=> aztra_arr_lines($o['style']),
    ];
  }

  public static function signup($req){
    $code = sanitize_text_field($req['code'] ?? '');
    $o = self::settings();
    if ($code !== $o['access_code']) return new WP_Error('forbidden','Invalid access code',['status'=>403]);
    $u = sanitize_user($req['username'] ?? '');
    $p = $req['password'] ?? '';
    if(!$u || !$p) return new WP_Error('bad_request','Missing fields',['status'=>400]);
    if(username_exists($u)) return new WP_Error('conflict','User exists',['status'=>409]);
    $id = wp_create_user($u,$p,$u.'@example.com');
    if(is_wp_error($id)) return $id;
    return ['ok'=>true,'user_id'=>$id];
  }

  public static function generate($req){
    $o = self::settings();
    $webhook = $o['webhook'];
    $user = wp_get_current_user()->user_login;
    $tz = new DateTimeZone('America/Sao_Paulo');
    $ts = (new DateTime('now',$tz))->format('d/m/Y H:i:s');

    $payload = [
      'animal'=> sanitize_text_field($req['animal'] ?? ''),
      'scenario'=> sanitize_text_field($req['scenario'] ?? ''),
      'time_of_day'=> sanitize_text_field($req['time_of_day'] ?? ''),
      'weather'=> sanitize_text_field($req['weather'] ?? ''),
      'flight_style'=> sanitize_text_field($req['flight_style'] ?? ''),
      'camera_movement'=> sanitize_text_field($req['camera_movement'] ?? ''),
      'style'=> sanitize_text_field($req['style'] ?? ''),
      'user'=> $user,
      'timestamp_sp'=> $ts,
    ];

    $r = wp_remote_post($webhook, [
      'headers'=>['Content-Type'=>'application/json'],
      'timeout'=>60,
      'body'=> wp_json_encode($payload),
    ]);
    if(is_wp_error($r)) return $r;
    $body = wp_remote_retrieve_body($r);
    $json = json_decode($body, true);
    if(!$json) $json = ['raw'=>$body];

    if (class_exists('Aztra_CPT')) {
      Aztra_CPT::store_request($payload, $json);
    }
    return ['ok'=>true,'data'=>$json];
  }
}
