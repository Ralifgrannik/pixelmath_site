<?php
require_once 'config.php';

// Проверка авторизации
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ' . ADMIN_PATH . '/admin_login.php');
    exit;
}

if (isset($_GET['id'])) {
    // Удаление изображения, если есть
    $stmt = $db->prepare('SELECT image FROM tasks_app_task WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $task = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($task && $task['image']) {
        unlink(BASE_PATH . $task['image']);
    }

    // Удаление задачи
    $stmt = $db->prepare('DELETE FROM tasks_app_task WHERE id = ?');
    $stmt->execute([$_GET['id']]);
}

header('Location: ' . ADMIN_PATH . '/admin.php?success=' . urlencode('Задача успешно удалена!'));
exit;
?>