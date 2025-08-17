<?php
if (!defined('ABSPATH')) exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

trait Aztra_Controls_Trait {
  protected function aztra_register_controls() {
    // STYLE (typography & colors)
    $this->start_controls_section('aztra_style_section', [
      'label'   => 'Aztra Style',
      'tab'     => Controls_Manager::TAB_STYLE,
      'classes' => 'aztra-section',
    ]);
    $this->add_control('aztra_text_color', [
      'label'=>'Text Color','type'=>Controls_Manager::COLOR,
      'selectors'=>['{{WRAPPER}} .aztra-el-card'=>'color: {{VALUE}};','{{WRAPPER}} .az-card'=>'--az-card-color: {{VALUE}};'],
    ]);
    $this->add_group_control(Group_Control_Typography::get_type(), [
      'name'=>'aztra_heading_typo', 'label'=>'Heading Typography',
      'selector'=>'{{WRAPPER}} .aztra-el-card h1, {{WRAPPER}} .aztra-el-card h2, {{WRAPPER}} .aztra-el-card h3, {{WRAPPER}} .aztra-el-card h4',
    ]);
    $this->add_group_control(Group_Control_Typography::get_type(), [
      'name'=>'aztra_body_typo', 'label'=>'Body Typography',
      'selector'=>'{{WRAPPER}} .aztra-el-card, {{WRAPPER}} .aztra-el-card input, {{WRAPPER}} .aztra-el-card select, {{WRAPPER}} .aztra-el-card button',
    ]);
    $this->add_control('aztra_card_background', [
      'label'=>'Card Background',
      'type'=>Controls_Manager::COLOR,
      'selectors'=>['{{WRAPPER}} .aztra-el-card'=>'background: {{VALUE}};','{{WRAPPER}} .az-card'=>'--az-card-bg: {{VALUE}};'],
      'description'=>'Use rgba with alpha 0 to make it transparent.'
    ]);
    $this->add_control('aztra_btn_bg', [
      'label'=>'Primary Button BG (gradient or color)',
      'type'=>Controls_Manager::TEXT,
      'default'=>'linear-gradient(135deg,#7c3aed,#06b6d4)',
      'selectors'=>['{{WRAPPER}} .aztra-el-card .az-btn.az-primary'=>'background: {{VALUE}};','{{WRAPPER}} .az-card'=>'--az-btn-bg: {{VALUE}};'],
    ]);
    $this->end_controls_section();

    // LAYOUT
    $this->start_controls_section('aztra_layout', [
      'label'   => 'Aztra Layout',
      'tab'     => Controls_Manager::TAB_ADVANCED,
      'classes' => 'aztra-section',
    ]);
    $this->add_responsive_control('aztra_max_width', [
      'label' => 'Max Width (px)',
      'type'  => Controls_Manager::SLIDER,
      'range' => [ 'px' => [ 'min'=>280,'max'=>1600 ] ],
      'selectors' => [ '{{WRAPPER}} .aztra-el-wrap' => 'max-width: {{SIZE}}{{UNIT}};' ],
    ]);
    $this->add_responsive_control('aztra_padding', [
      'label' => 'Card Padding',
      'type'  => Controls_Manager::DIMENSIONS,
      'size_units' => ['px','em','%'],
      'selectors' => [ '{{WRAPPER}} .aztra-el-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
    ]);
    $this->add_group_control(Group_Control_Border::get_type(), [
      'name' => 'aztra_border',
      'selector' => '{{WRAPPER}} .aztra-el-card',
    ]);
    $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
      'name' => 'aztra_shadow',
      'selector' => '{{WRAPPER}} .aztra-el-card',
    ]);
    $this->add_control('aztra_radius', [
      'label'=>'Border Radius',
      'type'=>Controls_Manager::SLIDER,
      'range'=>['px'=>['min'=>0,'max'=>48]],
      'selectors'=>['{{WRAPPER}} .aztra-el-card'=>'border-radius: {{SIZE}}{{UNIT}};'],
    ]);
    $this->end_controls_section();

