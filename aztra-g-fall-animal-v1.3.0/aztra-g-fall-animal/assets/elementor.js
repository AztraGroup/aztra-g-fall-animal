(function(){
  function init(){
    document.querySelectorAll('.aztra-el-wrap').forEach(wrap=>{
      const anim = wrap.dataset.anim || 'none';
      if(anim === 'gradient'){
        const g1 = wrap.dataset.grad1 || '#7c3aed';
        const g2 = wrap.dataset.grad2 || '#06b6d4';
        const gs = wrap.dataset.gradSpeed || 18;
        wrap.style.setProperty('--g1', g1);
        wrap.style.setProperty('--g2', g2);
        wrap.style.setProperty('--gs', gs + 's');
      }
      if(anim === 'particles'){ mountParticles(wrap); }
      if(anim === 'video'){ mountVideo(wrap, wrap.dataset.video); }
    });
  }
  function mountVideo(wrap, url){
    if(!url) return;
    const bg = wrap.querySelector('.aztra-bg'); if(!bg) return;
    const v = document.createElement('video');
    v.src = url; v.autoplay = true; v.loop = true; v.muted = true; v.playsInline = true;
    Object.assign(v.style,{position:'absolute',inset:'0',width:'100%',height:'100%',objectFit:'cover'});
    bg.innerHTML=''; bg.appendChild(v);
  }
  function mountParticles(wrap){
    const bg = wrap.querySelector('.aztra-bg'); if(!bg) return;
    const density = parseInt(wrap.dataset.particlesDensity || '120', 10);
    const speed   = parseFloat(wrap.dataset.particlesSpeed || '0.6');
    const c = document.createElement('canvas'); bg.innerHTML=''; bg.appendChild(c);
    const ctx = c.getContext('2d'); let w,h,points=[];
    function resize(){ w=bg.clientWidth; h=bg.clientHeight; c.width=w; c.height=h; makePoints(); }
    function makePoints(){
      points = Array.from({length: density}, ()=>({ x: Math.random()*w, y: Math.random()*h, vx:(Math.random()-0.5)*speed, vy:(Math.random()-0.5)*speed, r: Math.random()*1.8+0.2, a: Math.random()*0.4+0.2 }));
    }
    function tick(){
      ctx.clearRect(0,0,w,h); ctx.fillStyle='#ffffff';
      points.forEach(p=>{ p.x+=p.vx; p.y+=p.vy; if(p.x<0||p.x>w) p.vx*=-1; if(p.y<0||p.y>h) p.vy*=-1; ctx.globalAlpha=p.a; ctx.beginPath(); ctx.arc(p.x,p.y,p.r,0,Math.PI*2); ctx.fill(); });
      requestAnimationFrame(tick);
    }
    new ResizeObserver(resize).observe(bg);
    resize(); tick();
  }
  if(document.readyState !== 'loading') init();
  else document.addEventListener('DOMContentLoaded', init);
  if (window.elementorFrontend) {
    window.elementorFrontend.hooks.addAction('frontend/element_ready/global', init);
  }
})();