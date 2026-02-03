<?php
require_once 'config.php';
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    
    
    <script src="/static/scripts/protect.js"></script>
    <link rel="icon" href="/static/pictures/favicon.ico" type="image/x-icon">
    
    <!-- Yandex.Metrika counter -->
    <script type="text/javascript">
        (function(m,e,t,r,i,k,a){
            m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
            m[i].l=1*new Date();
            for (var j = 0; j < document.scripts.length; j++) {
                if (document.scripts[j].src === r) { return; }
            }
            k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a);
        })(window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

        ym(101089544, "init", {
            clickmap: true,
            trackLinks: true,
            accurateTrackBounce: true
        });
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/101089544" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <!-- /Yandex.Metrika counter -->

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Задания ЕГЭ по математике | Порешаем? - Бесплатная практика</title>
    <meta name="description" content="Решай задания ЕГЭ по математике онлайн бесплатно. Проверяй ответы и готовься к экзамену!">
    <meta name="keywords" content="задания ЕГЭ математика, практика ЕГЭ, решения задач ЕГЭ">
    <link rel="stylesheet" href="/static/CSS/math.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Anonymous+Pro:ital,wght@0,400;0,700;1,400&display=swap">
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@400;700&display=swap" rel="stylesheet">
    <script>
        window.MathJax = {
            tex: {
                inlineMath: [['$', '$'], ['\\(', '\\)']],
                displayMath: [['$$', '$$'], ['\\[', '\\]']],
                processEscapes: true
            },
            options: {
                ignoreHtmlClass: 'tex2jax_ignore',
                processHtmlClass: 'tex2jax_process'
            }
        };
    </script>
    <script src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-chtml.js" id="MathJax-script" async></script>
    
    
    <meta property="og:title" content="Порешаем? Бесплатная подготовка к ЕГЭ по математике">
    <meta property="og:description" content="Решай задания ЕГЭ по математике онлайн бесплатно и готовься к экзамену!">
    <meta property="og:image" content="https://pixelmath.ru/static/pictures/og-image.jpg">
    <meta property="og:url" content="https://pixelmath.ru/">
    <meta property="og:type" content="website">
    
    
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebPage",
        "name": "Задания ЕГЭ по математике | Порешаем?",
        "description": "Решай задания ЕГЭ по математике онлайн бесплатно с решениями.",
        "url": "https://pixelmath.ru/ege-matematika/"
    }
    </script>
    
    
    
</head>

<body>
    <div class="container">
        <button id="menu-toggle" class="menu-toggle">☰</button>
        <div class="sidebar" id="sidebar">
            <div class="button-menu">
                <div class="retro rbtn-big" data-test="1">Задание 1</div>
                <div class="retro rbtn-big" data-test="2">Задание 2</div>
                <div class="retro rbtn-big" data-test="3">Задание 3</div>
                <div class="retro rbtn-big" data-test="4">Задание 4</div>
                <div class="retro rbtn-big" data-test="5">Задание 5</div>
                <div class="retro rbtn-big" data-test="6">Задание 6</div>
                <div class="retro rbtn-big" data-test="7">Задание 7</div>
                <div class="retro rbtn-big" data-test="8">Задание 8</div>
                <div class="retro rbtn-big" data-test="9">Задание 9</div>
                <div class="retro rbtn-big" data-test="10">Задание 10</div>
                <div class="retro rbtn-big" data-test="11">Задание 11</div>
                <div class="retro rbtn-big" data-test="12">Задание 12</div>
                <div class="retro rbtn-big" data-test="generate-test">Сформировать<br> вариант</div>
                <div><br><br><br></div>
            </div>
        </div>
        <div class="content">
            <div id="task-display">
                <h1 class="seo-hidden">Задания ЕГЭ по математике для практики</h1>
                <div class="header">
                    <div class="motivation">
                        <h2 class="seo-hidden">Мотивируйся и готовься к ЕГЭ</h2>
                        <p>Ты можешь это сделать! Каждое решённое <strong>задание ЕГЭ по математике</strong> приближает тебя к высоким баллам на экзамене.</p>
                    </div>
                    <div class="counter">
                        <p id="solved-count">Решено задач: 0</p>
                        <button class="retro rbtn-small" id="theme-toggle">Тёмная тема</button>
                    </div>
                </div>
                <div class="task">
                    <p id="task-problem"></p>
                    <img id="task-image" src="" alt="Задача ЕГЭ по математике" style="display: none;">
                </div>
                <div class="answer-input">
                    <input type="text" id="user-answer" placeholder="Ваш ответ">
                    <span id="check-result"></span>
                </div>
                <div class="solution-button">
                    <button class="retro rbtn-big" id="check-answer">Проверить ответ</button>
                    <button class="retro rbtn-big" id="show-solution" disabled>Показать решение</button>
                    <button class="retro rbtn-big" id="next-task">Следующая задача</button>
                </div>
                <div class="solution" id="solution-text" style="display: none;">
                    <p id="solution-content"><strong>Решение:</strong><br></p>
                </div>
                <div id="test-container" style="display: none;"></div>
            </div>
            <div id="email-modal" class="modal" style="display: none;">
                <div class="modal-content">
                    <p>Отправить результаты</p>
                    <input type="text" id="sender-name" placeholder="Ваше имя" required>
                    <input type="email" id="recipient-email" placeholder="Email преподавателя" required>
                    <div id="loading-indicator" style="display: none;">Загрузка...</div>
                    <button class="retro rbtn-big" id="confirm-email">Подтвердить</button>
                    <button class="retro rbtn-big" id="cancel-email">Отмена</button>
                </div>
            </div>
        </div>
    </div>
    <audio id="optimus-theme" src="/static/audio/lightsaber.mp3" loading="lazy"></audio>
    <audio id="optimus-theme2" src="/static/audio/lightsaberOFF.mp3" loading="lazy"></audio>
    <script src="/static/scripts/math.js"></script>
    

    
</body>
</html>