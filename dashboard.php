<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
$email = $_SESSION['user'];

// Handle simple POST apply action (store applied internships in session)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'apply_intern') {
        $intern = trim($_POST['intern'] ?? '');
        if ($intern !== '') {
            if (!isset($_SESSION['applied_internships']) || !is_array($_SESSION['applied_internships'])) {
                $_SESSION['applied_internships'] = [];
            }
            if (!in_array($intern, $_SESSION['applied_internships'])) {
                $_SESSION['applied_internships'][] = $intern;
            }
        }
        header('Location: dashboard.php#recommended');
        exit;
    }
}

// DB credentials
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
        header('Location: login.php?error=1');
        exit;
    }

    // counts/statistics
    $totalUsers = (int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();

} catch (PDOException $e) {
    http_response_code(500);
    echo 'Server error.';
    exit;
}

// Simple recommendations by branch (same as before)
$recommendations = [
    'Computer Science' => [
        'Frontend Developer Intern - Acme Web',
        'Backend Developer Intern - ByteWorks',
        'AI/ML Intern - Neural Labs'
    ],
    'Electronics' => [
        'Embedded Systems Intern - CircuitPro',
        'VLSI Intern - ChipForge'
    ],
    'Mechanical' => [
        'CAD Intern - MechDesign',
        'Manufacturing Intern - ProdFlow'
    ],
    'Civil' => [
        'Site Engineering Intern - BuildRight',
        'Structural Intern - ArchStruct'
    ],
    'Information Technology' => [
        'IT Support Intern - NetHelp',
        'Cloud Intern - SkyStack'
    ],
    'Other' => [
        'General Intern - DiverseCo'
    ],
    'default' => [
        'General Intern - DiverseCo',
        'Summer Intern - Open Opportunities'
    ]
];
$branch = $user['branch'] ?? '';
$recommended = $recommendations[$branch] ?? $recommendations['default'];

