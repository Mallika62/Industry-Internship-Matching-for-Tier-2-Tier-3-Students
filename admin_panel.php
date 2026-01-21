<?php
session_start();
// In production protect this page (admin auth). For demo assume a logged-in admin.
if (!isset($_SESSION['user'])) { header('Location: login.php'); exit; }
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Admin Panel</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light">
  <nav class="navbar navbar-light bg-white shadow-sm mb-3"><div class="container"><a class="navbar-brand" href="dashboard.php">Hackthon2026</a></div></nav>
  <main class="container">
    <h4>Admin</h4>
    <div class="row g-3">
      <div class="col-md-6"><div class="card p-3"><h6>Users</h6><div id="users"></div></div></div>
      <div class="col-md-6"><div class="card p-3"><h6>Companies</h6><div id="companies"></div></div></div>
      <div class="col-12"><div class="card p-3 mt-2"><h6>Internships</h6><div id="interns"></div></div></div>
      <div class="col-12"><div class="card p-3 mt-2"><h6>Applications</h6><div id="apps"></div></div></div>
    </div>
  </main>

  <script>
    async function loadAll(){
      const u = await (await fetch('api/admin.php?action=users')).json();
      const c = await (await fetch('api/admin.php?action=companies')).json();
      const i = await (await fetch('api/admin.php?action=internships')).json();
      const a = await (await fetch('api/admin.php?action=applications')).json();

      document.getElementById('users').innerHTML = u.length?'<ul class="list-group">'+u.map(x=>'<li class="list-group-item">'+(x.fullname||x.email)+'</li>').join('')+'</ul>':'<div class="text-muted">No users</div>';
      document.getElementById('companies').innerHTML = c.length?'<ul class="list-group">'+c.map(x=>`<li class="list-group-item d-flex justify-content-between">${x.name} <div><button class="btn btn-sm btn-success" onclick="verify(${x.id})">Verify</button> <button class="btn btn-sm btn-danger" onclick="block(${x.id})">Block</button></div></li>`).join('')+'</ul>':'<div class="text-muted">No companies</div>';
      document.getElementById('interns').innerHTML = i.length?'<ul class="list-group">'+i.map(x=>'<li class="list-group-item">'+(x.title||'')+'</li>').join('')+'</ul>':'<div class="text-muted">No internships</div>';
      document.getElementById('apps').innerHTML = a.length?'<ul class="list-group">'+a.map(x=>'<li class="list-group-item">'+(x.student_email||'')+' - '+(x.title||'')+' ('+x.status+')</li>').join('')+'</ul>':'<div class="text-muted">No applications</div>';
    }

    async function verify(id){ await fetch('api/admin.php?action=verify&id='+id,{method:'POST'}); loadAll(); }
    async function block(id){ await fetch('api/admin.php?action=block&id='+id,{method:'POST'}); loadAll(); }

    loadAll();
  </script>
</body>
</html>
