<?php
require_once 'config.php';

// Проверка авторизации
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ' . ADMIN_PATH . '/admin_login.php');
    exit;
}

// Получение задачи, если редактируем
$task = null;
$success = null;
if (isset($_GET['id'])) {
    $stmt = $db->prepare('SELECT * FROM tasks_app_task WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $task = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$task) {
        die('Задача не найдена');
    }
}

// Обработка удаления изображения
if (isset($_POST['delete_image']) && isset($task['image']) && $task['image']) {
    unlink(BASE_PATH . $task['image']);
    $stmt = $db->prepare('UPDATE tasks_app_task SET image = NULL WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $success = "Изображение успешно удалено!";
    // Обновляем задачу после удаления изображения
    $stmt = $db->prepare('SELECT * FROM tasks_app_task WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $task = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['delete_image'])) {
    $number = (int)($_POST['number'] ?? 0);
    $problem = $_POST['problem'] ?? '';
    $solution = $_POST['solution'] ?? '';
    $answer = $_POST['answer'] ?? '';
    $task_number = !empty($_POST['task_number']) ? (int)$_POST['task_number'] : null;

    // Обработка изображения
    $image_path = isset($_POST['image_path']) ? $_POST['image_path'] : ($task['image'] ?? null);
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $file_name = time() . '_' . basename($_FILES['image']['name']);
        $target_file = UPLOAD_DIR . $file_name;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image_path = UPLOAD_URL . $file_name;
        } else {
            $error = "Ошибка при загрузке изображения";
        }
    }

    try {
        if (isset($_GET['id'])) {
            // Обновление задачи
            $stmt = $db->prepare('UPDATE tasks_app_task SET number = ?, problem = ?, image = ?, solution = ?, answer = ?, task_number = ? WHERE id = ?');
            $stmt->execute([$number, $problem, $image_path, $solution, $answer, $task_number, $_GET['id']]);
            $success = "Задача успешно обновлена!";
        } else {
            // Добавление новой задачи
            $stmt = $db->prepare('INSERT INTO tasks_app_task (number, problem, image, solution, answer, task_number) VALUES (?, ?, ?, ?, ?, ?)');
            $stmt->execute([$number, $problem, $image_path, $solution, $answer, $task_number]);
            $success = "Задача успешно добавлена!";
        }
        
        // Собираем параметры для сохранения состояния
        $params = [
            'filter_number' => isset($_GET['filter_number']) ? (int)$_GET['filter_number'] : 0,
            'sort_by' => isset($_GET['sort_by']) ? $_GET['sort_by'] : 'number',
            'sort_order' => isset($_GET['sort_order']) ? $_GET['sort_order'] : 'ASC',
            'success' => urlencode($success)
        ];
        // Если редактируем задачу, добавляем scroll_to для восстановления позиции
        if (isset($_GET['id'])) {
            $params['scroll_to'] = $_GET['id'];
        }
        $query_string = http_build_query($params);
        header('Location: ' . ADMIN_PATH . '/admin.php?' . $query_string);
        exit;
    } catch (PDOException $e) {
        $error = "Ошибка: " . $e->getMessage();
    }
}

