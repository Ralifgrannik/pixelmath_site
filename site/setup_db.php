<?php
require_once 'config.php';
try {
    $stmt = $db->query('SELECT name FROM sqlite_master WHERE type="table" AND name="articles"');
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        echo "Table 'articles' found in database at " . DB_PATH;
        // Test a query on articles
        $stmt = $db->query('SELECT count(*) as count FROM articles');
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "<br>Number of articles: " . $row['count'];
    } else {
        echo "Table 'articles' does not exist in database at " . DB_PATH;
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>