<?php
require_once 'config.php';

// Проверка авторизации
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Неавторизованный доступ']);
    exit;
}

// Проверка метода запроса
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Метод не поддерживается']);
    exit;
}

// Получение данных из POST-запроса
$data = json_decode(file_get_contents('php://input'), true);
$task_id = isset($data['id']) ? (int)$data['id'] : 0;
$new_solution = isset($data['solution']) ? trim($data['solution']) : '';

if ($task_id <= 0 || empty($new_solution)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Неверные данные']);
    exit;
}

try {
    $stmt = $db->prepare('UPDATE tasks_app_task SET solution = :solution WHERE id = :id');
    $stmt->bindValue(':solution', $new_solution, PDO::PARAM_STR);
    $stmt->bindValue(':id', $task_id, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode(['success' => true, 'message' => 'Решение успешно обновлено']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Ошибка при обновлении: ' . $e->getMessage()]);
}
?>