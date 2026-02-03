<?php
require_once 'config.php'; // Подключает корневой config.php

$slug = trim($_GET['slug'] ?? '');
if (empty($slug)) {
    http_response_code(404);
    die('Страница не найдена');
}

try {
    $stmt = $db->prepare('SELECT * FROM articles WHERE slug = :slug');
    $stmt->execute(['slug' => $slug]);
    $article = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$article) {
        http_response_code(404);
        die('Статья не найдена');
    }
} catch (PDOException $e) {
    error_log("Error fetching article: " . $e->getMessage());
    http_response_code(500);
    die('Ошибка загрузки статьи');
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($article['title']); ?> | Порешаем?</title>
    <meta name="description" content="<?php echo htmlspecialchars($article['excerpt']); ?>">
    <meta name="keywords" content="ЕГЭ математика, статьи ЕГЭ, подготовка к ЕГЭ">
    <link rel="stylesheet" href="/static/CSS/main.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Anonymous+Pro:ital,wght@0,400;0,700;1,400&display=swap">
    <script src="https://cdn.jsdelivr.net/npm/marked@4.0.0/marked.min.js"></script>
    <style>
        body {
            background: #f9fafb; /* Softer, light gray background */
            color: #2c3e50;
            font-family: 'Anonymous Pro', monospace;
            margin: 0;
            padding: 0;
            overflow-y: scroll;
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        body::-webkit-scrollbar {
            display: none;
        }
        header {
            width: 100%;
            padding: 20px 0 0;
            position: fixed;
            top: 0;
            z-index: 100;
        }
        .nav-container {
            width: 40%;
            margin: 0 auto;
            background: #f8f9fa;
            border: 3px solid #2c3e50;
            box-shadow: 4px 4px 0 #2c3e50;
            border-radius: 90px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 5px 20px;
        }
        .nav-left, .nav-right {
            display: flex;
            gap: 10px;
        }
        .nav-logo {
            width: 40px;
            height: 40px;
            background: #ffffff;
            border: 3px solid #2c3e50;
            border-radius: 50%;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .nav-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .nav-button {
            padding: 10px 20px;
            background: #ffffff;
            color: #2c3e50;
            font-family: 'Anonymous Pro', monospace;
            font-size: 1em;
            font-weight: 700;
            border: 3px solid #2c3e50;
            border-radius: 20px;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .nav-button:hover {
            background: #0db1a3; /* Original hover color */
            color: #ffffff;
            transform: translateY(-2px);
        }
        .nav-button.active {
            background: #0db1a3;
            color: #ffffff;
        }
        .container {
            max-width: 900px;
            margin: 100px auto 40px;
            padding: 20px;
            text-align: left;
        }
        h1 {
            font-size: 2.5em;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 20px;
            text-align: center;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.05);
        }
        .article-image {
            display: block;
            width: 100%;
            max-width: 900px;
            max-height: 300px;
            aspect-ratio: 16 / 9; /* Rectangular, wider than tall */
            object-fit: cover;
            border-radius: 10px;
            margin: 20px auto;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .article-content {
            font-size: 1.1em;
            color: #343a40;
            line-height: 1.8;
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
        .article-content img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            margin: 15px auto;
            display: block;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .article-content p, .article-content ul, .article-content ol {
            margin-bottom: 15px;
        }
        .article-content h2, .article-content h3 {
            font-weight: 700;
            color: #2c3e50;
            margin: 20px 0 10px;
        }
        .back-button {
            display: inline-block;
            padding: 12px 24px;
            background: #34d399; /* Friendlier green */
            color: #ffffff;
            font-size: 1.1em;
            font-weight: 700;
            border: 2px solid #2c3e50;
            border-radius: 25px;
            text-decoration: none;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }
        .back-button:hover {
            background: #2fb383;
            transform: translateY(-2px);
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .container, .article-content {
            animation: fadeInUp 0.5s ease forwards;
        }
        @media (max-width: 1200px) {
            .nav-container { width: 55%; }
        }
        @media (max-width: 900px) {
            .container { padding: 15px; margin: 80px 20px 20px; }
            .nav-container { width: 70%; margin-bottom: 10px; }
            .nav-logo { width: 50px; height: 50px; display: none; }
            .nav-button { padding: 8px 15px; font-size: 0.9em; }
            h1 { font-size: 2.2em; }
            .article-content { font-size: 1em; }
            .article-image { max-width: 100%; }
        }
        @media (max-width: 600px) {
            h1 { font-size: 1.8em; }
            .article-content { font-size: 0.95em; }
            .container { padding: 15px; }
            .nav-container { flex-wrap: wrap; justify-content: center; gap: 10px; }
            .nav-left, .nav-right { flex: 1 1 100%; justify-content: center; }
        }
    </style>
</head>
<body>
    <header>
        <div class="nav-container">
            <div class="nav-left">
                <a href="/index.php" class="nav-button">🏠 Главная</a>
                <a href="/articles.php" class="nav-button active">📝 Статьи</a>
            </div>
            <div class="nav-logo">
                <img src="/static/pictures/favicon.ico" alt="Логотип">
            </div>
            <div class="nav-right">
                <a href="#theory" class="nav-button">Теория 📚</a>
                <a href="/math.php" class="nav-button">Практика 🛠️</a>
                <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']): ?>
                    <a href="/adm_7f3k9p2/admin.php" class="nav-button">Админка ⚙️</a>
                <?php endif; ?>
            </div>
        </div>
    </header>
    <div class="container">
        <h1><?php echo htmlspecialchars($article['title']); ?></h1>
        <?php if ($article['image']): ?>
            <img src="<?php echo htmlspecialchars($article['image']); ?>" alt="<?php echo htmlspecialchars($article['title']); ?>" class="article-image">
        <?php endif; ?>
        <div class="article-content" id="content"></div>
    </div>
    <script>
        document.getElementById('content').innerHTML = marked.parse(<?php echo json_encode($article['content']); ?>);
    </script>
</body>
</html>