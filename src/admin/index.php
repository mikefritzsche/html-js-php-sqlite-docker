<?php
require_once '../config.php';

try {
    $stmt = $pdo->query("SELECT * FROM messages ORDER BY created_at DESC");
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Error fetching messages: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - <?php echo SITE_NAME; ?></title>
  <link rel="stylesheet" href="../styles.css">
  <style>
      .message-grid {
          display: grid;
          gap: 1rem;
          margin: 2rem 0;
      }
      .message-card {
          border: 1px solid #ddd;
          padding: 1rem;
          border-radius: 4px;
      }
      .export-btn {
          margin-bottom: 1rem;
      }
  </style>
</head>
<body>
<header>
  <h1>Admin Dashboard</h1>
</header>

<main>
  <button class="export-btn" onclick="exportToCsv()">Export to CSV</button>

  <div class="message-grid">
      <?php foreach ($messages as $message): ?>
        <div class="message-card">
          <h3><?php echo htmlspecialchars($message['name']); ?></h3>
          <p><strong>Email:</strong> <?php echo htmlspecialchars($message['email']); ?></p>
          <p><strong>Date:</strong> <?php echo htmlspecialchars($message['created_at']); ?></p>
          <p><strong>Message:</strong><br><?php echo nl2br(htmlspecialchars($message['message'])); ?></p>
        </div>
      <?php endforeach; ?>
  </div>
</main>

<script>
  function exportToCsv() {
    window.location.href = 'export.php';
  }
</script>
</body>
</html>
