<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
$email = $_SESSION['user'];
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Internships</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <!-- ...simple navbar... -->
  <nav class="navbar navbar-light bg-white shadow-sm mb-3">
    <div class="container">
      <a class="navbar-brand" href="dashboard.php">Hackthon2026</a>
      <div>
        <span class="me-3 small text-muted"><?= htmlspecialchars($email) ?></span>
        <a href="notifications.php" class="btn btn-sm btn-outline-secondary me-2">Notifications</a>
        <a href="logout.php" class="btn btn-sm btn-outline-danger">Logout</a>
      </div>
    </div>
  </nav>

  <main class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4>Available Internships</h4>
      <div>
        <a href="recommend.php" class="btn btn-sm btn-primary">Recommendations</a>
        <a href="applied_list.php" class="btn btn-sm btn-outline-primary ms-2">My Applications</a>
      </div>
    </div>

    <div id="list" class="row g-3"></div>
  </main>

  <script>
    const userEmail = <?= json_encode($email) ?>;
    async function load() {
      try {
        const res = await fetch('api/internships.php');
        const data = await res.json();
        const container = document.getElementById('list');
        container.innerHTML = '';
        if (!data.length) {
          container.innerHTML = '<div class="text-muted">No internships found.</div>';
          return;
        }
        data.forEach(i => {
          const col = document.createElement('div');
          col.className = 'col-md-6';
          col.innerHTML = `
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">${escapeHtml(i.title)}</h5>
                <h6 class="card-subtitle mb-2 text-muted">${escapeHtml(i.company_name || '')}</h6>
                <p class="card-text small">${escapeHtml(i.description || '')}</p>
                <p class="mb-1"><strong>Skills:</strong> ${escapeHtml(i.required_skills || '')}</p>
                <p class="mb-1"><strong>Duration:</strong> ${escapeHtml(i.duration || '')} · <strong>Stipend:</strong> ${escapeHtml(i.stipend || '')}</p>
                <div class="d-flex justify-content-end">
                  <button class="btn btn-sm btn-outline-primary me-2" onclick="apply(${i.id}, '${escapeAttr(i.title)}')">Apply</button>
                </div>
              </div>
            </div>`;
          container.appendChild(col);
        });
      } catch (e) {
        console.error(e);
        document.getElementById('list').innerHTML = '<div class="text-danger">Failed to load internships.</div>';
      }
    }

    async function apply(internship_id, title) {
      if (!confirm('Apply for "' + title + '"?')) return;
      try {
        const res = await fetch('api/applications.php', {
          method: 'POST',
          headers: {'Content-Type':'application/json'},
          body: JSON.stringify({ internship_id, student_email: userEmail, skills: '' })
        });
        const r = await res.json();
        if (r.success) {
          alert('Application submitted.');
        } else {
          alert(r.error || 'Error applying');
        }
      } catch (e) { alert('Failed to apply'); console.error(e); }
    }

    function escapeHtml(s){ return String(s||'').replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m])); }
    function escapeAttr(s){ return escapeHtml(s).replace(/"/g,'&quot;'); }

    load();
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
