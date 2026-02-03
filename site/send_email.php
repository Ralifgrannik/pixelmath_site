<?php
require_once 'config.php';
header('Content-Type: application/json; charset=UTF-8');
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php_errors.log');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['status' => 'error', 'message' => 'Неверный метод']);
        exit;
    }

    $sender_name = filter_var($_POST['sender_name'] ?? 'Неизвестный ученик', FILTER_SANITIZE_STRING);
    $recipient_email = filter_var($_POST['recipient_email'] ?? '', FILTER_SANITIZE_EMAIL);
    $file = $_FILES['file'] ?? null;

    if (!$recipient_email || !filter_var($recipient_email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Некорректный email преподавателя']);
        exit;
    }

    if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
        $file_error = $file ? $file['error'] : 'No file uploaded';
        error_log('File upload error: ' . $file_error);
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Файл не загружен', 'file_error' => $file_error]);
        exit;
    }

    if (!is_readable($file['tmp_name'])) {
        error_log('Cannot read file: ' . $file['tmp_name']);
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Ошибка доступа к файлу']);
        exit;
    }

    $subject = "Результаты варианта ЕГЭ от $sender_name";
    $message = "Результаты ученика $sender_name во вложении.";
    $boundary = md5(uniqid());

    $headers = "From: noreply@pixelmath.ru\r\n";
    $headers .= "Reply-To: noreply@pixelmath.ru\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";

    $body = "--$boundary\r\n";
    $body .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $body .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
    $body .= $message . "\r\n";

    $body .= "--$boundary\r\n";
    $body .= "Content-Type: application/octet-stream; name=\"{$file['name']}\"\r\n";
    $body .= "Content-Transfer-Encoding: base64\r\n";
    $body .= "Content-Disposition: attachment; filename=\"{$file['name']}\"\r\n\r\n";
    $body .= chunk_split(base64_encode(file_get_contents($file['tmp_name']))) . "\r\n";
    $body .= "--$boundary--";

    if (mail($recipient_email, $subject, $body, $headers)) {
        error_log('Email sent successfully to ' . $recipient_email . ' from ' . $sender_name);
        echo json_encode(['status' => 'success', 'message' => 'Email отправлен']);
    } else {
        error_log('Email error: Failed to send to ' . $recipient_email . ' from ' . $sender_name);
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Ошибка отправки']);
    }
} catch (Exception $e) {
    error_log('Exception in send_email.php: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Внутренняя ошибка']);
}
?>