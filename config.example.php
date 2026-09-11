<?php
/**
 * Shahi Peshawari - Configuration Template
 * 
 * HOW TO SETUP:
 * 1. Copy this file and rename it to "config.php"
 * 2. Replace all placeholder values with your real credentials
 * 3. NEVER commit config.php to version control
 */

// ─── Firebase Configuration ───
// Get these from: Firebase Console > Project Settings > General > Your apps
define('FIREBASE_API_KEY',             'YOUR_FIREBASE_API_KEY');
define('FIREBASE_AUTH_DOMAIN',         'your-project.firebaseapp.com');
define('FIREBASE_DATABASE_URL',        'https://your-project-default-rtdb.firebaseio.com');
define('FIREBASE_PROJECT_ID',          'your-project-id');
define('FIREBASE_STORAGE_BUCKET',      'your-project.appspot.com');
define('FIREBASE_MESSAGING_SENDER_ID', '000000000000');
define('FIREBASE_APP_ID',              '1:000000000000:web:xxxxxxxxxxxx');

// ─── SMTP Configuration (Gmail) ───
// How to get Gmail App Password:
// 1. Go to https://myaccount.google.com/security
// 2. Enable 2-Step Verification
// 3. Go to https://myaccount.google.com/apppasswords
// 4. Generate a new app password for "Mail"
define('SMTP_HOST',     'smtp.gmail.com');
define('SMTP_PORT',     465);
define('SMTP_USER',     'your-email@gmail.com');
define('SMTP_PASS',     'xxxx xxxx xxxx xxxx');  // Gmail App Password
define('SMTP_FROM',     'your-email@gmail.com');
define('SMTP_FROM_NAME', 'Your Store Name');

// ─── Store Settings ───
define('SITE_NAME',     'Your Store Name');
define('CURRENCY',      'Rs.');
define('DELIVERY_FEE',  0);

// Auto-detect BASE_URL (no need to change)
if (!defined('BASE_URL')) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $dir  = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
    $dir  = implode('/', array_map('rawurlencode', explode('/', $dir)));
    define('BASE_URL', $protocol . '://' . $host . $dir);
}

// ─── Admin Login ───
// First create this account in Firebase Console > Authentication > Users
define('ADMIN_EMAIL',  'admin@yourstore.com');
define('ADMIN_PASS',   'YourAdminPassword123');

// ─── Coupons ───
define('COUPONS', [
    'WELCOME20' => ['type' => 'percent', 'value' => 20, 'label' => '20% Off'],
    'FLAT100'   => ['type' => 'flat',    'value' => 100, 'label' => 'Rs.100 Off'],
]);
