<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php'); exit;
}
$email = $_SESSION['user'];
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8"><title>Post Internship</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <nav class="navbar navbar-light bg-white shadow-sm mb-3">
    <div class="container">
      <a class="navbar-brand" href="dashboard.php">Hackthon2026</a>
      <div><span class="small text-muted"><?= htmlspecialchars($email) ?></span></div>
    </div>
  </nav>

  <main class="container">
    <div class="card mx-auto" style="max-width:800px;">
      <div class="card-body">
        <h5>Post Internship</h5>
        <form id="frm">
          <div class="mb-2">
            <label>Company ID (numeric)</label>
            <input name="company_id" class="form-control" required value="1">
          </div>
          <div class="mb-2"><label>Title</label><input name="title" class="form-control" required></div>
          <div class="mb-2"><label>Description</label><textarea name="description" class="form-control"></textarea></div>
          <div class="mb-2"><label>Required Skills (comma-separated)</label><input name="required_skills" class="form-control"></div>
          <div class="row g-2">
            <div class="col"><input name="duration" class="form-control" placeholder="Duration"></div>
            <div class="col"><input name="stipend" class="form-control" placeholder="Stipend"></div>
            <div class="col">
              <select name="remote" class="form-select">
                <option value="remote">Remote</option>
                <option value="on-site">On-site</option>
                <option value="hybrid">Hybrid</option>
              </select>
            </div>
          </div>
          <div class="mt-3 text-end">
            <button class="btn btn-primary" type="submit">Create</button>
          </div>
        </form>
      </div>
    </div>
  </main>

  <script>
    document.getElementById('frm').addEventListener('submit', async function(e){
      e.preventDefault();
      const form = new FormData(e.target);
      const obj = Object.fromEntries(form.entries());
      try {
        const res = await fetch('api/internships.php', {
          method: 'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify(obj)
        });
        const data = await res.json();
        if (data.success) {
          alert('Created internship id: ' + data.id);
          window.location.href = 'company_post_internship.php';
        } else alert(data.error || 'Error');
      } catch (err){ alert('Failed'); console.error(err); }
    });
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
