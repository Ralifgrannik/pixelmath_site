<?php
require_once 'config.php'; // Подключает корневой config.php

try {
    $stmt = $db->query('SELECT title, slug, excerpt, image FROM articles ORDER BY created_at DESC LIMIT 3');
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Query failed: " . $e->getMessage());
    $articles = []; // Пустой массив, чтобы страница не падала
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Статьи | Порешаем?</title>
    <meta name="description" content="Подготовка к ЕГЭ по математике: выберите задание для изучения теории и практики с видеоуроками.">
    <meta name="keywords" content="ЕГЭ математика, статьи ЕГЭ, задания ЕГЭ, подготовка к ЕГЭ, видеоуроки">
    <link rel="stylesheet" href="/static/CSS/main.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Anonymous+Pro:ital,wght@0,400;0,700;1,400&display=swap">
    <style>
        body {
            background: #ffffff;
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
            background: #0db1a3;
            color: #ffffff;
            transform: translateY(-2px);
        }
        .nav-button.active {
            background: #0db1a3;
            color: #ffffff;
        }
        .container {
            max-width: 1200px;
            margin: 80px auto 40px;
            padding: 20px;
            text-align: center;
        }
        h1 {
            font-size: 2.8em;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 20px;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .section a {
            
            text-decoration: none;

        }
        h2 {
            font-size: 1.8em;
            font-weight: 700;
            color: #2c3e50;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 10px;
            margin: 30px 0 20px;
        }
        p {
            font-size: 1.1em;
            color: #343a40;
            line-height: 1.7;
            margin: 5px 0;
        }
        .note {
            background: #f8f9fa;
            border: 3px solid #2c3e50;
            box-shadow: 4px 4px 0 #2c3e50;
            padding: 20px;
            margin: 20px auto;
            max-width: 800px;
            border-radius: 12px;
            text-align: left;
            animation: fadeInUp 0.5s ease forwards;
        }
        .section {
            margin-bottom: 50px;
        }
        .buttons-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 15px;
            margin: 30px 0;
        }
        .retro {
            display: block;
            padding: 15px;
            background: #ffffff;
            color: #2c3e50;
            font-family: 'Anonymous Pro', monospace;
            font-size: 1.1em;
            font-weight: 700;
            border: 3px solid #2c3e50;
            box-shadow: 4px 4px 0 #2c3e50;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
        }
        .retro:hover {
            background: #0db1a3;
            color: #ffffff;
            box-shadow: 6px 6px 0 #2c3e50;
            transform: translateY(-2px);
        }
        .video-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin: 20px 0;
        }
        .video-container iframe {
            width: 100%;
            max-width: 600px;
            height: 340px;
            border: 3px solid #2c3e50;
            box-shadow: 4px 4px 0 #2c3e50;
            border-radius: 12px;
            transition: transform 0.3s ease;
        }
        .video-container iframe:hover {
            transform: translateY(-4px);
        }
        .articles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        .article-card {
            background: #f8f9fa;
            border: 3px solid #2c3e50;
            box-shadow: 4px 4px 0 #2c3e50;
            border-radius: 12px;
            text-decoration: none;
            color: #2c3e50;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .article-card:hover {
            transform: translateY(-4px);
            box-shadow: 6px 6px 0 #2c3e50;
        }
        .article-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-bottom: 3px solid #2c3e50;
        }
        
        
        .section {
             padding: 20px 40px;
             margin-bottom: 0;
        }
    
    
    
        .article-content {
            padding: 15px;
        }
        .article-content h3 {
            font-size: 1.4em;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .article-content p {
            font-size: 1em;
            color: #343a40;
        }
        .back-button {
            display: inline-block;
            padding: 12px 24px;
            background: #0db1a3;
            color: #ffffff;
            font-size: 1.1em;
            font-weight: 700;
            border: 3px solid #2c3e50;
            box-shadow: 4px 4px 0 #2c3e50;
            border-radius: 20px;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .back-button:hover {
            background: #0a9286;
            transform: translateY(-2px);
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
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
            h2 { font-size: 1.6em; }
            p { font-size: 1em; }
            .video-container iframe { height: 240px; }
            .buttons-grid { grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); }
        }
        @media (max-width: 600px) {
            h1 { font-size: 1.8em; }
            h2 { font-size: 1.4em; }
            p { font-size: 0.95em; }
            .video-container iframe { height: 200px; }
            .note { padding: 15px; }
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
        <h1>Статьи и задания ЕГЭ</h1>
        <div class="note">
            <p>Длинные видео лучше смотреть по частям — выделяйте 15-минутные отрезки и делайте заметки. Регулярно повторяйте материал, используя карточки для ключевых понятий.</p>
            <p>Если что-то непонятно, начните с запоминания правил — понимание придет с практикой.</p>
            <p>Все материалы из бесплатных источников. Видео принадлежат их авторам.</p>
        </div>

        <div class="section">
            <h2>Выберите задание</h2>
            <div class="buttons-grid">
                <?php for ($i = 1; $i <= 18; $i++): ?>
                    <a href="/Task_articles/task<?php echo $i; ?>.html" class="retro">Задание <?php echo $i; ?></a>
                <?php endfor; ?>
            </div>
        </div>

        <div class="section">
            <h2>Универсальный гайд</h2>
            <div class="video-container">
                <iframe src="https://www.youtube.com/embed/d1LXbrTHbFk" title="Универсальный гайд" allowfullscreen></iframe>
            </div>
        </div>

        <div class="section">
            <h2>Дополнительные ресурсы</h2>
            <p>Изучите полезные материалы на рекомендованных каналах:</p>
            <div class="articles-grid">
                <?php foreach ($articles as $article): ?>
                    <a href="/article/<?php echo htmlspecialchars($article['slug']); ?>" class="article-card">
                        <img src="<?php echo htmlspecialchars($article['image'] ?: 'https://source.unsplash.com/400x200/?math,' . urlencode($article['title'])); ?>" alt="<?php echo htmlspecialchars($article['title']); ?>">
                        <div class="article-content">
                            <h3><?php echo htmlspecialchars($article['title']); ?></h3>
                            <p><?php echo htmlspecialchars($article['excerpt']); ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
                <?php if (count($articles) < 3): ?>
                    <?php for ($i = count($articles); $i < 3; $i++): ?>
                        <a href="/articles.php" class="article-card">
                            <img src="https://source.unsplash.com/400x200/?math" alt="Узнайте больше">
                            <div class="article-content">
                                <h3>Узнайте больше</h3>
                                <p>Прочитайте наши статьи по подготовке к ЕГЭ.</p>
                            </div>
                        </a>
                    <?php endfor; ?>
                <?php endif; ?>
            </div>
            <div class="video-container">
                <iframe src="https://www.youtube.com/embed/-YaPMTxp1Z0" title="Советы и дополнительные материалы" allowfullscreen></iframe>
            </div>
            <a href="/" class="back-button">Вернуться на главную</a>
        </div>
    </div>
</body>
</html>