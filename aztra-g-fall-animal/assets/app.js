(function(){
  const api = (path, init={}) => {
    const url = new URL(path, AZTRA_CFG.rest);
    url.searchParams.set('_wpnonce', AZTRA_CFG.nonce);
    return fetch(url, init).then(r=>r.json());
  };
  const qs = (s,el=document)=>el.querySelector(s);

  const setTheme = (mode)=>{
    document.body.classList.toggle('az-theme-dark', mode==='dark');
    localStorage.setItem('aztra_theme', mode);
  };
  const toggleTheme = ()=>setTheme(document.body.classList.contains('az-theme-dark')?'light':'dark');

  const setFont = (size)=>{
    document.documentElement.style.setProperty('--az-font-size', size+'px');
    localStorage.setItem('aztra_font', size);
  };
  const changeFont = (d)=>{
    const cur = parseFloat(localStorage.getItem('aztra_font')||'16');
    const next = Math.min(Math.max(cur + d, 12), 24);
    setFont(next);
  };
  const setContrast = (c)=>{
    document.documentElement.style.setProperty('--az-contrast', c);
    localStorage.setItem('aztra_contrast', c);
  };
  const toggleContrast = ()=>{
    const cur = localStorage.getItem('aztra_contrast')||'1';
    setContrast(cur==='1' ? '2' : '1');
  };

  setTheme(localStorage.getItem('aztra_theme')||'light');
  setFont(parseFloat(localStorage.getItem('aztra_font')||'16'));
  setContrast(localStorage.getItem('aztra_contrast')||'1');

  window.Aztra = {api,setTheme,toggleTheme,changeFont,toggleContrast};

  const previewEl = qs('#aztra-preview');
  if(previewEl){
    const storedPreview = localStorage.getItem('aztra_preview');
    if(storedPreview) previewEl.textContent = storedPreview;
  }

  document.addEventListener('click', async (e)=>{
    const el = e.target.closest('[data-aztra-act]');
    if(!el) return;
    const act = el.dataset.aztraAct;

    if(act==='signup'){
      const u = qs('#az-su-user')?.value.trim();
      const p = qs('#az-su-pass')?.value;
      const c = qs('#az-su-code')?.value.trim();
      if(!u||!p||!c){ alert('Fill all fields'); return; }
      const res = await api('/signup', {method:'POST', body: new URLSearchParams({username:u,password:p,code:c})});
      if(res?.ok){ alert('Account created. Please login.'); const url = document.querySelector('[data-aztra-login-url]')?.dataset.aztraLoginUrl || '/'; window.location.href = url; }
      else alert((res?.message)||'Signup failed');
    }

    if(act==='generate'){
      const form = qs('#aztra-form');
      const f = Object.fromEntries(new FormData(form).entries());
      const res = await api('/generate', {method:'POST', body: new URLSearchParams(f)});
      if(!res?.ok){ alert('Send failed'); return; }
      renderResponse(res.data);
    }

    if(act==='toggle-theme') toggleTheme();
    if(act==='font-inc') changeFont(2);
    if(act==='font-dec') changeFont(-2);
    if(act==='toggle-contrast') toggleContrast();

    if(act==='open-save-model') openWebhookModal();

    if(act==='close-modal') qs('.az-overlay')?.remove();

    if(act==='save-webhook'){
      const url = qs('#aztra-webhook')?.value.trim();
      if(url){
        localStorage.setItem('aztra_webhook', url);
        await api('/webhook',{method:'POST',body:new URLSearchParams({url})});
      }
      window.location.href = AZTRA_CFG.chat_url || '/';
    }
  });

  document.addEventListener('change', e=>{
    const el = e.target;
    if(el.dataset.aztraAct==='set-lang'){
      const lang = el.value;
      const url = new URL(window.location.href);
      url.searchParams.set('lang', lang);
      window.location.href = url.toString();
    }
  });

  async function loadLists(){
    const wrap = qs('#aztra-form'); if(!wrap) return;
    const lists = await api('/lists');
    const fill = (id, arr)=>{ const s=qs('#'+id); if(s) s.innerHTML = arr.map(v=>`<option>${v}</option>`).join(''); };
    fill('animal',lists.animals); fill('scenario',lists.scenarios);
    fill('time_of_day',lists.time_of_day); fill('weather',lists.weather);
    fill('flight_style',lists.flight_style); fill('camera_movement',lists.camera_movement);
    fill('style',lists.style);
  }
  loadLists();

  function renderResponse(data){
    const formatted = JSON.stringify(data, null, 2);
    const box = qs('#aztra-response'); if(box) box.textContent = formatted;
    localStorage.setItem('aztra_preview', formatted);
    const assets = qs('#aztra-assets'); if(!assets) return;
    assets.innerHTML = '';
    const addAsset = (h)=>{ const d=document.createElement('div'); d.className='az-asset'; d.innerHTML=h; assets.appendChild(d); };

    if(data?.binary){
      Object.entries(data.binary).forEach(([k,bin])=>{
        if(bin?.data){
          const url = toBlobUrl(bin.data, bin.mimeType||'application/octet-stream');
          if((bin.mimeType||'').startsWith('image/')) addAsset(`<img src="${url}" class="az-thumb" alt="${bin.fileName||k}"><details><summary>Base64</summary><textarea>${bin.data}</textarea></details>`);
          else if((bin.mimeType||'').startsWith('video/')) addAsset(`<video controls class="az-thumb" src="${url}"></video><details><summary>Base64</summary><textarea>${bin.data}</textarea></details>`);
          else addAsset(`<a class="az-btn" download="${bin.fileName||k}" href="${url}">Download ${bin.fileName||k}</a><details><summary>Base64</summary><textarea>${bin.data}</textarea></details>`);
        }
      });
    }
    if(data?.data && Array.isArray(data.data) && data.data[0]?.b64_json){
      const b64 = data.data[0].b64_json;
      const url = toBlobUrl(b64,'image/png');
      addAsset(`<img src="${url}" class="az-thumb" alt="image"><details><summary>Base64</summary><textarea>${b64}</textarea></details>`);
    }
    const link = data.webViewLink || data.webContentLink || data.link || data.url;
    if(link){ addAsset(`<a class="az-btn" target="_blank" rel="noopener" href="${link}">Open Link</a>`); }
  }

  function toBlobUrl(b64, mime){
    try{
      const bin = atob(b64); const arr = new Uint8Array(bin.length);
      for(let i=0;i<bin.length;i++) arr[i] = bin.charCodeAt(i);
      return URL.createObjectURL(new Blob([arr], {type:mime}));
    }catch(e){ return null; }
  }

  function openWebhookModal(){
    if(qs('.az-overlay')) return;
    const wrap = document.createElement('div');
    wrap.className = 'az-overlay';
    wrap.innerHTML = `
      <div class="az-modal">
        <h3>Atualize seu WebHook para modo de produção</h3>
        <div class="az-field"><label>Webhook URL</label><input id="aztra-webhook" type="url"></div>
        <div class="az-row">
          <button class="az-btn az-primary" data-aztra-act="save-webhook">Salvar e abrir conversa</button>
          <button class="az-btn" data-aztra-act="close-modal">Cancelar</button>
        </div>
      </div>`;
    document.body.appendChild(wrap);
    const input = qs('#aztra-webhook');
    input.value = localStorage.getItem('aztra_webhook') || '';
    input.focus();
  }
})();
