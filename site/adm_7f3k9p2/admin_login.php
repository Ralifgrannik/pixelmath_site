<?php
require_once 'config.php';

// Инициализация попыток входа
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['lockout_time'] = 0;
}

$lockout_duration = 300; // 5 минут (в секундах)
$current_time = time();

// Проверка на блокировку
if ($_SESSION['login_attempts'] >= 5 && ($current_time - $_SESSION['lockout_time']) < $lockout_duration) {
    $remaining = $lockout_duration - ($current_time - $_SESSION['lockout_time']);
    $error = "Слишком много попыток. Попробуйте снова через $remaining секунд.";
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Сброс времени блокировки, если оно истекло
    if ($_SESSION['login_attempts'] >= 5 && ($current_time - $_SESSION['lockout_time']) >= $lockout_duration) {
        $_SESSION['login_attempts'] = 0;
        $_SESSION['lockout_time'] = 0;
    }

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username === ADMIN_USERNAME && $password === ADMIN_PASSWORD) {
        $_SESSION['loggedin'] = true;
        $_SESSION['login_attempts'] = 0; // Сброс попыток при успешном входе
        header('Location: ' . ADMIN_PATH . '/admin.php');
        exit;
    } else {
        $_SESSION['login_attempts']++;
        if ($_SESSION['login_attempts'] >= 5) {
            $_SESSION['lockout_time'] = $current_time;
            $error = "Слишком много попыток. Попробуйте снова через 5 минут.";
        } else {
            $error = "Неверный логин или пароль. Попытка " . $_SESSION['login_attempts'] . " из 5.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход в админку - PixelMath</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f4f7fa, #d9e2ec);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
        }

        .login-box {
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0,0,0,0.1);
            text-align: center;
        }

        .login-box h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        .form-group input:focus {
            border-color: #007bff;
            outline: none;
        }

        .login-box button {
            background: #007bff;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 6px;
            width: 100%;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }

        .login-box button:hover {
            background: #0056b3;
        }

        .error {
            color: #dc3545;
            background: #f8d7da;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 480px) {
            .container {
                padding: 15px;
            }

            .login-box {
                padding: 20px;
            }

            .login-box h2 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-box">
            <h2>Вход в админку</h2>
            <?php if (isset($error)): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="form-group">
                    <input type="text" name="username" placeholder="Логин" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="Пароль" required>
                </div>
                <button type="submit">Войти</button>
            </form>
        </div>
    </div>
</body>
</html>