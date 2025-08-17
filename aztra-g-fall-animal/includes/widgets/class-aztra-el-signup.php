<?php
if (!defined('ABSPATH')) exit;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
require_once __DIR__.'/trait-aztra-controls.php';

class Aztra_El_Signup extends Widget_Base {
  use Aztra_Controls_Trait;

  public function get_name() { return 'aztra_signup'; }
  public function get_title() { return 'Aztra Signup'; }
  public function get_icon() { return 'eicon-user-circle-o'; }
  public function get_categories() { return ['general']; }
  public function get_style_depends() { return ['aztra-app','aztra-el']; }
  public function get_script_depends() { return ['aztra-app','aztra-el']; }

  protected function register_controls() {
    $this->start_controls_section('aztra_signup_content', [
      'label'=>'Signup — Content', 'tab'=>Controls_Manager::TAB_CONTENT,
    ]);
    $this->add_control('title', ['label'=>'Title','type'=>Controls_Manager::TEXT,'default'=>'Create account','label_block'=>true]);
    $this->add_control('subtitle', ['label'=>'Subtitle','type'=>Controls_Manager::TEXTAREA,'default'=>'Use your access code to join.','rows'=>2]);
    $this->add_control('label_username', ['label'=>'Label: Username','type'=>Controls_Manager::TEXT,'default'=>'Username']);
    $this->add_control('label_password', ['label'=>'Label: Password','type'=>Controls_Manager::TEXT,'default'=>'Password']);
    $this->add_control('label_access', ['label'=>'Label: Access code','type'=>Controls_Manager::TEXT,'default'=>'Access code']);
    $this->add_control('placeholder_access', ['label'=>'Placeholder: Access code','type'=>Controls_Manager::TEXT,'default'=>'Enter your access code']);
    $this->add_control('button_text', ['label'=>'Button text','type'=>Controls_Manager::TEXT,'default'=>'Create account']);
    $this->end_controls_section();
    $this->aztra_register_controls();
  }

  protected function render() {
    $s = $this->get_settings_for_display();
    $sc = sprintf(
      '[aztra_signup title="%s" subtitle="%s" label_username="%s" label_password="%s" label_access="%s" placeholder_access="%s" button="%s"]',
      esc_attr($s['title']), esc_attr($s['subtitle']), esc_attr($s['label_username']),
      esc_attr($s['label_password']), esc_attr($s['label_access']), esc_attr($s['placeholder_access']), esc_attr($s['button_text'])
    );
    echo '<div '.$this->aztra_wrapper_attrs($s).'><div class="aztra-bg"></div><div class="aztra-el-card">';
    echo do_shortcode($sc);
    echo '</div></div>';
  }
}
