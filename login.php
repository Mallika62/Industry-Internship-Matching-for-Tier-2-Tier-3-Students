<?php
session_start();

// DB config — update DB_USER and DB_PASS if needed
$dbHost = '127.0.0.1';
$dbUser = 'root';
$dbPass = '';
$dbName = 'hackthon2026';

// If already logged in, go to dashboard
// if (!empty($_SESSION['user'])) {
//     header('Location: dashboard.php');
//     exit;
// }

// Handle POST login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $dob = trim($_POST['dob'] ?? '');

    if ($email === '' || $dob === '') {
        header('Location: login.php?error=1');
        exit;
    }

    // Connect to MySQL
    $mysqli = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
    if ($mysqli->connect_errno) {
        header('Location: login.php?error=1');
        exit;
    }

    // Use prepared statement to avoid SQL injection
    $stmt = $mysqli->prepare('SELECT dob FROM users WHERE email = ? LIMIT 1');
    if ($stmt) {
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->bind_result($dbDob);
        $found = false;
        if ($stmt->fetch()) {
            $found = true;
        }
        $stmt->close();
        $mysqli->close();

        // Compare DOB strings (assuming stored as YYYY-MM-DD)
        if ($found && $dbDob === $dob) {
            $_SESSION['user'] = $email;
            header('Location: dashboard.php');
            exit;
        } else {
            header('Location: login.php?error=1');
            exit;
        }
    } else {
        $mysqli->close();
        header('Location: login.php?error=1');
        exit;
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Login - Find Your Future</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background:#f5f7fb; min-height:100vh; display:flex; align-items:center; }
    .card { border-radius:10px; }
  </style>
</head>
<body>
  <!-- simple public navbar -->
  <!-- <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
      <a class="navbar-brand" href="index.php">Hackthon2026</a>
      <div class="ms-auto">
        <a href="register.php" class="btn btn-sm btn-outline-primary">Register</a>
      </div>
    </div>
  </nav> -->

  <div class="container d-flex align-items-center justify-content-center" style="min-height:80vh;">
    <div class="card shadow-sm" style="max-width:420px; width:100%;">
      <div class="card-body">
        <h5 class="card-title mb-3">Sign In</h5>

        <?php if (isset($_GET['registered'])): ?>
          <div class="alert alert-success py-2">Registration successful — please login.</div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
          <div class="alert alert-danger py-2">Invalid email or DOB.</div>
        <?php endif; ?>

        <form action="login.php" method="post" novalidate>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" name="email" type="email" class="form-control" required placeholder="name@example.com">
          </div>

          <div class="mb-3">
            <label for="dob" class="form-label">Date of Birth</label>
            <input id="dob" name="dob" type="date" class="form-control" required max="">
          </div>

          <div class="d-grid">
            <button class="btn btn-primary" type="submit">Login</button>
          </div>

          <div class="mt-3 text-center">
            <a href="register.php">Register an account</a>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    // set max for dob = today
    (function(){
      const d = document.getElementById('dob');
      if (d) d.max = new Date().toISOString().split('T')[0];
    })();
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>