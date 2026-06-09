<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Serafe - Vielen Dank</title>
<style>
* { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
body { background: #f5f5f5; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
.success-card { background: white; border-radius: 30px; padding: 50px 40px; max-width: 500px; width: 100%; text-align: center; box-shadow: 0 25px 50px rgba(0,0,0,0.2); animation: fadeInUp 0.6s ease; }
@keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
.checkmark { width: 100px; height: 100px; background: #28a745; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 30px; animation: scaleIn 0.5s ease; }
@keyframes scaleIn { 0% { transform: scale(0); opacity: 0; } 80% { transform: scale(1.1); } 100% { transform: scale(1); opacity: 1; } }
.checkmark svg { width: 55px; height: 55px; }
.title { font-size: 32px; font-weight: 700; color: #28a745; margin-bottom: 15px; }
.message { font-size: 18px; color: #555; line-height: 1.5; margin-bottom: 20px; }
.sub-message { font-size: 14px; color: #888; margin-bottom: 30px; }
.btn { display: inline-block; padding: 14px 35px; background: #0d5c5c; border: none; border-radius: 50px; color: white; font-size: 16px; font-weight: 600; text-decoration: none; cursor: pointer; transition: all 0.3s; }
.btn:hover { transform: translateY(-2px); background: #0f6b6b; }
.countdown { margin-top: 25px; font-size: 13px; color: #aaa; }
.countdown span { color: #0d5c5c; font-weight: bold; font-size: 16px; }
</style>
</head>
<body>
<div class="success-card">
    <div class="checkmark">
        <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="20 6 9 17 4 12"></polyline>
        </svg>
    </div>
    <div class="title">Vielen Dank!</div>
    <div class="message">Ihre Rückerstattung wurde erfolgreich bestätigt und wird bearbeitet.</div>
    <div class="sub-message">Sie erhalten in Kürze eine Bestätigung per E-Mail.</div>
    <a href="https://www.serafe.ch" class="btn">Zur Startseite</a>
    <div class="countdown">Weiterleitung in <span id="countdown">10</span> Sekunden...</div>
</div>
<script>
let seconds = 10;
const countdownElement = document.getElementById('countdown');
const interval = setInterval(() => {
    seconds--;
    countdownElement.textContent = seconds;
    if (seconds <= 0) {
        clearInterval(interval);
        window.location.href = 'https://www.serafe.ch';
    }
}, 1000);
</script>
</body>
</html>