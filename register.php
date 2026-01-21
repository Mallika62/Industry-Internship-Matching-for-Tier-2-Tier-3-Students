<?php
// If already logged in redirect to dashboard
session_start();
// s
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>User Registration - Find Your Future</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background:#f5f7fa; }
    .card { border-radius:8px; }
  </style>
</head>
<body>
  <!-- public navbar -->
  <!-- <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
      <a class="navbar-brand" href="index.php">Hackthon2026</a>
      <div class="ms-auto">
        <a href="login.php" class="btn btn-sm btn-outline-primary">Login</a>
      </div>
    </div>
  </nav> -->

  <main class="container d-flex align-items-center justify-content-center" style="min-height:80vh;">
    <div class="card shadow-sm w-100" style="max-width: 720px;">
      <div class="card-body p-4">
        <h1 class="h5 mb-3">Registration</h1>

        <form id="registrationForm" action="registration.php" method="post" novalidate>
          <div class="mb-3">
            <label for="fullname" class="form-label">Full Name</label>
            <input id="fullname" name="fullname" type="text" class="form-control" required minlength="2" placeholder="First Last" />
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label for="dob" class="form-label">Date of Birth</label>
              <input id="dob" name="dob" type="date" class="form-control" required max="">
            </div>

            <div class="col-md-6">
              <label for="branch" class="form-label">Branch</label>
              <select id="branch" name="branch" class="form-select" required>
                <option value="">Select branch</option>
                <option>Computer Science</option>
                <option>Electronics</option>
                <option>Mechanical</option>
                <option>Civil</option>
                <option>Information Technology</option>
                <option>Other</option>
              </select>
            </div>
          </div>

          <div class="mb-3">
            <label for="qualification" class="form-label">Qualification</label>
            <select id="qualification" name="qualification" class="form-select" required>
              <option value="">Select qualification</option>
              <option>High School</option>
              <option>Diploma</option>
              <option>Bachelor's</option>
              <option>Master's</option>
              <option>PhD</option>
              <option>Other</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="collegename" class="form-label">College Name</label>
            <input id="collegename" name="collegename" type="text" class="form-control" required placeholder="Your college/institution" />
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label for="phone" class="form-label">Phone Number</label>
              <input id="phone" name="phone" type="tel" pattern="[0-9]{10}" class="form-control" required placeholder="10-digit number" />
              <small class="text-muted">Enter 10 digits, no spaces or symbols</small>
            </div>

            <div class="col-md-6">
              <label for="email" class="form-label">Email</label>
              <input id="email" name="email" type="email" class="form-control" required placeholder="name@example.com" />
            </div>
          </div>

          <div class="text-end">
            <a href="login.php" class="me-3">Already have an account? Login</a>
            <button type="submit" class="btn btn-primary">Register</button>
          </div>
        </form>
      </div>
    </div>
  </main>

  <script>
    (function () {
      const dob = document.getElementById('dob');
      if (dob) dob.max = new Date().toISOString().split('T')[0];
      const form = document.getElementById('registrationForm');
      form.addEventListener('submit', function (e) {
        if (!form.checkValidity()) {
          e.preventDefault();
          form.reportValidity();
        }
      });
    })();
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
