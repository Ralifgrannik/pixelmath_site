<?php
require_once '../config.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ' . ADMIN_PATH . '/admin_login.php');
    exit;
}

if (isset($_GET['id'])) {
    try {
        $stmt = $db->prepare('DELETE FROM articles WHERE id = :id');
        $stmt->execute(['id' => $_GET['id']]);
    } catch (PDOException $e) {
        die("Error deleting article: " . $e->getMessage());
    }
}

header('Location: ' . ADMIN_PATH . '/articles.php?success=Статья успешно удалена');
exit;