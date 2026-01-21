<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$email = $_SESSION['user'];

// DB credentials (match other files)
$DB_HOST = 'localhost';
$DB_NAME = 'hackthon2026';
$DB_USER = 'root';
$DB_PASS = '';

try {
    $dsn = "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4";
    $pdo = new PDO($dsn, $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    $stmt = $pdo->prepare('SELECT fullname, dob, qualification, collegename, phone, email, branch, created_at FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        // user not found - redirect to login
        header('Location: login.php?error=1');
        exit;
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo 'Server error.';
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <!-- standardized navbar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container-fluid">
      <button id="toggleSidebar" class="hamburger me-2" aria-label="Toggle sidebar">&#9776;</button>
      <!-- <a class="navbar-brand" href="dashboard.php">Hackthon2026</a> -->

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavProfile" aria-controls="mainNavProfile" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="mainNavProfile">
        <!-- <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link active" aria-current="page" href="profile.php">View Profile</a></li>
          <li class="nav-item"><a class="nav-link" href="add_skills.php">Add / Update Skills</a></li>
        </ul> -->

        <div class="d-flex align-items-center">
          <?php if (!empty($user['fullname'])): ?>
            <span class="me-3 text-muted small">Hello, <?= htmlspecialchars($user['fullname']) ?></span>
          <?php else: ?>
            <span class="me-3 text-muted small"><?= htmlspecialchars($_SESSION['user'] ?? '') ?></span>
          <?php endif; ?>
          <!-- <a href="logout.php" class="btn btn-sm btn-outline-danger">Logout</a> -->
        </div>
      </div>
    </div>
  </nav>

  <div class="container py-5">
    <div class="card mx-auto" style="max-width:820px;">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-3">
          <h5 class="card-title mb-0">Your Profile</h5>
          <div>
            <a href="dashboard.php" class="btn btn-sm btn-outline-secondary me-2">Dashboard</a>
            <!-- <a href="logout.php" class="btn btn-sm btn-outline-danger">Logout</a> -->
          </div>
        </div>

        <dl class="row">
          <dt class="col-sm-3">Full Name</dt>
          <dd class="col-sm-9"><?= htmlspecialchars($user['fullname']) ?></dd>

          <dt class="col-sm-3">Email</dt>
          <dd class="col-sm-9"><?= htmlspecialchars($user['email']) ?></dd>

          <dt class="col-sm-3">Date of Birth</dt>
          <dd class="col-sm-9"><?= htmlspecialchars($user['dob']) ?></dd>

          <dt class="col-sm-3">Branch</dt>
          <dd class="col-sm-9"><?= htmlspecialchars($user['branch']) ?></dd>

          <dt class="col-sm-3">Qualification</dt>
          <dd class="col-sm-9"><?= htmlspecialchars($user['qualification']) ?></dd>

          <dt class="col-sm-3">College Name</dt>
          <dd class="col-sm-9"><?= htmlspecialchars($user['collegename']) ?></dd>

          <dt class="col-sm-3">Phone</dt>
          <dd class="col-sm-9"><?= htmlspecialchars($user['phone']) ?></dd>

          <dt class="col-sm-3">Registered On</dt>
          <dd class="col-sm-9"><?= htmlspecialchars($user['created_at']) ?></dd>
        </dl>
      </div>
    </div>
  </div>
</body>
</html>