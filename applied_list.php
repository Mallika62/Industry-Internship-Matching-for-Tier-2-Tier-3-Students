<?php
session_start();
if (!isset($_SESSION['user'])) { header('Location: login.php'); exit; }
$email = $_SESSION['user'];
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>My Applications</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light">
  <nav class="navbar navbar-light bg-white shadow-sm mb-3"><div class="container"><a class="navbar-brand" href="dashboard.php">Hackthon2026</a></div></nav>
  <main class="container">
    <h4>My Applications</h4>
    <div id="apps" class="mt-3"></div>
  </main>
  <script>
    async function load() {
      const res = await fetch('api/applications.php?student_email=' + encodeURIComponent(<?= json_encode($email) ?>));
      const data = await res.json();
      const el = document.getElementById('apps');
      if (!data.length) { el.innerHTML = '<div class="text-muted">No applications yet.</div>'; return; }
      el.innerHTML = '<ul class="list-group"></ul>';
      const list = el.querySelector('ul');
      data.forEach(a=>{
        const li = document.createElement('li');
        li.className = 'list-group-item';
        li.innerHTML = `<strong>${escapeHtml(a.title || 'Internship')}</strong> <div class="small text-muted">Status: ${escapeHtml(a.status)}</div><div class="small text-muted">Applied: ${escapeHtml(a.applied_at)}</div>`;
        list.appendChild(li);
      });
    }
    function escapeHtml(s){ return String(s||'').replace(/[&<>"']/g,m=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m])); }
    load();
  </script>
</body>
</html>
