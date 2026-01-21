<?php
session_start();
if (!isset($_SESSION['user'])) { header('Location: login.php'); exit; }
$email = $_SESSION['user'];
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Notifications</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light">
  <nav class="navbar navbar-light bg-white shadow-sm mb-3"><div class="container"><a class="navbar-brand" href="dashboard.php">Hackthon2026</a></div></nav>
  <main class="container">
    <h4>Notifications</h4>
    <div id="notes" class="mt-3"></div>
  </main>
  <script>
    async function load(){
      const res = await fetch('api/notifications.php?action=list&email=' + encodeURIComponent(<?= json_encode($email) ?>));
      const data = await res.json();
      const el = document.getElementById('notes');
      if (!data.length){ el.innerHTML = '<div class="text-muted">No notifications.</div>'; return; }
      el.innerHTML = '<ul class="list-group"></ul>';
      const ul = el.querySelector('ul');
      data.forEach(n=>{
        const li = document.createElement('li');
        li.className = 'list-group-item';
        li.innerHTML = `<div><strong>${escapeHtml(n.title)}</strong><div class="small text-muted">${escapeHtml(n.message)}</div><div class="small text-muted">At: ${escapeHtml(n.created_at)}</div></div>`;
        ul.appendChild(li);
      });
    }
    function escapeHtml(s){ return String(s||'').replace(/[&<>"']/g,m=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m])); }
    load();
  </script>
</body>
</html>
