(function(){
  const api = (p, init={}) => fetch(AZTRA_CFG.rest + p + '&_wpnonce=' + AZTRA_CFG.nonce, init).then(r=>r.json());
  const qs = (s,el=document)=>el.querySelector(s);

  document.addEventListener('click', async (e)=>{
    const el = e.target.closest('[data-aztra-act]');
    if(!el) return;
    const act = el.dataset.aztraAct;

    if(act==='signup'){
      const u = qs('#az-su-user')?.value.trim();
      const p = qs('#az-su-pass')?.value;
      const c = qs('#az-su-code')?.value.trim();
      if(!u||!p||!c){ alert('Fill all fields'); return; }
      const res = await api('/signup?'+new URLSearchParams({username:u,password:p,code:c}), {method:'POST'});
      if(res?.ok){ alert('Account created. Please login.'); const url = document.querySelector('[data-aztra-login-url]')?.dataset.aztraLoginUrl || '/'; window.location.href = url; }
      else alert((res?.message)||'Signup failed');
    }

    if(act==='generate'){
      const form = qs('#aztra-form');
      const f = Object.fromEntries(new FormData(form).entries());
      const res = await api('/generate?', {method:'POST', body: new URLSearchParams(f)});
      if(!res?.ok){ alert('Send failed'); return; }
      renderResponse(res.data);
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
    const box = qs('#aztra-response'); if(box) box.textContent = JSON.stringify(data,null,2);
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
})();