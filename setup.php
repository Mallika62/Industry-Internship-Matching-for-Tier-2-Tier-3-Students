<?php
// Run this once to create the required tables for the project.
// Usage: open http://localhost/hackthon2026/setup.php in browser (or run php setup.php)

$DB_HOST = 'localhost';
$DB_NAME = 'hackthon2026';
$DB_USER = 'root';
$DB_PASS = '';

$path = __DIR__ . '/sql/schema.sql';
if (!file_exists($path)) {
    echo "ERROR: schema file not found at $path";
    exit;
}

$sql = file_get_contents($path);
if ($sql === false) {
    echo "ERROR: Unable to read schema file.";
    exit;
}

try {
    $pdo = new PDO("mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4", $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    echo "ERROR: DB connection failed: " . htmlspecialchars($e->getMessage());
    exit;
}

// Split statements by semicolon carefully (simple approach)
$stmts = array_filter(array_map('trim', preg_split('/;[\r\n]+/', $sql)));
$errors = [];
foreach ($stmts as $stmt) {
    if ($stmt === '') continue;
    try {
        $pdo->exec($stmt);
    } catch (PDOException $e) {
        $errors[] = $e->getMessage();
    }
}

if (empty($errors)) {
    echo "<h3>Setup completed successfully.</h3>";
    echo "<p>Tables created/checked. You can now visit <a href=\"index.php\">index.php</a>.</p>";
} else {
    echo "<h3>Completed with errors:</h3><pre>" . htmlspecialchars(implode("\n", $errors)) . "</pre>";
}