    // CUSTOMIZATION (BG animations + overlay)
    $this->start_controls_section('aztra_custom', [
      'label'   => 'Aztra Customization',
      'tab'     => Controls_Manager::TAB_ADVANCED,
      'classes' => 'aztra-section',
    ]);
    $this->add_control('aztra_bg_anim', [
      'label' => 'Background Animation',
      'type'  => Controls_Manager::SELECT,
      'default' => 'none',
      'options' => [
        'none'      => 'None',
        'gradient'  => 'Animated Gradient',
        'particles' => 'Particles',
        'noise'     => 'Noise Film',
        'video'     => 'Video Background',
      ],
    ]);
    $this->add_control('aztra_enable_overlay', [
      'label'=>'Enable Overlay','type'=>Controls_Manager::SWITCHER,'default'=>'',
      'description'=>'Adds a color overlay above the background animation.',
    ]);
    $this->add_control('aztra_overlay', [
      'label'=>'Overlay Color',
      'type'=>Controls_Manager::COLOR,
      'default'=>'rgba(0,0,0,0.35)',
    ]);
    $this->add_control('aztra_grad_color_1', [
      'label'=>'Gradient Color 1',
      'type'=>Controls_Manager::COLOR,
      'default'=>'#7c3aed','condition'=>['aztra_bg_anim'=>'gradient'],
    ]);
    $this->add_control('aztra_grad_color_2', [
      'label'=>'Gradient Color 2',
      'type'=>Controls_Manager::COLOR,
      'default'=>'#06b6d4','condition'=>['aztra_bg_anim'=>'gradient'],
    ]);
    $this->add_control('aztra_grad_speed', [
      'label'=>'Gradient Speed (s)',
      'type'=>Controls_Manager::NUMBER,
      'default'=>18, 'min'=>2,'max'=>60,'condition'=>['aztra_bg_anim'=>'gradient'],
    ]);
    $this->add_control('aztra_particles_density', [
      'label'=>'Particles Density',
      'type'=>Controls_Manager::SLIDER,
      'range'=>['px'=>['min'=>20,'max'=>300]],
      'default'=>['size'=>120],'condition'=>['aztra_bg_anim'=>'particles'],
    ]);
    $this->add_control('aztra_particles_speed', [
      'label'=>'Particles Speed',
      'type'=>Controls_Manager::SLIDER,
      'range'=>['px'=>['min'=>0,'max'=>3,'step'=>0.1]],
      'default'=>['size'=>0.6],'condition'=>['aztra_bg_anim'=>'particles'],
    ]);
    $this->add_control('aztra_noise_strength', [
      'label'=>'Noise Strength',
      'type'=>Controls_Manager::SLIDER,
      'range'=>['px'=>['min'=>0,'max'=>1,'step'=>0.05]],
      'default'=>['size'=>0.15],'condition'=>['aztra_bg_anim'=>'noise'],
    ]);
    $this->add_control('aztra_video_url', [
      'label'=>'Video URL (mp4/webm)',
      'type'=>Controls_Manager::TEXT,'label_block'=>true,'placeholder'=>'https://…/bg.mp4',
      'condition'=>['aztra_bg_anim'=>'video'],
    ]);
    $this->end_controls_section();
  }

  protected function aztra_wrapper_attrs( $settings ){
    $attrs = [
      'class' => 'aztra-el-wrap' . ( !empty($settings['aztra_enable_overlay']) ? ' has-overlay' : '' ),
      'style' => '--aztra-overlay: '.( $settings['aztra_overlay'] ?? 'rgba(0,0,0,0.35)' ).';',
      'data-anim' => $settings['aztra_bg_anim'] ?? 'none',
      'data-grad-1' => $settings['aztra_grad_color_1'] ?? '',
      'data-grad-2' => $settings['aztra_grad_color_2'] ?? '',
      'data-grad-speed' => $settings['aztra_grad_speed'] ?? 18,
      'data-particles-density' => isset($settings['aztra_particles_density']['size']) ? $settings['aztra_particles_density']['size'] : 120,
      'data-particles-speed'   => isset($settings['aztra_particles_speed']['size']) ? $settings['aztra_particles_speed']['size'] : 0.6,
      'data-noise-strength'    => isset($settings['aztra_noise_strength']['size']) ? $settings['aztra_noise_strength']['size'] : 0.15,
      'data-video' => $settings['aztra_video_url'] ?? '',
    ];
    $html = '';
    foreach($attrs as $k=>$v){ if($v===null) $v=''; $html .= ' '.esc_attr($k).'="'.esc_attr($v).'"'; }
    return $html;
  }
}
