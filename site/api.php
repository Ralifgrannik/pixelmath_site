<?php
require_once 'config.php';
header('Content-Type: application/json; charset=UTF-8');

$test_number = isset($_GET['test_number']) ? (int)$_GET['test_number'] : null;

try {
    if ($test_number === null || $test_number < 1 || $test_number > 12) {
        http_response_code(400);
        echo json_encode(['error' => 'Неверный номер задачи']);
        exit;
    }

    $stmt = $db->prepare('SELECT * FROM tasks_app_task WHERE number = :number ORDER BY RANDOM() LIMIT 1');
    $stmt->execute(['number' => $test_number]);
    $task = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($task) {
        echo json_encode([
            'id' => (int)$task['id'],
            'number' => (int)$task['number'],
            'problem' => $task['problem'],
            'image' => $task['image'],
            'solution' => $task['solution'],
            'answer' => $task['answer'],
            'task_number' => $task['task_number'] ? (int)$task['task_number'] : null
        ]);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Задача не найдена']);
    }
} catch (PDOException $e) {
    error_log('API error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Ошибка сервера']);
}
?>