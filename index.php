<?php
session_start();
// if (!empty($_SESSION['user'])) {
//     header('Location: index.php');
//     exit;
// }
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Welcome - Find Your Future</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #f5f7fb; min-height:100vh; display:flex; align-items:center; }
    .hero {
      padding: 3rem;
      background: white;
      border-radius: 12px;
      box-shadow: 0 6px 24px rgba(18, 38, 63, 0.06);
    }
    .hero h1 { font-weight:700; letter-spacing: -0.5px; }
    .hero p { color: #556; }
    .btn-hero { padding: .6rem 1.15rem; font-weight:600; }
    .img-side { border-radius: 8px; overflow: hidden; }
    @media (max-width: 767px) {
      body { padding: 1.5rem; }
      .hero { padding: 1.5rem; }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="hero mx-auto" style="max-width:1100px;">
      <div class="row g-0 align-items-center">
        <!-- Text side -->
        <div class="col-md-6 p-4 p-md-5">
          <h1 class="mb-3">Welcome — Find Your Future</h1>
          <p class="mb-4">We help Tier-2 and Tier-3 students discover internships that match their skills.
             Build your profile, add your skills, and get recommended opportunities — location bias reduced.</p>
          <div class="d-flex gap-2">
            <a href="login.php" class="btn btn-primary btn-hero">Login</a>
            <a href="register.html" class="btn btn-outline-secondary btn-hero">Register</a>
          </div>
          <small class="d-block text-muted mt-3">Already registered? Click Login to continue to your dashboard.</small>
        </div>

        <!-- Image side -->
        <div class="col-md-6 d-none d-md-block">
          <div class="img-side">
            <img src="https://images.unsplash.com/photo-1531497865140-9f0b5f5d6a2f?q=80&w=1200&auto=format&fit=crop&ixlib=rb-4.0.3&s=5b2ff14b3f6b7b2b0f2c1a1d9a9e7f2a" alt="Find internships" style="width:100%; height:100%; object-fit:cover; min-height:320px;">
          </div>
        </div>

        <!-- On small screens show the image below -->
        <div class="col-12 d-block d-md-none mt-3">
          <div class="img-side">
            <img src="https://images.unsplash.com/photo-1531497865140-9f0b5f5d6a2f?q=80&w=1200&auto=format&fit=crop&ixlib=rb-4.0.3&s=5b2ff14b3f6b7b2b0f2c1a1d9a9e7f2a" alt="Find internships" style="width:100%; height:auto; object-fit:cover; border-radius:8px;">
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>