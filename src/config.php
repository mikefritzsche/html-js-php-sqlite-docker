<?php
// Database configuration
$db_path = '/var/www/html/database/website.db';

try {
    // Ensure database directory exists and is writable
    $db_dir = dirname($db_path);
    if (!is_dir($db_dir)) {
        mkdir($db_dir, 0755, true);
    }

    $pdo = new PDO("sqlite:$db_path");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create table if it doesn't exist
    $pdo->exec("CREATE TABLE IF NOT EXISTS messages (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        email TEXT NOT NULL,
        message TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

} catch(PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    throw new Exception("Database connection failed. Please check the error logs.");
}

// Other configuration settings
define('SITE_NAME', 'Plain Jane');
define('ADMIN_EMAIL', 'admin@example.com');
?>
