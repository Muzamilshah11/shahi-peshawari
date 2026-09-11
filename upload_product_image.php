<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Method not allowed']);
    exit;
}

$uploadDir = __DIR__ . '/uploads/products/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$maxSize = 5 * 1024 * 1024; // 5MB

if (!isset($_FILES['images'])) {
    echo json_encode(['ok' => false, 'error' => 'No files uploaded']);
    exit;
}

$uploaded = [];
$errors = [];
$files = $_FILES['images'];

// Handle multiple files
$count = is_array($files['name']) ? count($files['name']) : 1;

for ($i = 0; $i < $count; $i++) {
    $name = $files['name'][$i] ?? $files['name'];
    $tmpName = $files['tmp_name'][$i] ?? $files['tmp_name'];
    $error = $files['error'][$i] ?? $files['error'];
    $size = $files['size'][$i] ?? $files['size'];
    $type = $files['type'][$i] ?? $files['type'];

    if ($error !== UPLOAD_ERR_OK) {
        $errors[] = "Error uploading $name";
        continue;
    }

    if (!in_array($type, $allowedTypes)) {
        $errors[] = "$name: Invalid file type (only JPG, PNG, GIF, WebP)";
        continue;
    }

    if ($size > $maxSize) {
        $errors[] = "$name: File too large (max 5MB)";
        continue;
    }

    $ext = pathinfo($name, PATHINFO_EXTENSION);
    $newName = 'product_' . time() . '_' . mt_rand(1000, 9999) . '.' . $ext;
    $dest = $uploadDir . $newName;

    if (move_uploaded_file($tmpName, $dest)) {
        $uploaded[] = 'uploads/products/' . $newName;
    } else {
        $errors[] = "$name: Failed to save";
    }
}

echo json_encode([
    'ok' => count($uploaded) > 0,
    'uploaded' => $uploaded,
    'errors' => $errors,
    'count' => count($uploaded)
]);
