<?php
require_once 'config.php';

// Проверка авторизации
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ' . ADMIN_PATH . '/admin_login.php');
    exit;
}

// Получение уведомления
$success = isset($_GET['success']) ? urldecode($_GET['success']) : null;

// Фильтр по номеру
$filter_number = isset($_GET['filter_number']) ? (int)$_GET['filter_number'] : 0;

// Сортировка
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'number';
$sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'ASC';
$allowed_sort = ['number', 'task_number'];
if (!in_array($sort_by, $allowed_sort)) {
    $sort_by = 'number';
}
$sort_order = strtoupper($sort_order) === 'DESC' ? 'DESC' : 'ASC';

// Построение запроса
$query = 'SELECT * FROM tasks_app_task';
if ($filter_number > 0) {
    $query .= ' WHERE number = :number';
}
$query .= ' ORDER BY ' . $sort_by . ' ' . $sort_order;

try {
    $stmt = $db->prepare($query);
    if ($filter_number > 0) {
        $stmt->bindValue(':number', $filter_number, PDO::PARAM_INT);
    }
    $stmt->execute();
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}

// Функция для генерации ссылки сортировки
function sortLink($column) {
    global $sort_by, $sort_order, $filter_number;
    $new_order = ($sort_by === $column && $sort_order === 'ASC') ? 'DESC' : 'ASC';
    $params = http_build_query(['sort_by' => $column, 'sort_order' => $new_order, 'filter_number' => $filter_number]);
    return '?' . $params;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админка - PixelMath</title>
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

        th a {
            color: white;
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
            max-width: 98%;
            margin: 20px auto;
            padding: 0 20px;
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

        .add-button {
            text-align: center;
            margin-bottom: 20px;
        }

        .add-button a {
            display: inline-flex;
            align-items: center;
            background: #28a745;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            transition: background 0.3s;
        }

        .add-button a:hover {
            background: #218838;
        }

        .add-button a::before {
            content: '➕';
            margin-right: 8px;
        }

        .filter-form {
            background: #ffffff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            text-align: center;
        }

        .filter-form label {
            margin-right: 10px;
            font-weight: bold;
            color: #333;
        }

        .filter-form select {
            padding: 8px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        .filter-form button {
            background: #007bff;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-left: 10px;
            font-weight: bold;
            transition: background 0.3s;
        }

        .filter-form button:hover {
            background: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #ffffff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }

        th {
            background: #007bff;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }

        th:hover {
            background: #0056b3;
        }

        td {
            color: #333;
            word-break: break-word;
        }

        th:nth-child(1), td:nth-child(1) { /* Номер */
            width: 8%;
            min-width: 80px;
        }

        th:nth-child(2), td:nth-child(2) { /* Task Number */
            width: 10%;
            min-width: 100px;
        }

        th:nth-child(3), td:nth-child(3) { /* Задача */
            width: 25%;
        }

        th:nth-child(4), td:nth-child(4) { /* Изображение */
            width: 15%;
        }

        th:nth-child(5), td:nth-child(5) { /* Решение */
            width: 25%;
        }

        th:nth-child(6), td:nth-child(6) { /* Ответ */
            width: 10%;
            min-width: 100px;
        }

        th:nth-child(7), td:nth-child(7) { /* Действия */
            width: 15%;
            min-width: 150px;
        }

        .solution-text {
            line-height: 1.8;
            cursor: pointer;
            position: relative;
        }

        .solution-text:hover::after {
            content: '✏️ Нажмите, чтобы редактировать';
            position: absolute;
            bottom: 5px;
            right: 5px;
            font-size: 12px;
            color: #007bff;
            background: #f0f0f0;
            padding: 2px 5px;
            border-radius: 3px;
        }

        .solution-text textarea {
            width: 100%;
            min-height: 400px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-family: inherit;
            font-size: 14px;
            resize: vertical;
        }

        .solution-text .edit-buttons {
            margin-top: 10px;
            text-align: right;
        }

        .solution-text .edit-buttons button {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s;
        }

        .solution-text .edit-buttons .save-btn {
            background: #28a745;
            color: white;
            margin-right: 10px;
        }

        .solution-text .edit-buttons .save-btn:hover {
            background: #218838;
        }

        .solution-text .edit-buttons .cancel-btn {
            background: #dc3545;
            color: white;
        }

        .solution-text .edit-buttons .cancel-btn:hover {
            background: #c82333;
        }

        img {
            max-width: 100px;
            height: auto;
            border-radius: 4px;
        }

        .actions a {
            margin-right: 10px;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s;
            white-space: nowrap;
        }

        .actions a.edit {
            color: #007bff;
        }

        .actions a.edit:hover {
            color: #0056b3;
        }

        .actions a.edit::before {
            content: '✏️';
            margin-right: 5px;
        }

        .actions a.delete {
            color: #dc3545;
        }

        .actions a.delete:hover {
            color: #c82333;
        }

        .actions a.delete::before {
            content: '🗑️';
            margin-right: 5px;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                text-align: center;
            }

            .header h1 {
                margin-bottom: 10px;
            }

            table, thead, tbody, th, td, tr {
                display: block;
            }

            thead tr {
                display: none;
            }

            tr {
                margin-bottom: 15px;
                border: 1px solid #e9ecef;
                border-radius: 8px;
                padding: 10px;
                background: #ffffff;
            }

            td {
                border: none;
                position: relative;
                padding-left: 50%;
            }

            td::before {
                content: attr(data-label);
                position: absolute;
                left: 10px;
                font-weight: bold;
                color: #007bff;
            }

            td:nth-child(1), td:nth-child(2), td:nth-child(6), td:nth-child(7) {
                padding-left: 50%;
            }

            .actions {
                text-align: center;
                margin-top: 10px;
            }

            .solution-text {
                line-height: 1.6;
            }

            .solution-text:hover::after {
                display: none;
            }

            .solution-text textarea {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Админка PixelMath</h1>
        <div>
            <a href="<?php echo ADMIN_PATH; ?>/articles.php">Управление статьями</a>
            <a href="<?php echo ADMIN_PATH; ?>/logout.php">Выйти</a>
        </div>
    </div>
    <div class="container">
        <?php if ($success): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <div class="add-button">
            <a href="<?php echo ADMIN_PATH; ?>/edit_task.php" class="add-task">Добавить задачу</a>
            <a href="<?php echo ADMIN_PATH; ?>/edit_article.php" class="add-task">Добавить статью</a>
        </div>
        <div class="filter-form">
            <form method="GET">
                <label for="filter_number">Фильтр по номеру:</label>
                <select name="filter_number" id="filter_number">
                    <option value="0">Все</option>
                    <?php for ($i = 1; $i <= 12; $i++): ?>
                        <option value="<?php echo $i; ?>" <?php echo $filter_number == $i ? 'selected' : ''; ?>><?php echo $i; ?></option>
                    <?php endfor; ?>
                </select>
                <button type="submit">Применить</button>
            </form>
        </div>
        <table>
            <thead>
                <tr>
                    <th><a href="<?php echo sortLink('number'); ?>">Номер</a></th>
                    <th><a href="<?php echo sortLink('task_number'); ?>">Task Number</a></th>
                    <th>Задача</th>
                    <th>Изображение</th>
                    <th>Решение</th>
                    <th>Ответ</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tasks as $task): ?>
                    <tr id="task-<?php echo $task['id']; ?>">
                        <td data-label="Номер"><?php echo htmlspecialchars($task['number']); ?></td>
                        <td data-label="Task Number"><?php echo htmlspecialchars($task['task_number'] ?? ''); ?></td>
                        <td data-label="Задача"><?php echo htmlspecialchars($task['problem']); ?></td>
                        <td data-label="Изображение">
                            <?php if ($task['image']): ?>
                                <img src="<?php echo htmlspecialchars($task['image']); ?>" alt="Task Image">
                            <?php endif; ?>
                        </td>
                        <td data-label="Решение" class="solution-text" data-id="<?php echo $task['id']; ?>">
                            <div class="solution-content"><?php echo nl2br(htmlspecialchars($task['solution'])); ?></div>
                        </td>
                        <td data-label="Ответ"><?php echo htmlspecialchars($task['answer']); ?></td>
                        <td data-label="Действия" class="actions">
                            <a href="<?php echo ADMIN_PATH; ?>/edit_task.php?id=<?php echo $task['id']; ?>&filter_number=<?php echo $filter_number; ?>&sort_by=<?php echo $sort_by; ?>&sort_order=<?php echo $sort_order; ?>" class="edit">Редактировать</a>
                            <a href="<?php echo ADMIN_PATH; ?>/delete_task.php?id=<?php echo $task['id']; ?>" class="delete" onclick="return confirm('Вы уверены?')">Удалить</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Сохраняем позицию прокрутки и параметры фильтра перед переходом
            const editLinks = document.querySelectorAll('.edit, .add-task');
            editLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    const scrollPosition = window.scrollY;
                    const filterNumber = document.querySelector('#filter_number').value;
                    const urlParams = new URLSearchParams(window.location.search);
                    const state = {
                        scrollPosition: scrollPosition,
                        filterNumber: filterNumber,
                        sortBy: urlParams.get('sort_by') || 'number',
                        sortOrder: urlParams.get('sort_order') || 'ASC'
                    };
                    sessionStorage.setItem('adminState', JSON.stringify(state));
                });
            });

            // Восстанавливаем позицию прокрутки и фильтры
            const savedState = sessionStorage.getItem('adminState');
            const urlParams = new URLSearchParams(window.location.search);
            const scrollToTaskId = urlParams.get('scroll_to');

            if (savedState) {
                const state = JSON.parse(savedState);
                const currentFilter = urlParams.get('filter_number') || '0';
                const currentSortBy = urlParams.get('sort_by') || 'number';
                const currentSortOrder = urlParams.get('sort_order') || 'ASC';

                if (
                    currentFilter === state.filterNumber &&
                    currentSortBy === state.sortBy &&
                    currentSortOrder === state.sortOrder
                ) {
                    if (scrollToTaskId) {
                        const taskRow = document.getElementById('task-' + scrollToTaskId);
                        if (taskRow) {
                            taskRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    } else {
                        window.scrollTo(0, state.scrollPosition);
                    }
                }
                sessionStorage.removeItem('adminState');
            }

            // Обработчик для редактирования решения
            const solutionCells = document.querySelectorAll('.solution-text');
            solutionCells.forEach(cell => {
                cell.addEventListener('click', function() {
                    if (cell.querySelector('textarea')) return;

                    const solutionContent = cell.querySelector('.solution-content');
                    const originalText = solutionContent.innerText;
                    const taskId = cell.getAttribute('data-id');

                    const textarea = document.createElement('textarea');
                    textarea.value = originalText;
                    solutionContent.style.display = 'none';
                    cell.appendChild(textarea);

                    const buttonsDiv = document.createElement('div');
                    buttonsDiv.classList.add('edit-buttons');

                    const saveBtn = document.createElement('button');
                    saveBtn.textContent = 'Сохранить';
                    saveBtn.classList.add('save-btn');

                    const cancelBtn = document.createElement('button');
                    cancelBtn.textContent = 'Отмена';
                    cancelBtn.classList.add('cancel-btn');

                    buttonsDiv.appendChild(saveBtn);
                    buttonsDiv.appendChild(cancelBtn);
                    cell.appendChild(buttonsDiv);

                    saveBtn.addEventListener('click', function() {
                        const newSolution = textarea.value;

                        fetch('<?php echo ADMIN_PATH; ?>/update_solution.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                id: taskId,
                                solution: newSolution
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                solutionContent.innerHTML = newSolution.replace(/\n/g, '<br>');
                                alert(data.message);
                            } else {
                                alert('Ошибка: ' + data.message);
                            }
                            textarea.remove();
                            buttonsDiv.remove();
                            solutionContent.style.display = 'block';
                        })
                        .catch(error => {
                            alert('Ошибка при сохранении: ' + error);
                            textarea.remove();
                            buttonsDiv.remove();
                            solutionContent.style.display = 'block';
                        });
                    });

                    cancelBtn.addEventListener('click', function() {
                        textarea.remove();
                        buttonsDiv.remove();
                        solutionContent.style.display = 'block';
                    });
                });
            });
        });
    </script>
</body>
</html>