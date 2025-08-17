(function($){
  function mountAztraTab(){
    if (window.__AZTRA_TAB_MOUNTED__) return; // global guard
    const $tabs = $('.elementor-panel-navigation .elementor-component-tabs');
    if(!$tabs.length) return;
    if($tabs.find('.aztra-tab').length){ window.__AZTRA_TAB_MOUNTED__ = true; return; }

    const $adv = $tabs.find('.elementor-component-tab').last();
    const $aztra = $adv.clone();
    $aztra.removeClass('elementor-active').addClass('aztra-tab').attr('data-tab','aztra')
      .find('.elementor-panel-navigation-tab-title').text('Aztra');
    $adv.after($aztra);

    $aztra.on('click', function(){
      $tabs.find('.elementor-component-tab').removeClass('elementor-active');
      $aztra.addClass('elementor-active');
      const $panel = $('.elementor-control-sections');
      $panel.find('.elementor-control-section').hide();
      $panel.find('.elementor-control-section.aztra-section').show();
      $panel.scrollTop(0);
    });

    $tabs.find('.elementor-component-tab').not('.aztra-tab').on('click', function(){
      const $panel = $('.elementor-control-sections'); $panel.find('.elementor-control-section').show();
    });

    window.__AZTRA_TAB_MOUNTED__ = true;
  }
  if (window.elementor) {
    elementor.hooks.addAction('panel/open_editor/widget', function(){
      setTimeout(()=>mountAztraTab(), 80);
    });
  }
})(jQuery);
