<?php
// ...new file...

// Replace these with your database credentials
$DB_HOST = 'localhost';
$DB_NAME = 'hackthon2026';
$DB_USER = 'root';
$DB_PASS = '';

function bad($msg) {
    http_response_code(400);
    echo htmlspecialchars($msg);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    bad('Invalid request method');
}

// Collect and basic-validate inputs
$fullname     = trim($_POST['fullname'] ?? '');
$dob          = trim($_POST['dob'] ?? '');
$qualification= trim($_POST['qualification'] ?? '');
$collegename  = trim($_POST['collegename'] ?? '');
$phone        = trim($_POST['phone'] ?? '');
$email        = trim($_POST['email'] ?? '');
$branch       = trim($_POST['branch'] ?? '');

if ($fullname === '' || $dob === '' || $qualification === '' || $collegename === '' ||
    $phone === '' || $email === '' || $branch === '') {
    bad('All fields are required.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    bad('Invalid email address.');
}

if (!preg_match('/^\d{10}$/', $phone)) {
    bad('Phone must be 10 digits.');
}

// Validate date format (YYYY-MM-DD)
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dob) || strtotime($dob) === false) {
    bad('Invalid date of birth.');
}

try {
    $dsn = "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4";
    $pdo = new PDO($dsn, $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    $sql = "INSERT INTO users
            (fullname, dob, qualification, collegename, phone, email, branch, created_at)
            VALUES (:fullname, :dob, :qualification, :collegename, :phone, :email, :branch, NOW())";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':fullname' => $fullname,
        ':dob' => $dob,
        ':qualification' => $qualification,
        ':collegename' => $collegename,
        ':phone' => $phone,
        ':email' => $email,
        ':branch' => $branch,
    ]);

    echo '<script>alert("Registration successful.");</script>';
    echo '<script>window.location.href = "login.php?registered=1";</script>';
} catch (PDOException $e) {
    // In production don't echo raw errors; log them instead.
    http_response_code(500);
    echo 'Server error: ' . htmlspecialchars($e->getMessage());
    exit;
}
?>
