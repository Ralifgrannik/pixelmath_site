<?php
require_once '../config.php'; // Подключает /config.php (корневой, с DB_PATH)
require_once 'config.php'; // Подключает /adm_7f3k9p2/config.php

// Проверка авторизации
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ' . ADMIN_PATH . '/admin_login.php');
    exit;
}

$article = ['id' => null, 'title' => '', 'slug' => '', 'excerpt' => '', 'content' => '', 'image' => ''];
$error = '';
$success = '';

if (isset($_GET['id'])) {
    try {
        $stmt = $db->prepare('SELECT * FROM articles WHERE id = :id');
        $stmt->execute(['id' => (int)$_GET['id']]);
        $article = $stmt->fetch(PDO::FETCH_ASSOC) ?: $article;
    } catch (PDOException $e) {
        error_log("Error fetching article: " . $e->getMessage());
        $error = "Ошибка загрузки статьи.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $excerpt = trim($_POST['excerpt'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if (empty($title) || empty($slug) || empty($excerpt) || empty($content)) {
        $error = "Все поля обязательны.";
    } else {
        try {
            $image = $article['image'];
            if ($_FILES['image']['name']) {
                $upload_file = UPLOAD_DIR . basename($_FILES['image']['name']);
                if (!is_dir(UPLOAD_DIR)) {
                    mkdir(UPLOAD_DIR, 0755, true);
                }
                if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_file)) {
                    $image = UPLOAD_URL . basename($_FILES['image']['name']);
                } else {
                    throw new Exception("Ошибка загрузки изображения.");
                }
            }

            // Проверка уникальности slug
            $stmt = $db->prepare('SELECT id FROM articles WHERE slug = :slug AND id != :id');
            $stmt->execute(['slug' => $slug, 'id' => $article['id'] ?: 0]);
            if ($stmt->fetch()) {
                throw new Exception("Slug уже занят, выберите другой.");
            }

            if ($article['id']) {
                $stmt = $db->prepare('UPDATE articles SET title = :title, slug = :slug, excerpt = :excerpt, content = :content, image = :image WHERE id = :id');
                $stmt->execute([
                    'title' => $title,
                    'slug' => $slug,
                    'excerpt' => $excerpt,
                    'content' => $content,
                    'image' => $image,
                    'id' => $article['id']
                ]);
            } else {
                $stmt = $db->prepare('INSERT INTO articles (title, slug, excerpt, content, image, created_at) VALUES (:title, :slug, :excerpt, :content, :image, datetime("now"))');
                $stmt->execute([
                    'title' => $title,
                    'slug' => $slug,
                    'excerpt' => $excerpt,
                    'content' => $content,
                    'image' => $image
                ]);
            }
            $success = "Статья сохранена успешно!";
            header('Location: ' . ADMIN_PATH . '/articles.php?success=' . urlencode($success));
            exit;
        } catch (PDOException $e) {
            error_log("Database error in edit_article: " . $e->getMessage());
            $error = "Ошибка БД: " . $e->getMessage();
        } catch (Exception $e) {
            error_log("Error: " . $e->getMessage());
            $error = $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $article['id'] ? 'Редактировать статью' : 'Добавить статью'; ?> | Порешаем?</title>
    <link rel="stylesheet" href="https://uicdn.toast.com/editor/latest/toastui-editor.min.css">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f4f7fa; color: #333; line-height: 1.6; padding: 20px; }
        .header { background: #ffffff; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-radius: 8px; }
        .header h1 { font-size: 20px; color: #333; }
        .header a { color: #007bff; text-decoration: none; font-weight: bold; }
        .header a:hover { color: #0056b3; }
        .container { max-width: 98%; margin: 0 auto; padding: 0 20px; }
        .error { color: #dc3545; background: #f8d7da; padding: 10px; border-radius: 6px; margin-bottom: 20px; text-align: center; }
        .success { color: #28a745; background: #d4edda; padding: 10px; border-radius: 6px; margin-bottom: 20px; text-align: center; }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 0.5rem; }
        .form-group input, .form-group textarea { width: 100%; padding: 0.75rem; border: 1px solid #ccc; border-radius: 6px; font-family: inherit; }
        .form-group textarea { min-height: 150px; resize: vertical; }
        .save-btn { background: #28a745; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; }
        .save-btn:hover { background: #218838; }
        img { max-width: 200px; height: auto; border-radius: 4px; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1><?php echo $article['id'] ? 'Редактировать статью' : 'Добавить статью'; ?></h1>
        <a href="<?php echo ADMIN_PATH; ?>/articles.php">Назад к статьям</a>
    </div>
    <div class="container">
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Заголовок</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($article['title']); ?>" required>
            </div>
            <div class="form-group">
                <label for="slug">Slug (латиницей, для URL)</label>
                <input type="text" id="slug" name="slug" value="<?php echo htmlspecialchars($article['slug']); ?>" required>
            </div>
            <div class="form-group">
                <label for="excerpt">Краткое описание (excerpt)</label>
                <textarea id="excerpt" name="excerpt" required><?php echo htmlspecialchars($article['excerpt']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="content">Содержимое (Markdown)</label>
                <div id="editor"></div>
                <input type="hidden" id="content" name="content" value="<?php echo htmlspecialchars($article['content']); ?>">
            </div>
            <div class="form-group">
                <label for="image">Изображение (опционально)</label>
                <input type="file" id="image" name="image" accept="image/*">
                <?php if ($article['image']): ?>
                    <img src="<?php echo htmlspecialchars($article['image']); ?>" alt="Текущее изображение">
                <?php endif; ?>
            </div>
            <button type="submit" class="save-btn">Сохранить</button>
        </form>
    </div>
    <script src="https://uicdn.toast.com/editor/latest/toastui-editor-all.min.js"></script>
    <script>
        // Транслитерация для slug
        function transliterate(str) {
            const map = {
                'а': 'a', 'б': 'b', 'в': 'v', 'г': 'g', 'д': 'd', 'е': 'e', 'ё': 'yo', 'ж': 'zh', 'з': 'z',
                'и': 'i', 'й': 'y', 'к': 'k', 'л': 'l', 'м': 'm', 'н': 'n', 'о': 'o', 'п': 'p', 'р': 'r',
                'с': 's', 'т': 't', 'у': 'u', 'ф': 'f', 'х': 'kh', 'ц': 'ts', 'ч': 'ch', 'ш': 'sh', 'щ': 'sch',
                'ы': 'y', 'э': 'e', 'ю': 'yu', 'я': 'ya', ' ': '-', 'ь': '', 'ъ': ''
            };
            return str.toLowerCase().split('').map(char => map[char] || char).join('').replace(/[^a-z0-9-]/g, '').replace(/-+/g, '-').replace(/^-|-$/g, '');
        }

        // Автозаполнение slug
        const titleInput = document.getElementById('title');
        const slugInput = document.getElementById('slug');
        titleInput.addEventListener('input', () => {
            if (!slugInput.dataset.edited || slugInput.dataset.edited === 'false') {
                slugInput.value = transliterate(titleInput.value);
                slugInput.dataset.edited = 'false';
            }
        });
        slugInput.addEventListener('input', () => {
            slugInput.dataset.edited = 'true';
        });

        // Инициализация Toast UI Editor
        const editor = new toastui.Editor({
            el: document.querySelector('#editor'),
            height: '500px',
            initialEditType: 'markdown',
            previewStyle: 'vertical',
            initialValue: <?php echo json_encode($article['content']); ?>,
            toolbarItems: [
                ['heading', 'bold', 'italic', 'strike'],
                ['hr', 'quote'],
                ['ul', 'ol', 'task', 'indent', 'outdent'],
                ['table', 'image', 'link'],
                ['code', 'codeblock']
            ],
            hooks: {
                addImageBlobHook: (blob, callback) => {
                    const formData = new FormData();
                    formData.append('image', blob);
                    fetch('<?php echo ADMIN_PATH; ?>/upload_image.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.url) {
                            callback(data.url, blob.name);
                        } else {
                            alert('Ошибка загрузки изображения: ' + (data.error || 'Неизвестная ошибка'));
                        }
                    })
                    .catch(error => {
                        alert('Ошибка загрузки изображения: ' + error);
                    });
                }
            }
        });

        // Синхронизация контента с hidden input
        editor.on('change', () => {
            document.getElementById('content').value = editor.getMarkdown();
        });
    </script>
</body>
</html>