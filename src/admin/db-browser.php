<?php
require_once '../config.php';

$tables = [];
$error = null;
$query_result = null;
$custom_query = '';

try {
    // Get list of tables
    $result = $pdo->query("SELECT name FROM sqlite_master WHERE type='table'");
    $tables = $result->fetchAll(PDO::FETCH_COLUMN);

    // Handle custom query submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['query'])) {
        $custom_query = $_POST['query'];
        // Only allow SELECT queries for safety
        if (stripos(trim($custom_query), 'select') === 0) {
            $stmt = $pdo->query($custom_query);
            $query_result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $error = "Only SELECT queries are allowed for safety reasons.";
        }
    }
} catch(PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}

// Get table structure and data if table is selected
$selected_table = $_GET['table'] ?? null;
$table_data = null;
$table_structure = null;

if ($selected_table && in_array($selected_table, $tables)) {
    try {
        // Get table structure
        $stmt = $pdo->query("PRAGMA table_info(" . $pdo->quote($selected_table) . ")");
        $table_structure = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Get table data
        $stmt = $pdo->query("SELECT * FROM " . $pdo->quote($selected_table));
        $table_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        $error = "Error fetching table data: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Browser - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../styles.css">
    <style>
        .container { padding: 2rem; }
        .error { color: red; margin: 1rem 0; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 1rem 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 0.5rem;
            text-align: left;
        }
        th { background-color: #f4f4f4; }
        .query-box {
            width: 100%;
            height: 100px;
            margin: 1rem 0;
            font-family: monospace;
        }
        nav {
            background-color: #f4f4f4;
            padding: 1rem;
            margin-bottom: 2rem;
        }
        nav a {
            color: #333;
            text-decoration: none;
            margin-right: 1rem;
        }
        nav a:hover {
            text-decoration: underline;
        }
        .tables-list {
            margin: 2rem 0;
        }
        .tables-list a {
            display: inline-block;
            margin-right: 1rem;
            padding: 0.5rem 1rem;
            background-color: #f4f4f4;
            text-decoration: none;
            color: #333;
            border-radius: 4px;
        }
        .section {
            margin: 2rem 0;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>
<header>
    <h1>Database Browser</h1>
    <nav>
        <a href="index.php">Messages</a>
        <a href="db-browser.php">Database Browser</a>
    </nav>
</header>

<div class="container">
    <?php if ($error): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="section">
        <h2>Available Tables</h2>
        <div class="tables-list">
            <?php foreach ($tables as $table): ?>
                <a href="?table=<?php echo urlencode($table); ?>"><?php echo htmlspecialchars($table); ?></a>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="section">
        <h2>Custom Query</h2>
        <form method="post">
            <textarea name="query" class="query-box" placeholder="Enter SELECT query..."><?php echo htmlspecialchars($custom_query); ?></textarea>
            <button type="submit">Run Query</button>
        </form>
    </div>

    <?php if ($selected_table): ?>
        <div class="section">
            <h2>Table Structure: <?php echo htmlspecialchars($selected_table); ?></h2>
            <table>
                <tr>
                    <th>Column</th>
                    <th>Type</th>
                    <th>Nullable</th>
                    <th>Default</th>
                </tr>
                <?php foreach ($table_structure as $column): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($column['name']); ?></td>
                        <td><?php echo htmlspecialchars($column['type']); ?></td>
                        <td><?php echo $column['notnull'] ? 'No' : 'Yes'; ?></td>
                        <td><?php echo htmlspecialchars($column['dflt_value'] ?? 'NULL'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <h3>Table Data</h3>
            <?php if ($table_data && !empty($table_data)): ?>
                <table>
                    <tr>
                        <?php foreach ($table_data[0] as $column => $value): ?>
                            <th><?php echo htmlspecialchars($column); ?></th>
                        <?php endforeach; ?>
                    </tr>
                    <?php foreach ($table_data as $row): ?>
                        <tr>
                            <?php foreach ($row as $value): ?>
                                <td><?php echo htmlspecialchars($value ?? 'NULL'); ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p>No data in table.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if ($query_result): ?>
        <div class="section">
            <h2>Query Results</h2>
            <?php if (!empty($query_result)): ?>
                <table>
                    <tr>
                        <?php foreach ($query_result[0] as $column => $value): ?>
                            <th><?php echo htmlspecialchars($column); ?></th>
                        <?php endforeach; ?>
                    </tr>
                    <?php foreach ($query_result as $row): ?>
                        <tr>
                            <?php foreach ($row as $value): ?>
                                <td><?php echo htmlspecialchars($value ?? 'NULL'); ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p>No results found.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
