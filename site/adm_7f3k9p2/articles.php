<?php
require_once '../config.php'; // Подключает /config.php (корневой, с DB_PATH)
require_once 'config.php'; // Подключает /adm_7f3k9p2/config.php

// Проверка авторизации
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ' . ADMIN_PATH . '/admin_login.php');
    exit;
}

// Получение уведомления
$success = isset($_GET['success']) ? urldecode($_GET['success']) : null;

// Получение списка статей
try {
    $stmt = $db->query('SELECT * FROM articles ORDER BY created_at DESC');
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error fetching articles: " . $e->getMessage());
    die("Ошибка загрузки статей: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление статьями | PixelMath</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f4f7fa; color: #333; line-height: 1.6; }
        .header { background: #ffffff; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; }
        .header h1 { font-size: 20px; color: #333; }
        .header a { color: #007bff; text-decoration: none; font-weight: bold; transition: color 0.3s; }
        .header a:hover { color: #0056b3; }
        .container { max-width: 98%; margin: 20px auto; padding: 0 20px; }
        .success { color: #28a745; background: #d4edda; padding: 10px; border-radius: 6px; margin-bottom: 20px; font-size: 14px; text-align: center; }
        .add-button { text-align: center; margin-bottom: 20px; }
        .add-button a { display: inline-flex; align-items: center; background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: bold; transition: background 0.3s; }
        .add-button a:hover { background: #218838; }
        .add-button a::before { content: '➕'; margin-right: 8px; }
        table { width: 100%; border-collapse: collapse; background: #ffffff; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 8px; overflow: hidden; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #e9ecef; }
        th { background: #007bff; color: white; font-weight: bold; }
        th a { color: white; text-decoration: none; }
        th a:hover { text-decoration: underline; }
        td { color: #333; word-break: break-word; }
        img { max-width: 100px; height: auto; border-radius: 4px; }
        .actions a { margin-right: 10px; text-decoration: none; font-weight: bold; transition: color 0.3s; }
        .actions a.edit { color: #007bff; }
        .actions a.edit:hover { color: #0056b3; }
        .actions a.edit::before { content: '✏️'; margin-right: 5px; }
        .actions a.delete { color: #dc3545; }
        .actions a.delete:hover { color: #c82333; }
        .actions a.delete::before { content: '🗑️'; margin-right: 5px; }
        @media (max-width: 768px) {
            .header { flex-direction: column; text-align: center; }
            .header h1 { margin-bottom: 10px; }
            table, thead, tbody, th, td, tr { display: block; }
            thead tr { display: none; }
            tr { margin-bottom: 15px; border: 1px solid #e9ecef; border-radius: 8px; padding: 10px; background: #ffffff; }
            td { border: none; position: relative; padding-left: 50%; }
            td::before { content: attr(data-label); position: absolute; left: 10px; font-weight: bold; color: #007bff; }
            .actions { text-align: center; margin-top: 10px; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Управление статьями</h1>
        <div>
            <a href="<?php echo ADMIN_PATH; ?>/admin.php">Назад к задачам</a>
            <a href="<?php echo ADMIN_PATH; ?>/logout.php">Выйти</a>
        </div>
    </div>
    <div class="container">
        <?php if ($success): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <div class="add-button">
            <a href="<?php echo ADMIN_PATH; ?>/edit_article.php" class="add-task">Добавить статью</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Заголовок</th>
                    <th>Slug</th>
                    <th>Краткое описание</th>
                    <th>Изображение</th>
                    <th>Дата создания</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($articles as $article): ?>
                    <tr>
                        <td data-label="Заголовок"><?php echo htmlspecialchars($article['title']); ?></td>
                        <td data-label="Slug"><?php echo htmlspecialchars($article['slug']); ?></td>
                        <td data-label="Краткое описание"><?php echo htmlspecialchars($article['excerpt']); ?></td>
                        <td data-label="Изображение">
                            <?php if ($article['image']): ?>
                                <img src="<?php echo htmlspecialchars($article['image']); ?>" alt="Изображение статьи">
                            <?php endif; ?>
                        </td>
                        <td data-label="Дата создания"><?php echo htmlspecialchars($article['created_at']); ?></td>
                        <td data-label="Действия" class="actions">
                            <a href="<?php echo ADMIN_PATH; ?>/edit_article.php?id=<?php echo $article['id']; ?>" class="edit">Редактировать</a>
                            <a href="<?php echo ADMIN_PATH; ?>/delete_article.php?id=<?php echo $article['id']; ?>" class="delete" onclick="return confirm('Вы уверены?')">Удалить</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>