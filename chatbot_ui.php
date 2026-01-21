<?php
session_start();
if (!isset($_SESSION['user'])) { header('Location: login.php'); exit; }
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Career Assistant</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light">
  <nav class="navbar navbar-light bg-white shadow-sm mb-3"><div class="container"><a class="navbar-brand" href="dashboard.php">Hackthon2026</a></div></nav>
  <main class="container">
    <h4>Career Assistant</h4>
    <div id="chat" class="border rounded p-3 mb-3" style="min-height:200px; background:#fff; overflow:auto;"></div>
    <form id="frm" class="d-flex gap-2">
      <input id="msg" class="form-control" placeholder="Ask about resume, interview, applications..." required>
      <button class="btn btn-primary">Send</button>
    </form>
  </main>

  <script>
    const chat = document.getElementById('chat'), frm = document.getElementById('frm'), msg = document.getElementById('msg');
    function add(role, text){
      const p = document.createElement('div'); p.className = role==='bot'?'mb-2 text-start':'mb-2 text-end';
      p.innerHTML = `<div class="p-2 ${role==='bot'?'bg-light':'bg-primary text-white'} rounded">${escapeHtml(text)}</div>`;
      chat.appendChild(p); chat.scrollTop = chat.scrollHeight;
    }
    frm.addEventListener('submit', async (e)=>{
      e.preventDefault();
      const text = msg.value.trim(); if(!text) return;
      add('user', text); msg.value='';
      try {
        const res = await fetch('api/chatbot.php', {
          method:'POST', headers: {'Content-Type':'application/json'}, body: JSON.stringify({ message: text })
        });
        const j = await res.json();
        add('bot', j.reply || 'No reply');
      } catch (err) { add('bot', 'Service error'); console.error(err); }
    });
    function escapeHtml(s){ return String(s||'').replace(/[&<>"']/g,m=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m])); }
  </script>
</body>
</html>
