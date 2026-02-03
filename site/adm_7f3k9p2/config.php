<?php
require_once dirname(__DIR__) . '/config.php';
ini_set('display_errors', 0);
error_reporting(E_ALL);
define('ADMIN_PATH', '/adm_7f3k9p2');
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', 'xzjdurgp23894');
define('UPLOAD_DIR', BASE_PATH . '/static/pictures/');
define('UPLOAD_URL', '/static/pictures/');
session_set_cookie_params([
    'lifetime' => 0,
    'path' => ADMIN_PATH . '/',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Strict'
]);
session_start();
?>