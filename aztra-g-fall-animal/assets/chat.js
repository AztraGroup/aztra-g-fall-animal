(function(){
  const {api,toggleTheme}=window.Aztra||{};
  if(!api) return;
  const qs = s=>document.querySelector(s);
  const log = qs('#aztra-chat-log');
  const fileInput = qs('#aztra-chat-file');
  const msgInput = qs('#aztra-chat-message');

  function esc(s){return s.replace(/[&<>]/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;'}[c]));}
  function renderFile(f){
    const url = esc(f.url);
    if(f.type && f.type.startsWith('image/')) return `<img src="${url}" class="az-file" alt="file">`;
    if(f.type && f.type.startsWith('video/')) return `<video controls class="az-file" src="${url}"></video>`;
    return `<a class="az-file" href="${url}" target="_blank" rel="noopener">${url}</a>`;
  }
  function addMessage(m){
    const div=document.createElement('div');
    div.className='az-chat-msg';
    let html = esc(m.text||'');
    if(m.files){ html += m.files.map(renderFile).join(''); }
    div.innerHTML = `<div class="az-bubble">${html}</div>`;
    log.appendChild(div); log.scrollTop=log.scrollHeight;
  }
  async function load(){
    const res = await api('/chat/list');
    if(res&&Array.isArray(res.messages)) res.messages.forEach(addMessage);
  }
  load();

  document.addEventListener('click', async e=>{
    const el = e.target.closest('[data-aztra-act]');
    if(!el) return;
    const act = el.dataset.aztraAct;
    if(act==='send-chat'){
      const text = msgInput.value.trim();
      if(!text && !fileInput.files.length) return;
      const fd = new FormData();
      fd.append('message', text);
      for(const f of fileInput.files){ fd.append('files[]', f); }
      const res = await api('/chat/send',{method:'POST', body:fd});
      if(res&&res.message){ addMessage(res.message); msgInput.value=''; fileInput.value=''; }
    }
    if(act==='toggle-theme') toggleTheme();
  });
})();
