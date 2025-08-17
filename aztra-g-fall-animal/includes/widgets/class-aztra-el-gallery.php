<?php
if (!defined('ABSPATH')) exit;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
require_once __DIR__.'/trait-aztra-controls.php';

class Aztra_El_Gallery extends Widget_Base {
  use Aztra_Controls_Trait;

  public function get_name() { return 'aztra_gallery'; }
  public function get_title() { return 'Aztra Gallery'; }
  public function get_icon() { return 'eicon-gallery-grid'; }
  public function get_categories() { return ['general']; }
  public function get_style_depends() { return ['aztra-app','aztra-el']; }
  public function get_script_depends() { return ['aztra-app','aztra-el']; }

  protected function register_controls() {
    $this->start_controls_section('aztra_gallery_content', [
      'label'=>'Gallery — Content','tab'=>Controls_Manager::TAB_CONTENT,
    ]);
    $this->add_control('title', ['label'=>'Title','type'=>Controls_Manager::TEXT,'default'=>'Gallery']);
    $this->end_controls_section();
    $this->aztra_register_controls();
  }

  protected function render() {
    $s = $this->get_settings_for_display();
    echo '<div '.$this->aztra_wrapper_attrs($s).'><div class="aztra-bg"></div><div class="aztra-el-card">';
    echo do_shortcode('[aztra_gallery]');
    echo '</div></div>';
  }
}