$applied = $_SESSION['applied_internships'] ?? [];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .sidebar {
      min-height: 70vh;
    }
    .stat-box { padding: 1rem; border-radius: 6px; background:#fff; box-shadow: 0 0 0.5rem rgba(0,0,0,0.04); }
    #sidebarCollapsed { display: none; }
    .hamburger { font-size: 1.25rem; cursor:pointer; border: none; background: transparent; }
    @media (max-width: 767px) {
      aside { display: none; }
      aside.show-mobile { display: block; position: absolute; z-index: 1030; width: 80%; left: 0; top: 56px; }
    }
  </style>
</head>
<body class="bg-light">
  <!-- standardized navbar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container-fluid">
      <button id="toggleSidebar" class="hamburger me-2" aria-label="Toggle sidebar">&#9776;</button>
      <a class="navbar-brand" href="dashboard.php"></a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

       <div class="collapse navbar-collapse col-md-3" id="mainNav">
        <!--<ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="profile.php">View Profile</a></li>
          <li class="nav-item"><a class="nav-link" href="add_skills.php">Add / Update Skills</a></li>
          <li class="nav-item"><a class="nav-link" href="#recommended">Recommended</a></li>
          <li class="nav-item"><a class="nav-link" href="#applied">Applied</a></li>
        </ul> -->

        <div class="d-flex align-items-center  ">
          <?php if (!empty($user['fullname'])): ?>
            <span class="me-3 text-muted small">Hello, <?= htmlspecialchars($user['fullname']) ?></span>
          <?php elseif (!empty($_SESSION['user'])): ?>
            <span class="me-3 text-muted small"><?= htmlspecialchars($_SESSION['user']) ?></span>
          <?php endif; ?>
          <!-- <a href="logout.php" class="btn btn-sm btn-outline-danger">Logout</a> -->
        </div>
      </div>
    </div>
  </nav>

  <div class="container py-4">
    <div class="row g-4">
      <!-- Sidebar -->
      <aside id="sidebar" class="col-md-3">
        <div class="card sidebar">
          <div class="card-body">
            <h6 class="mb-3">Menu</h6>
            <div class="list-group">
              <a href="profile.php" class="list-group-item list-group-item-action">View Profile</a>
              <a href="add_skills.php" class="list-group-item list-group-item-action">Add / Update Skills</a>
              <a href="recommend.php" class="list-group-item list-group-item-action">Recommended Internships</a>
              <a href="#applied.php" class="list-group-item list-group-item-action">Applied Internships</a>
              <a href="logout.php" class="list-group-item list-group-item-action text-danger">Logout</a>
            </div>
          </div>
        </div>
      </aside>

      <!-- Main content -->
      <main class="col-md-9">
        <div class="row mb-3">
          <div class="col-md-3">
            <div class="stat-box">
              <div class="small text-muted">Registered Users</div>
              <div class="h4 mb-0"><?= htmlspecialchars((string)$totalUsers) ?></div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat-box">
              <div class="small text-muted">Recommended Internships</div>
              <div class="h4 mb-0"><?= htmlspecialchars((string)count($recommended)) ?></div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat-box">
              <div class="small text-muted">Applied Internships</div>
              <div class="h4 mb-0"><?= htmlspecialchars((string)count($applied)) ?></div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat-box">
              <div class="small text-muted">Registered On</div>
              <div class="h6 mb-0"><?= htmlspecialchars($user['created_at']) ?></div>
            </div>
          </div>
        </div>

        <div class="card mb-3">
          <div class="card-body">
            <h5 class="card-title mb-1">Hello, <?= htmlspecialchars($user['fullname']) ?></h5>
            <p class="text-muted mb-0">Branch: <?= htmlspecialchars($branch ?: 'N/A') ?> · Email: <?= htmlspecialchars($email) ?></p>
          </div>
        </div>

        <!-- Recommended internships -->
        <section id="recommended" class="card mb-3">
          <div class="card-body">
            <h6 class="card-title">Recommended Internships</h6>
            <p class="small text-muted">Based on your branch</p>
            <?php if ($recommended): ?>
              <div class="list-group">
                <?php foreach ($recommended as $intern): ?>
                  <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div><?= htmlspecialchars($intern) ?></div>
                    <div>
                      <?php if (in_array($intern, $applied)): ?>
                        <span class="badge bg-success">Applied</span>
                      <?php else: ?>
                        <form method="post" action="dashboard.php" class="d-inline-block ms-2">
                          <input type="hidden" name="action" value="apply_intern">
                          <input type="hidden" name="intern" value="<?= htmlspecialchars($intern) ?>">
                          <button type="submit" class="btn btn-sm btn-outline-primary">Apply</button>
                        </form>
                      <?php endif; ?>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php else: ?>
              <div class="text-muted">No recommendations available.</div>
            <?php endif; ?>
          </div>
        </section>

        <!-- Applied internships -->
        <section id="applied" class="card">
          <div class="card-body">
            <h6 class="card-title">Applied Internships</h6>
            <?php if (!empty($applied)): ?>
              <ul class="list-group">
                <?php foreach ($applied as $a): ?>
                  <li class="list-group-item"><?= htmlspecialchars($a) ?></li>
                <?php endforeach; ?>
              </ul>
            <?php else: ?>
              <div class="text-muted">You haven't applied to any internships yet.</div>
            <?php endif; ?>
          </div>
        </section>
      </main>
    </div>
  </div>

  <script>
    // Toggle sidebar visibility (hamburger button)
    (function(){
      const toggle = document.getElementById('toggleSidebar');
      const sidebar = document.getElementById('sidebar');
      if (!toggle || !sidebar) return;
      toggle.addEventListener('click', function(){
        if (sidebar.classList.contains('d-none')) {
          sidebar.classList.remove('d-none');
        } else {
          sidebar.classList.add('d-none');
        }
        if (window.innerWidth <= 767) {
          sidebar.classList.toggle('show-mobile');
        }
      });
    })();
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>