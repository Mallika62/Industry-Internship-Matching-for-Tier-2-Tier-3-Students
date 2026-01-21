<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
$email = $_SESSION['user'];

require_once __DIR__ . '/api/db.php'; // reuse shared PDO connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $skills_raw = trim($_POST['skills'] ?? '');
    // normalize: comma-separated
    $skills = implode(', ', array_values(array_filter(array_map('trim', explode(',', $skills_raw)))));
    // upsert into student_skills
    $stmt = $pdo->prepare("SELECT id FROM student_skills WHERE student_email = ? LIMIT 1");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $u = $pdo->prepare("UPDATE student_skills SET skills = ? WHERE student_email = ?");
        $u->execute([$skills, $email]);
    } else {
        $i = $pdo->prepare("INSERT INTO student_skills (student_email, skills) VALUES (?, ?)");
        $i->execute([$email, $skills]);
    }
    header('Location: dashboard.php#skills');
    exit;
}

// display current skills
$stmt = $pdo->prepare("SELECT skills FROM student_skills WHERE student_email = ? LIMIT 1");
$stmt->execute([$email]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$session_skills = $row ? explode(',', $row['skills']) : [];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Add / Update Skills</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <!-- standardized navbar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container-fluid">
      <button id="toggleSidebar" class="hamburger me-2" aria-label="Toggle sidebar">&#9776;</button>
      <a class="navbar-brand" href="dashboard.php">Hackthon2026</a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavSkills" aria-controls="mainNavSkills" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="mainNavSkills">
        <!-- <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="profile.php">View Profile</a></li>
          <li class="nav-item"><a class="nav-link active" href="add_skills.php">Add / Update Skills</a></li>
        </ul> -->

        <div class="d-flex align-items-center">
          <span class="me-3 text-muted small"><?= htmlspecialchars($email) ?></span>
          <!-- <a href="logout.php" class="btn btn-sm btn-outline-danger">Logout</a> -->
        </div>
      </div>
    </div>
  </nav>

  <main class="container py-4">
    <div class="card mx-auto" style="max-width:720px;">
      <div class="card-body">
        <h5 class="card-title">Add / Update Skills</h5>
        <p class="small text-muted">Enter skills as a comma-separated list (e.g. HTML, CSS, JavaScript)</p>
        <form method="post" class="row g-2">
          <div class="col-12">
            <input name="skills" type="text" class="form-control" placeholder="e.g. Python, SQL, React" value="<?= htmlspecialchars(isset($row['skills']) ? $row['skills'] : '') ?>">
          </div>
          <div class="col-auto">
            <button type="submit" class="btn btn-primary">Save Skills</button>
            <a href="dashboard.php" class="btn btn-secondary ms-2">Cancel</a>
          </div>
        </form>

        <div class="mt-3">
          <?php if (!empty($row['skills'])): ?>
            <strong>Current skills:</strong>
            <div class="mt-2">
              <?php foreach (array_filter(array_map('trim', explode(',', $row['skills'])) ) as $s): ?>
                <span class="badge bg-secondary me-1"><?= htmlspecialchars($s) ?></span>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <div class="text-muted">No skills added yet.</div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
