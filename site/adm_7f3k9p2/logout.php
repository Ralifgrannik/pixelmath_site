<?php
require_once 'config.php';

session_destroy();
header('Location: ' . ADMIN_PATH . '/admin_login.php');
exit;
?>