<?php
/**
 * send_order_email.php
 * Raw SMTP socket email sender — no external libraries.
 * Receives JSON POST from cart.php and sends an Order Confirmed email.
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Method not allowed']);
    exit;
}

require_once __DIR__ . '/config.php';

/* ─── Parse incoming JSON ─── */
$raw = file_get_contents('php://input');
$order = json_decode($raw, true);

if (!$order || empty($order['customer']['email'])) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Invalid order data or no email']);
    exit;
}

$emailTo   = $order['customer']['email'] ?? '';
$nameTo    = $order['customer']['name'] ?: 'Customer';
$orderId   = $order['orderId'] ?: 'N/A';
$items     = $order['items'] ?? [];
$total     = $order['total'] ?? 0;
$subtotal  = $order['subtotal'] ?? 0;
$delivery  = $order['deliveryFee'] ?? 0;
$discount  = $order['discount'] ?? 0;
$coupon    = $order['coupon'] ?? '';
$address   = $order['customer']['address'] ?? '';
$city      = $order['customer']['city'] ?? '';
$note      = $order['customer']['note'] ?? '';

/* ─── Build Items HTML ─── */
$itemRows = '';
foreach ($items as $it) {
    $itemRows .= '<tr>';
    $itemRows .= '<td style="padding:10px 14px;border-bottom:1px solid #eee;font-size:14px;">' . htmlspecialchars($it['name'] ?? '') . ($it['size'] ? ' <span style="font-size:12px;color:#888;">(Size: ' . htmlspecialchars($it['size']) . ')</span>' : '') . '</td>';
    $itemRows .= '<td style="padding:10px 14px;border-bottom:1px solid #eee;font-size:14px;text-align:center;">' . (int)($it['qty'] ?? 1) . '</td>';
    $itemRows .= '<td style="padding:10px 14px;border-bottom:1px solid #eee;font-size:14px;text-align:right;">Rs. ' . number_format(($it['price'] ?? 0) * ($it['qty'] ?? 1)) . '</td>';
    $itemRows .= '</tr>';
}

$discountRow = '';
if ($discount > 0) {
    $discountRow = '<tr><td style="padding:8px 14px;font-size:14px;">Discount (' . htmlspecialchars($coupon) . ')</td><td style="padding:8px 14px;text-align:right;font-size:14px;color:#16a34a;">-Rs. ' . number_format($discount) . '</td></tr>';
}

/* ─── HTML Email Body ─── */
$html = <<<HTML
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="margin:0;padding:0;background:#f4f6f8;font-family:'Segoe UI',Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6f8;padding:30px 0;">
<tr><td align="center">
<table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,.06);">

<!-- Header -->
<tr><td style="background:#0984e3;padding:28px 30px;text-align:center;">
    <h1 style="margin:0;color:#ffffff;font-size:24px;font-weight:700;letter-spacing:1px;">SHAHI PESHAWARI</h1>
    <p style="margin:6px 0 0;color:rgba(255,255,255,.85);font-size:13px;">Order Confirmation</p>
</td></tr>

<!-- Body -->
<tr><td style="padding:30px;">
    <p style="margin:0 0 6px;font-size:15px;color:#333;">Dear <strong>{$nameTo}</strong>,</p>
    <p style="margin:0 0 20px;font-size:14px;color:#555;">Thank you for your order! Here are your order details:</p>

    <!-- Order ID Box -->
    <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
    <tr><td style="background:#f0f8ff;border-radius:8px;padding:16px;text-align:center;">
        <p style="margin:0 0 4px;font-size:12px;color:#888;text-transform:uppercase;letter-spacing:1px;">Order ID</p>
        <p style="margin:0;font-size:20px;font-weight:700;color:#0984e3;">{$orderId}</p>
    </td></tr>
    </table>

    <!-- Items Table -->
    <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:20px;border:1px solid #eee;border-radius:8px;overflow:hidden;">
    <tr style="background:#f9fafb;">
        <td style="padding:10px 14px;font-size:12px;font-weight:600;color:#888;text-transform:uppercase;">Item</td>
        <td style="padding:10px 14px;font-size:12px;font-weight:600;color:#888;text-transform:uppercase;text-align:center;">Qty</td>
        <td style="padding:10px 14px;font-size:12px;font-weight:600;color:#888;text-transform:uppercase;text-align:right;">Price</td>
    </tr>
    {$itemRows}
    </table>

    <!-- Totals -->
    <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:20px;">
    <tr><td style="padding:6px 14px;font-size:14px;">Subtotal</td><td style="padding:6px 14px;text-align:right;font-size:14px;">Rs. {$subtotal}</td></tr>
    {$discountRow}
    <tr><td style="padding:10px 14px;font-size:16px;font-weight:700;border-top:2px solid #eee;">Total</td><td style="padding:10px 14px;text-align:right;font-size:18px;font-weight:700;color:#0984e3;border-top:2px solid #eee;">Rs. {$total}</td></tr>
    </table>

HTML;

