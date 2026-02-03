<?php
require_once 'config.php';
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <script src="/static/scripts/protect.js"></script>
    
    <link rel="icon" href="/static/pictures/favicon.ico" type="image/x-icon">
    <link rel="icon" href="https://pixelmath.ru/static/pictures/favicon.ico" type="image/x-icon">
    <!-- Yandex.Metrika counter -->
    <script type="text/javascript" >
   (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
   m[i].l=1*new Date();
   for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
   k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

   ym(101089544, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true
   });
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/101089544" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <!-- /Yandex.Metrika counter -->
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Порешаем? | Подготовка к ЕГЭ по математике бесплатно</title>
    <meta name="description" content="Бесплатная подготовка к ЕГЭ по математике: задания, решения, практика. Начни прямо сейчас и улучши свои результаты!">
    <meta name="keywords" content="ЕГЭ математика, подготовка к ЕГЭ, задания ЕГЭ, решения ЕГЭ, бесплатные уроки математики, задания ЕГЭ математика, практика ЕГЭ, решения задач ЕГЭ, подготовка к ЕГЭ, математика онлайн">
    <link rel="stylesheet" href="/static/CSS/main.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Anonymous+Pro:ital,wght@0,400;0,700;1,400&display=swap">
        
    <meta property="og:title" content="Порешаем? Бесплатная подготовка к ЕГЭ по математике">
    <meta property="og:description" content="Решай задания ЕГЭ по математике онлайн бесплатно и готовься к экзамену!">
    <meta property="og:image" content="https://pixelmath.ru/static/pictures/og-image.jpg">
    <meta property="og:url" content="https://pixelmath.ru/">
    <meta property="og:type" content="website">
    
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "WebPage",
            "name": "Порешаем? Подготовка к ЕГЭ по математике",
            "description": "Бесплатная подготовка к ЕГЭ по математике с заданиями и решениями.",
            "url": "https://pixelmath.ru/"
        }
    </script>
    
    <style>
        .section_articles {
            padding: 60px 40px;
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
        }
        .section_articles h2, .section_articles h3 {
            font-family: 'Anonymous Pro', monospace;
            font-size: 2.5em;
            color: #2c3e50;
            margin-bottom: 30px;
            font-weight: 700;
        }
        .articles-container {
            padding-top: 10px;
            display: flex;
            gap: 20px;
            overflow-x: hidden;
            position: relative;
            scroll-behavior: smooth;
        }
        .articles-group {
            display: flex;
            gap: 20px;
            flex-shrink: 0;
            width: 100%;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.5s ease forwards;
        }
        .article-block-large {
            flex: 0.33;
            background: #ffffff;
            border: 2px solid #2c3e50;
            border-radius: 12px;
            padding: 20px;
            text-align: left;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.3s ease, background 0.3s ease;
        }
        .article-block-large:hover {
            transform: translateY(-5px);
            background: #e6f0fa;
        }
        .article-block-small {
            flex: 1;
            background: #ffffff;
            border: 2px solid #2c3e50;
            border-radius: 12px;
            padding: 15px;
            text-align: left;
            height: 190px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.3s ease, background 0.3s ease;
        }
        .article-right {
            flex: 0.67;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        .article-block-small:hover {
            transform: translateY(-5px);
            background: #e6f0fa;
        }
        .article-title {
            font-family: 'Anonymous Pro', monospace;
            font-size: 1.6em;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        .article-preview {
            font-family: 'Anonymous Pro', monospace;
            font-size: 1em;
            color: #555;
            margin-bottom: 10px;
            flex-grow: 1;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
        }
        .article-button {
            display: inline-block;
            padding: 10px 20px;
            background: #0db1a3;
            color: #ffffff;
            font-family: 'Anonymous Pro', monospace;
            font-size: 1em;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.3s ease;
            align-self: flex-start;
            text-decoration: none;
        }
        .article-button:hover {
            background: #0a9286;
            transform: scale(1.05);
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .modal-content {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 20px;
            max-width: 600px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            font-family: 'Anonymous Pro', monospace;
            position: relative;
            transform: translateX(-100%);
            opacity: 0;
            transition: transform 0.3s ease, opacity 0.3s ease;
        }
        .modal-content.open {
            transform: translateX(0);
            opacity: 1;
        }
        .modal-content h3 {
            font-size: 1.8em;
            color: #2c3e50;
            margin-bottom: 15px;
            font-weight: 700;
        }
        .modal-content p {
            font-size: 1em;
            color: #343a40;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .modal-close {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 1.5em;
            color: #2c3e50;
            cursor: pointer;
            transition: color 0.3s ease;
        }
        .modal-close:hover {
            color: #0db1a3;
        }
        .nav-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }
        .nav-button {
            display: inline-block;
            padding: 12px 24px;
            background: #2c3e50;
            color: #ffffff;
            font-family: 'Anonymous Pro', monospace;
            font-size: 1em;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease, box-shadow 0.3s ease;
        }
        .nav-button:hover {
            background: #3b5066;
            box-shadow: 0 0 10px rgba(13, 177, 163, 0.5);
        }
        .nav-button:disabled {
            background: #cccccc;
            color: #666666;
            cursor: not-allowed;
            box-shadow: none;
        }
        .video-feed {
            display: flex;
            overflow-x: auto;
            gap: 20px;
            padding: 20px 0;
            scrollbar-width: thin;
        }
        .video-card {
            flex: 0 0 auto;
            background: #ffffff;
            border: 2px solid #2c3e50;
            border-radius: 12px;
            padding: 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: transform 0.3s ease, background 0.3s ease;
        }
        .video-card:hover {
            transform: translateY(-5px);
            background: #e6f0fa;
        }
        .video-feed iframe {
            width: 300px;
            height: 169px;
            border: none;
            border-radius: 8px;
            object-fit: contain;
            transition: transform 0.3s ease;
        }
        .video-caption {
            font-family: 'Anonymous Pro', monospace;
            font-size: 1.1em;
            font-weight: 700;
            color: #2c3e50;
            margin-top: 10px;
            text-align: center;
            max-width: 300px;
        }
        .video-description {
            font-family: 'Anonymous Pro', monospace;
            font-size: 0.9em;
            color: #555;
            margin-top: 5px;
            text-align: center;
            max-width: 300px;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }
        .article-block-small.hidden-mobile {
            display: block;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @media (max-width: 900px) {
            .section_articles {
                padding: 40px 20px;
            }
            .section_articles h2, .section_articles h3 {
                font-size: 2em;
            }
            .articles-group {
                flex-direction: column;
            }
            .article-block-large, .article-block-small {
                height: auto;
                min-height: 200px;
            }
            .article-right {
                display: flex;
                flex-direction: column;
                gap: 15px;
            }
            .article-block-small.hidden-mobile {
                display: none;
            }
            .article-block-small:not(:first-child) {
                margin-top: 15px;
            }
            .article-title {
                font-size: 1.4em;
            }
            .article-preview {
                font-size: 0.9em;
            }
            .article-button, .nav-button {
                padding: 15px 20px;
                font-size: 0.9em;
            }
            .modal-content {
                width: 95%;
                padding: 15px;
            }
            .modal-content h3 {
                font-size: 1.5em;
            }
            .modal-content p {
                font-size: 0.9em;
            }
            .video-feed iframe {
                width: 300px;
                height: 169px;
            }
            .video-caption {
                font-size: 1em;
            }
            .video-description {
                font-size: 0.85em;
            }
        }
        @media (max-width: 600px) {
            .section_articles {
                padding: 30px 15px;
            }
            .section_articles h2, .section_articles h3 {
                font-size: 1.8em;
            }
            .article-title {
                font-size: 1.2em;
            }
            .article-preview {
                font-size: 0.8em;
            }
            .article-button, .nav-button {
                padding: 12px 18px;
                font-size: 0.8em;
            }
            .modal-content {
                padding: 10px;
            }
            .modal-content h3 {
                font-size: 1.3em;
            }
            .modal-content p {
                font-size: 0.8em;
            }
            .video-card {
                width: 100%;
            }
            .video-feed iframe {
                width: 100%;
                height: 150px;
            }
            .video-caption {
                font-size: 1em;
                max-width: 100%;
            }
            .video-description {
                font-size: 0.8em;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="overlay"></div>
    <div class="particles-container"></div>
    <div class="content-wrapper">
        <div class="main_text">
            <h1>Порешаем? Подготовка к ЕГЭ по математике</h1>
            <p class="subtitle">Всё бесплатно</p>
            <div class="retro rbtn-big" id="start-button">Начать</div>
            <p class="description">Кликни “Начать” и практикуй <strong>задания ЕГЭ по математике</strong> онлайн. Бесплатные уроки и решения помогут тебе подготовиться к экзамену и повысить баллы!</p>
        </div>
        <div class="equations-container">
            <div id="typewriter-left" class="typewriter-column"></div>
            <div id="typewriter-right" class="typewriter-column"></div>
        </div>
    </div>
    <div class="section about">
        <h2 class="seo-hidden">О сайте: Бесплатная помощь в подготовке к ЕГЭ</h2>
        <h2>О сайте(бум шака лака ):</h2>
        <p>Этот сайт создан для тех, кто хочет быстро и эффективно подготовиться к ЕГЭ по математике. Мы предлагаем бесплатные задания, решения и объяснения, чтобы ты мог практиковаться в любое время.</p>
    </div>
    <div class="section_features">
        <div class="section_fearurse_left">
            <div class="section_fearurse_left_center">
                <h2 class="seo-hidden">Что мы предлагаем для подготовки к ЕГЭ</h2>
                <div class="section_fearurse_left_ul">
                    <ul>
                        <li>➜ Пошаговые <strong>решения задач ЕГЭ</strong></li>
                        <li>➜ Интерактивные <strong>примеры ЕГЭ по математике</strong></li>
                        <li>➜ Доступ к практике <strong>ЕГЭ 24/7</strong></li>
                        <li>➜ Простой интерфейс для подготовки к <strong>ЕГЭ онлайн</strong></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="section_fearurse_img">
            <img class="example_img" src="/static/pictures/example.png" alt="Пример задания ЕГЭ по математике">
        </div>
    </div>
    
    <div class="section_articles">
    <h2>Полезные статьи и видео</h2>
        <div class="articles-container" id="articlesContainer">
            <div class="articles-group">
                <div class="article-block-large">
                    <div class="article-title">Как эффективно подготовиться к ЕГЭ по математике за 3 месяца</div>
                    <div class="article-preview">Узнайте, как за 12 недель поднять баллы на ЕГЭ по математике — пошаговый план и проверенные советы.</div>
                    <button class="article-button" data-content="Подготовка к ЕГЭ требует четкого плана. За 3 месяца можно значительно улучшить результаты, если следовать структурированному подходу. Недели 1-4: изучите базовые темы (алгебра, геометрия, тригонометрия). Недели 5-8: решайте задачи из прошлых ЕГЭ и практикуйтесь в сложных типах задач. Недели 9-12: проходите пробные экзамены и разбирайте ошибки. Используйте бесплатные задания на нашем сайте, видеоуроки и инструменты вроде PixelMath. Управляйте временем, избегайте стресса и регулярно проверяйте прогресс.">Открыть</button>
                </div>
                <div class="article-right">
                    <div class="article-block-small">
                        <div class="article-title">Топ-10 ошибок на ЕГЭ по математике</div>
                        <div class="article-preview">Узнайте, какие ошибки допускают на ЕГЭ и как их избежать.</div>
                        <button class="article-button" data-content="Ошибки на ЕГЭ могут стоить баллов. Самые частые: невнимательное чтение условий, ошибки в вычислениях, неправильное применение теорем и плохое управление временем. Чтобы избежать: проверяйте условия, тренируйте устный счет, рисуйте чертежи для геометрии и практикуйтесь с таймером. Пройдите наш тест на выявление ошибок и начните подготовку с заданий на сайте.">Открыть</button>
                    </div>
                    <div class="article-block-small hidden-mobile">
                        <div class="article-title">Базовый или профильный ЕГЭ?</div>
                        <div class="article-preview">Как выбрать между базовым и профильным уровнем ЕГЭ по математике.</div>
                        <button class="article-button" data-content="Базовый уровень ЕГЭ проще и подходит для тех, кто не планирует математические специальности. Он включает алгебру, геометрию и статистику. Профильный уровень сложнее, но открывает двери в топ-вузы, покрывая сложные темы вроде анализа и вероятности. Решите, исходя из ваших целей. База требует меньше времени, профиль — глубокой подготовки. Попробуйте примеры задач для обоих уровней на нашем сайте.">Открыть</button>
                    </div>
                    <div class="article-block-small">
                        <div class="article-title">Геометрия для ЕГЭ: ключевые темы</div>
                        <div class="article-preview">Освойте геометрию для ЕГЭ с основными теоремами и практикой.</div>
                        <button class="article-button" data-content="Геометрия — важная часть ЕГЭ. Ключевые темы: теорема Пифагора, свойства треугольников и окружностей, векторы. Стратегии: рисуйте точные чертежи, разбивайте задачи на части, практикуйтесь с координатной плоскостью. Решите наши примеры задач с подробными разборами и посмотрите видеоуроки для закрепления материала.">Открыть</button>
                    </div>
                    <div class="article-block-small hidden-mobile">
                        <div class="article-title">Прошлые варианты ЕГЭ</div>
                        <div class="article-preview">Как использовать прошлые тесты ЕГЭ для подготовки.</div>
                        <button class="article-button" data-content="Прошлые варианты ЕГЭ — лучший способ подготовки. Найдите их на сайте ФИПИ или в нашем архиве. Практикуйтесь в условиях экзамена, фокусируйтесь на слабых темах (например, вероятность) и разбирайте решения. Отслеживайте прогресс и выявляйте ошибки. Скачайте бесплатный вариант ЕГЭ с нашего сайта и начните прямо сейчас.">Открыть</button>
                    </div>
                </div>
            </div>
            <div class="articles-group">
                <div class="article-block-large">
                    <div class="article-title">Топ-10 ошибок на ЕГЭ по математике</div>
                    <div class="article-preview">Узнайте, какие ошибки допускают на ЕГЭ и как их избежать.</div>
                    <button class="article-button" data-content="Ошибки на ЕГЭ могут стоить баллов. Самые частые: невнимательное чтение условий, ошибки в вычислениях, неправильное применение теорем и плохое управление временем. Чтобы избежать: проверяйте условия, тренируйте устный счет, рисуйте чертежи для геометрии и практикуйтесь с таймером. Пройдите наш тест на выявление ошибок и начните подготовку с заданий на сайте.">Открыть</button>
                </div>
                <div class="article-right">
                    <div class="article-block-small">
                        <div class="article-title">Как подготовиться к ЕГЭ за 3 месяца</div>
                        <div class="article-preview">Пошаговый план подготовки к ЕГЭ по математике за 3 месяца.</div>
                        <button class="article-button" data-content="Подготовка к ЕГЭ требует четкого плана. За 3 месяца можно значительно улучшить результаты, если следовать структурированному подходу. Недели 1-4: изучите базовые темы (алгебра, геометрия, тригонометрия). Недели 5-8: решайте задачи из прошлых ЕГЭ и практикуйтесь в сложных типах задач. Недели 9-12: проходите пробные экзамены и разбирайте ошибки. Используйте бесплатные задания на нашем сайте, видеоуроки и инструменты вроде MathPix. Управляйте временем, избегайте стресса и регулярно проверяйте прогресс.">Открыть</button>
                    </div>
                    <div class="article-block-small hidden-mobile">
                        <div class="article-title">Геометрия для ЕГЭ: ключевые темы</div>
                        <div class="article-preview">Освойте геометрию для ЕГЭ с основными теоремами и практикой.</div>
                        <button class="article-button" data-content="Геометрия — важная часть ЕГЭ. Ключевые темы: теорема Пифагора, свойства треугольников и окружностей, векторы. Стратегии: рисуйте точные чертежи, разбивайте задачи на части, практикуйтесь с координатной плоскостью. Решите наши примеры задач с подробными разборами и посмотрите видеоуроки для закрепления материала.">Открыть</button>
                    </div>
                    <div class="article-block-small">
                        <div class="article-title">Базовый или профильный ЕГЭ?</div>
                        <div class="article-preview">Как выбрать между базовым и профильным уровнем ЕГЭ.</div>
                        <button class="article-button" data-content="Базовый уровень ЕГЭ проще и подходит для тех, кто не планирует математические специальности. Он включает алгебру, геометрию и статистику. Профильный уровень сложнее, но открывает двери в топ-вузы, покрывая сложные темы вроде анализа и вероятности. Решите, исходя из ваших целей. База требует меньше времени, профиль — глубокой подготовки. Попробуйте примеры задач для обоих уровней на нашем сайте.">Открыть</button>
                    </div>
                    <div class="article-block-small hidden-mobile">
                        <div class="article-title">Прошлые варианты ЕГЭ</div>
                        <div class="article-preview">Как использовать прошлые тесты ЕГЭ для подготовки.</div>
                        <button class="article-button" data-content="Прошлые варианты ЕГЭ — лучший способ подготовки. Найдите их на сайте ФИПИ или в нашем архиве. Практикуйтесь в условиях экзамена, фокусируйтесь на слабых темах (например, вероятность) и разбирайте решения. Отслеживайте прогресс и выявляйте ошибки. Скачайте бесплатный вариант ЕГЭ с нашего сайта и начните прямо сейчас.">Открыть</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="nav-buttons">
            <button class="nav-button" id="prevButton" disabled>← Назад</button>
            <button class="nav-button" id="nextButton">Вперед →</button>
        </div>
        <div class="modal" id="articleModal">
            <div class="modal-content">
                <span class="modal-close">×</span>
                <h3 id="modalTitle"></h3>
                <p id="modalContent"></p>
            </div>
        </div>
        <h3>Полезные видео</h3>
        <div class="video-feed">
            <div class="video-card">
                <iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ" title="YouTube video" allowfullscreen></iframe>
                <p class="video-caption">YouTube video</p>
                <p class="video-description">Для настроения</p>
            </div>
            <div class="video-card">
                <iframe src="https://vk.com/video_ext.php?oid=-199135401&id=456239309&hd=2&autoplay=0" allowfullscreen title="VK video"></iframe>
                <p class="video-caption">VK video</p>
                <p class="video-description">Если не хочется учить математику и нужно вдохновиться.</p>
            </div>
            
        </div>
    </div>
    
    <div class="section_features">
        <div class="section_fearurse_left">
            <div class="section_fearurse_left_center">
                <h2 class="seo-hidden">Что мы предлагаем для подготовки к ЕГЭ</h2>
                <div class="section_fearurse_left_ul">
                    <ul>
                        <li>➜ Подготовка к ОГЭ <strong></strong></li>
                        <li>➜ Подготовка к ЕГЭ (базовый и профильный уровень)<strong></strong></li>
                        <li>➜ Повышение успеваемости<strong></strong></li>
                        <li>➜ Помощь с домашней работой<strong></strong></li>
                        <li>➜ тг <strong>@ralifgrannik</strong></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="section_fearurse_img">
            <img class="example_img" src="/static/pictures/lessen.png" alt="Индивидуальные занятия. Услуги репетитора">
        </div>
    </div>
    
    <div class="section contact">
        <h2>Связаться с нами</h2>
        <p>Есть вопросы? Пиши нам на <a href="mailto:mathproba@mail.ru">mathpix@inbox.ru</a> или оставляй комментарии в нашем сообществе!</p>
    </div>
    <audio id="optimus-theme" src="/static/audio/optimus_theme.mp3" loading="lazy"></audio>
    
    <script>
        document.getElementById('start-button').addEventListener('click', function() {
            ym(101089544, 'reachGoal', 'start_button_click');
        });
        
        // Modal functionality
        const modal = document.getElementById('articleModal');
        const modalContent = document.querySelector('.modal-content');
        const modalTitle = document.getElementById('modalTitle');
        const modalText = document.getElementById('modalContent');
        const closeModal = document.querySelector('.modal-close');
        
        document.querySelectorAll('.article-button').forEach(button => {
            button.addEventListener('click', () => {
                const title = button.parentElement.querySelector('.article-title').textContent;
                const content = button.getAttribute('data-content');
                modalTitle.textContent = title;
                modalText.textContent = content;
                modal.style.display = 'flex';
                setTimeout(() => modalContent.classList.add('open'), 10);
            });
        });
        
        closeModal.addEventListener('click', () => {
            modalContent.classList.remove('open');
            setTimeout(() => modal.style.display = 'none', 300);
        });
        
        window.addEventListener('click', (e) => {
            if (e.target === modal) {
                modalContent.classList.remove('open');
                setTimeout(() => modal.style.display = 'none', 300);
            }
        });
        
        // Articles scrolling
        const container = document.getElementById('articlesContainer');
        const prevButton = document.getElementById('prevButton');
        const nextButton = document.getElementById('nextButton');
        let currentIndex = 0;
        const groups = document.querySelectorAll('.articles-group');
        
        function updateButtons() {
            prevButton.disabled = currentIndex === 0;
            nextButton.disabled = currentIndex === groups.length - 1;
        }
        
        nextButton.addEventListener('click', () => {
            if (currentIndex < groups.length - 1) {
                currentIndex++;
                container.scrollTo({
                    left: groups[currentIndex].offsetLeft,
                    behavior: 'smooth'
                });
                updateButtons();
            }
        });
        
        prevButton.addEventListener('click', () => {
            if (currentIndex > 0) {
                currentIndex--;
                container.scrollTo({
                    left: groups[currentIndex].offsetLeft,
                    behavior: 'smooth'
                });
                updateButtons();
            }
        });
        
        updateButtons();
    </script>
    
    <script src="/static/scripts/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-chtml.js" async></script>
</body>
</html>