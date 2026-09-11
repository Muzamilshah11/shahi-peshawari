<?php
/**
 * admin_auth.php — Firebase Auth REST API proxy
 * Auto-creates admin account on first login, then signs in.
 */
require_once __DIR__ . '/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Method not allowed']);
    exit;
}

$raw   = file_get_contents('php://input');
$input = json_decode($raw, true);
$email = trim($input['email']  ?? '');
$pass  = trim($input['password'] ?? '');

if (!$email || !$pass) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Email and password required']);
    exit;
}

$apiKey = FIREBASE_API_KEY;

/* ─── Helper: POST to Firebase via cURL ─── */
function fbPost($endpoint, $data, $apiKey) {
    $url = "https://identitytoolkit.googleapis.com/v1/{$endpoint}?key={$apiKey}";

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
        CURLOPT_POSTFIELDS     => json_encode($data),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 15,
        CURLOPT_SSL_VERIFYPEER => true,
    ]);
    $resp = curl_exec($ch);
    $err  = curl_error($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($err) {
        return ['error' => ['message' => 'cURL error: ' . $err]];
    }
    if ($code === 0) {
        return ['error' => ['message' => 'No response from server (HTTP 0)']];
    }
    $decoded = json_decode($resp, true);
    if (!$decoded) {
        return ['error' => ['message' => 'Invalid response (HTTP ' . $code . ')']];
    }
    return $decoded;
}

/* ─── Step 1: Try Sign In ─── */
$result = fbPost('accounts:signInWithPassword', [
    'email'             => $email,
    'password'          => $pass,
    'returnSecureToken' => true,
], $apiKey);

if (isset($result['idToken'])) {
    echo json_encode([
        'ok'      => true,
        'idToken' => $result['idToken'],
        'email'   => $result['email'],
    ]);
    exit;
}

/* ─── Step 2: If user not found → create account ─── */
$errMessage = $result['error']['message'] ?? 'UNKNOWN_ERROR';

if ($errMessage === 'EMAIL_NOT_FOUND' || $errMessage === 'INVALID_LOGIN_CREDENTIALS' || $errMessage === 'INVALID_EMAIL') {
    $createResult = fbPost('accounts:signUp', [
        'email'             => $email,
        'password'          => $pass,
        'returnSecureToken' => true,
    ], $apiKey);

    if (isset($createResult['idToken'])) {
        $signInAgain = fbPost('accounts:signInWithPassword', [
            'email'             => $email,
            'password'          => $pass,
            'returnSecureToken' => true,
        ], $apiKey);

        if (isset($signInAgain['idToken'])) {
            echo json_encode([
                'ok'      => true,
                'idToken' => $signInAgain['idToken'],
                'email'   => $signInAgain['email'],
                'created' => true,
            ]);
            exit;
        }
    }

    $createErr = $createResult['error']['message'] ?? 'Unknown';
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Account creation failed: ' . $createErr]);
    exit;
}

/* ─── Other error ─── */
http_response_code(401);
echo json_encode(['ok' => false, 'error' => $errMessage]);
