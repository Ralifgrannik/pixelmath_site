<?php
require_once '../config.php';
require_once 'config.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

if ($_FILES['image']) {
    try {
        $filename = time() . '_' . basename($_FILES['image']['name']);
        $upload_file = UPLOAD_DIR . $filename;
        if (!is_dir(UPLOAD_DIR)) {
            mkdir(UPLOAD_DIR, 0755, true);
        }
        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_file)) {
            echo json_encode(['url' => UPLOAD_URL . $filename]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to upload image']);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'No image provided']);
}
?>