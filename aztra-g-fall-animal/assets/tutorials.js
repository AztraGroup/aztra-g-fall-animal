(function(){
  document.addEventListener('DOMContentLoaded', function(){
    const buttons = document.querySelectorAll('[data-aztra-tab]');
    const tabs = document.querySelectorAll('.az-tab');
    function show(id){
      tabs.forEach(t=>t.classList.toggle('active', t.id===id));
      buttons.forEach(b=>b.classList.toggle('active', b.getAttribute('data-aztra-tab')===id));
    }
    buttons.forEach(btn=>{
      btn.addEventListener('click', function(){
        show(this.getAttribute('data-aztra-tab'));
      });
    });
    if(buttons.length){ show(buttons[0].getAttribute('data-aztra-tab')); }
    document.querySelectorAll('pre code').forEach(block=>{
      if(window.hljs){ window.hljs.highlightElement(block); }
    });
  });
})();
