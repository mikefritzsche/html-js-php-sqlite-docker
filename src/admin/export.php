<?php
require_once '../config.php';

try {
    $stmt = $pdo->query("SELECT * FROM messages ORDER BY created_at DESC");
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Set headers for CSV download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="messages.csv"');

    // Create CSV
    $output = fopen('php://output', 'w');
    fputcsv($output, array_keys($messages[0])); // Headers

    foreach ($messages as $message) {
        fputcsv($output, $message);
    }

    fclose($output);
} catch(PDOException $e) {
    die("Error exporting messages: " . $e->getMessage());
}
?>
