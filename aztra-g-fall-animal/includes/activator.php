<?php
if (!defined('ABSPATH')) exit;

class Aztra_Activator {
  public static function activate(){
    // default settings
    if (!get_option('aztra_g_settings')) {
      update_option('aztra_g_settings',[
        'webhook'=>'https://n8n.srv957470.hstgr.cloud/webhook/444b9fdb-cdf0-43b5-81d8-7126b2a8f5ec',
        'access_code'=>'444b9fdb',
        'animals'=>"camel\ncapybara\nred fox\njaguar\npanda\nkoala\nkangaroo\nwolf\nbear",
        'scenarios'=>"Egypt • Giza Pyramids • low desert dunes\nBrazil • Rio • Copacabana + Sugarloaf\nIceland • black-sand beach\nUSA • Grand Canyon",
        'time_of_day'=>"sunrise\ngolden hour\nsunset\nblue hour\nnight sky",
        'weather'=>"clear\nclear with soft haze\nscattered clouds\nfoggy\nlight snow",
        'flight_style'=>"slow hover\nslow glide with gentle banking\nsmooth lateral drift",
        'camera_movement'=>"smooth forward drift with slight parallax\ngentle push-in\nlow skim over ground",
        'style'=>"cinematic photorealism\nhyperreal nature doc\ndreamlike realism",
        'company_name'=>get_bloginfo('name'),
        'contact_email'=>get_bloginfo('admin_email'),
        'privacy_template'=>"This Privacy Policy belongs to {company_name}. Contact us at {contact_email}.",
        'terms_template'=>"These Terms of Use govern the site operated by {company_name}. For support: {contact_email}.",
      ], false);
    }
    // create pages with shortcodes and store their IDs
    $pages = [
      'Aztra — Home'        => '[aztra_home]',
      'Aztra — Chat'        => '[aztra_chat]',
      'Aztra — Builder'     => '[aztra_builder]',
      'Gere Seu Agente'     => '[aztra_agent]',
      'Aztra — Galeria'     => '[aztra_gallery]',
      'Aztra — Tutoriais'   => '[aztra_tutorials]',
      'Política de Privacidade' => '[aztra_privacy]',
      'Termos de Uso'           => '[aztra_terms]',
      'Aztra — Commands'    => '[aztra_commands]',
    ];

    $ids = [];
    foreach($pages as $title=>$sc){
      $page = get_page_by_title($title);
      if(!$page){
        $page_id = wp_insert_post([
          'post_title'   => $title,
          'post_status'  => 'publish',
          'post_type'    => 'page',
          'post_content' => $sc,
        ]);
      } else {
        $page_id = $page->ID;
      }
      $ids[$title] = (int)$page_id;
    }
    update_option('aztra_page_ids', $ids, false);
  }
}
