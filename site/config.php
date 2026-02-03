<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);

define('BASE_PATH', __DIR__);
define('DB_PATH', BASE_PATH . '/db/db.sqlite3');

try {
    $db = new PDO('sqlite:' . DB_PATH);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    error_log('Database error: ' . $e->getMessage());
    die('Database connection failed');
}
?>