if ($address) {
    $html .= '<p style="margin:0 0 6px;font-size:14px;color:#555;"><strong>Delivery Address:</strong> ' . htmlspecialchars($address);
    if ($city) $html .= ', ' . htmlspecialchars($city);
    $html .= '</p>';
}
if ($note) {
    $html .= '<p style="margin:10px 0 0;font-size:13px;color:#888;"><em>Note: ' . htmlspecialchars($note) . '</em></p>';
}

$html .= <<<HTML
</td></tr>

<!-- Footer -->
<tr><td style="background:#f9fafb;padding:20px 30px;text-align:center;border-top:1px solid #eee;">
    <p style="margin:0 0 4px;font-size:12px;color:#999;">This is an automated email from Shahi Peshawari.</p>
    <p style="margin:0;font-size:12px;color:#999;">For queries, contact us at <a href="mailto:{$emailTo}" style="color:#0984e3;">{$emailTo}</a></p>
</td></tr>

</table>
</td></tr>
</table>
</body>
</html>
HTML;

/* ─── Plain text fallback ─── */
$plain = "Dear {$nameTo},\n\n";
$plain .= "Thank you for your order!\n";
$plain .= "Order ID: {$orderId}\n\n";
$plain .= "Items:\n";
foreach ($items as $it) {
    $plain .= "- " . ($it['name'] ?? '') . ($it['size'] ? ' (Size: ' . $it['size'] . ')' : '') . " x" . ($it['qty'] ?? 1) . "  Rs. " . number_format(($it['price'] ?? 0) * ($it['qty'] ?? 1)) . "\n";
}
$plain .= "\nSubtotal: Rs. {$subtotal}\n";
if ($discount > 0) $plain .= "Discount ({$coupon}): -Rs. {$discount}\n";
$plain .= "Total: Rs. {$total}\n\n";
if ($address) $plain .= "Address: {$address}, {$city}\n";
if ($note) $plain .= "Note: {$note}\n";
$plain .= "\n- Shahi Peshawari Team\n";

/* ─── Build RFC 2822 Message ─── */
$boundary = md5(uniqid(time()));
$headers  = "From: " . SMTP_FROM_NAME . " <" . SMTP_FROM . ">\r\n";
$headers .= "To: {$nameTo} <{$emailTo}>\r\n";
$headers .= "Subject: Order Confirmed - {$orderId}\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: multipart/alternative; boundary=\"{$boundary}\"\r\n";
$headers .= "\r\n";

$body  = "--{$boundary}\r\n";
$body .= "Content-Type: text/plain; charset=UTF-8\r\n";
$body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
$body .= $plain . "\r\n\r\n";
$body .= "--{$boundary}\r\n";
$body .= "Content-Type: text/html; charset=UTF-8\r\n";
$body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
$body .= $html . "\r\n\r\n";
$body .= "--{$boundary}--";

/* ─── Raw SMTP via fsockopen ─── */
try {
    $errno = 0;
    $errstr = '';
    $fp = fsockopen('ssl://' . SMTP_HOST, SMTP_PORT, $errno, $errstr, 15);

    if (!$fp) {
        throw new Exception("SMTP connection failed: {$errstr} ({$errno})");
    }

    /* Read greeting */
    $response = fgets($fp, 512);

    /* EHLO */
    fwrite($fp, "EHLO " . SMTP_HOST . "\r\n");
    $response = readSmtpResponse($fp);

    /* STARTTLS (not needed for port 465, but skip if already SSL) */

    /* AUTH LOGIN */
    fwrite($fp, "AUTH LOGIN\r\n");
    $response = fgets($fp, 512);

    /* Username (base64) */
    fwrite($fp, base64_encode(SMTP_USER) . "\r\n");
    $response = fgets($fp, 512);

    /* Password (base64) */
    fwrite($fp, base64_encode(SMTP_PASS) . "\r\n");
    $response = fgets($fp, 512);

    if (strpos($response, '235') === false) {
        throw new Exception("SMTP authentication failed: " . trim($response));
    }

    /* MAIL FROM */
    fwrite($fp, "MAIL FROM:<" . SMTP_FROM . ">\r\n");
    $response = fgets($fp, 512);

    /* RCPT TO */
    fwrite($fp, "RCPT TO:<{$emailTo}>\r\n");
    $response = fgets($fp, 512);

    /* DATA */
    fwrite($fp, "DATA\r\n");
    $response = fgets($fp, 512);

    /* Send headers + body */
    fwrite($fp, $headers . $body . "\r\n.\r\n");
    $response = fgets($fp, 512);

    /* QUIT */
    fwrite($fp, "QUIT\r\n");
    fclose($fp);

    http_response_code(200);
    echo json_encode(['ok' => true, 'message' => 'Email sent to ' . $emailTo]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}

/* ─── Helper: Read multi-line SMTP response ─── */
function readSmtpResponse($fp) {
    $response = '';
    while (true) {
        $line = fgets($fp, 512);
        if ($line === false) break;
        $response .= $line;
        if (isset($line[3]) && $line[3] === ' ') break;
    }
    return $response;
}