// Собираем параметры для кнопки "Отмена"
$cancel_params = [
    'filter_number' => isset($_GET['filter_number']) ? (int)$_GET['filter_number'] : 0,
    'sort_by' => isset($_GET['sort_by']) ? $_GET['sort_by'] : 'number',
    'sort_order' => isset($_GET['sort_order']) ? $_GET['sort_order'] : 'ASC'
];
if (isset($_GET['id'])) {
    $cancel_params['scroll_to'] = $_GET['id'];
}
$cancel_query_string = http_build_query($cancel_params);
$cancel_url = ADMIN_PATH . '/admin.php?' . $cancel_query_string;
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($task) ? 'Редактировать задачу' : 'Добавить задачу'; ?> - PixelMath</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f7fa;
            color: #333;
            line-height: 1.6;
        }

        .header {
            background: #ffffff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 20px;
            color: #333;
        }

        .header a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s;
        }

        .header a:hover {
            color: #0056b3;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
        }

        .form-container {
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .form-container h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: 600;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            border-color: #007bff;
            outline: none;
        }

        .form-group textarea {
            height: 320px;
            resize: vertical;
        }

        .form-group input[type="text"],
        .form-group input[type="number"] {
            height: 40px; /* Consistent height for single-line inputs */
        }

        .form-group img {
            max-width: 200px;
            height: auto;
            border-radius: 6px;
            margin-top: 10px;
            display: block;
        }

        .form-group .delete-image-btn {
            background: #dc3545;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 10px;
            font-weight: bold;
            transition: background 0.3s;
        }

        .form-group .delete-image-btn:hover {
            background: #c82333;
        }

        .form-group button {
            background: #007bff;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s;
            width: 100%;
        }

        .form-group button:hover {
            background: #0056b3;
        }

        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .form-actions button,
        .form-actions a {
            flex: 1;
            text-align: center;
            padding: 12px;
            border-radius: 6px;
            font-weight: bold;
            transition: background 0.3s;
            text-decoration: none;
        }

        .form-actions button {
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }

        .form-actions button:hover {
            background: #0056b3;
        }

        .form-actions .cancel-btn {
            background: #6c757d;
            color: white;
        }

        .form-actions .cancel-btn:hover {
            background: #5a6268;
        }

        .error {
            color: #dc3545;
            background: #f8d7da;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;
            animation: fadeIn 0.5s ease-in-out;
        }

        .success {
            color: #28a745;
            background: #d4edda;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 480px) {
            .container {
                padding: 0 15px;
            }

            .form-container {
                padding: 20px;
            }

            .form-container h2 {
                font-size: 20px;
            }

            .form-actions {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1><?php echo isset($task) ? 'Редактировать задачу' : 'Добавить задачу'; ?></h1>
        <a href="<?php echo ADMIN_PATH; ?>/admin.php">Назад</a>
    </div>
    <div class="container">
        <div class="form-container">
            <h2><?php echo isset($task) ? 'Редактировать задачу' : 'Добавить задачу'; ?></h2>
            <?php if (isset($error)): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <?php if (isset($success)): ?>
                <div class="success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            <form method="POST" enctype="multipart/form-data" onsubmit="return confirm('Вы уверены, что хотите сохранить изменения?');">
                <div class="form-group">
                    <label for="number">Номер задачи (1-12):</label>
                    <input type="number" name="number" id="number" value="<?php echo htmlspecialchars($task['number'] ?? ''); ?>" min="1" max="12" required>
                </div>
                <div class="form-group">
                    <label for="task_number">Task Number (опционально):</label>
                    <input type="number" name="task_number" id="task_number" value="<?php echo htmlspecialchars($task['task_number'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label for="problem">Текст задачи:</label>
                    <textarea name="problem" id="problem" required><?php echo htmlspecialchars($task['problem'] ?? ''); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="image">Изображение:</label>
                    <input type="file" name="image" id="image" accept="image/*">
                    <?php if (isset($task['image']) && $task['image']): ?>
                        <div>
                            <img src="<?php echo htmlspecialchars($task['image']); ?>" alt="Current Image">
                            <input type="text" name="image_path" id="image_path" value="<?php echo htmlspecialchars($task['image']); ?>" style="margin-top: 10px;">
                            <button type="submit" name="delete_image" class="delete-image-btn" onclick="return confirm('Вы уверены, что хотите удалить изображение?');">Удалить</button>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="solution">Решение:</label>
                    <textarea name="solution" id="solution" required><?php echo htmlspecialchars($task['solution'] ?? ''); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="answer">Ответ:</label>
                    <input type="text" name="answer" id="answer" value="<?php echo htmlspecialchars($task['answer'] ?? ''); ?>" required>
                </div>
                <div class="form-actions">
                    <button type="submit"><?php echo isset($task) ? 'Сохранить изменения' : 'Добавить задачу'; ?></button>
                    <a href="<?php echo htmlspecialchars($cancel_url); ?>" class="cancel-btn">Отмена</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>