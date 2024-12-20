<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $stmt = $pdo->prepare("INSERT INTO messages (name, email, message) VALUES (?, ?, ?)");
    try {
        $stmt->execute([
            $data['name'],
            $data['email'],
            $data['message']
        ]);
        echo json_encode(['status' => 'success']);
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Database error']);
    }
}
?>
