<?php
session_start();
if (!isset($_SESSION['user'])) { header('Location: login.php'); exit; }
$email = $_SESSION['user'];
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8"><title>Recommendations</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <nav class="navbar navbar-light bg-white shadow-sm mb-3">
    <div class="container">
      <a class="navbar-brand" href="dashboard.php">Hackthon2026</a>
      <div>
        <span class="small text-muted"><?= htmlspecialchars($email) ?></span>
      </div>
    </div>
  </nav>

  <main class="container">
    <h4>Recommended Internships</h4>
    <div id="rec" class="mt-3">
      
    </div>
  </main>

  <script>
    async function load() {
      const res = await fetch('api/recommendations.php?action=to-student&email=' + encodeURIComponent(<?= json_encode($email) ?>));
      const json = await res.json();
      const el = document.getElementById('rec');
      if (!json.recommendations || !json.recommendations.length) {
        el.innerHTML = '<div class="text-muted">No recommendations yet.</div>'; return;
      }
      el.innerHTML = '<div class="list-group"></div>';
      const list = el.querySelector('.list-group');
      json.recommendations.forEach(r => {
        const item = document.createElement('div');
        item.className = 'list-group-item d-flex justify-content-between align-items-center';
        item.innerHTML = `<div><strong>${escapeHtml(r.title)}</strong><div class="small text-muted">${escapeHtml(r.company)}</div></div><div><span class="badge bg-primary me-2">${r.score}%</span><a href="internships_list.php" class="btn btn-sm btn-outline-primary">View</a></div>`;
        list.appendChild(item);
      });
    }
    function escapeHtml(s){ return String(s||'').replace(/[&<>"']/g, m=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m])); }
    load();
  </script>
</body>
</html>
