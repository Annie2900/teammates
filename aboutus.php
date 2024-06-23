<?php
session_start();
require_once 'dbcon.php';
require_once 'functions_def.php';

// Ellenőrizzük, hogy be van-e jelentkezve a felhasználó
$loggedIn = isset($_SESSION['id_user']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
	<link rel="stylesheet" href="css/rolunk.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Narrow:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    <script src="js/scripts.js" ></script>
    <title>Rólunk</title>
</head>
<body background="images/wallpaper.jpg">    
    <nav>
    <div class="navbar navbar-expand-lg pt-4">
        <div class="container-fluid">
            <a href="#" class="brand text-decoration-none d-block d-lg-none fw-bold fs-1 ">LOGO</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                <ul id="nav-length" class="navbar-nav justify-content-between border-top border-2 text-center">
                    <li class="nav-item">
                        <a href="index.php" class="nav-link border-hover py-3 ">Kezdőlap</a>
                    </li>
                    <li class="nav-item">
                        <a href="food.php" class="nav-link border-hover py-3 ">Étlap</a>
                    </li>
                    <li class="nav-item">
                        <a href="aboutus.php" class="nav-link border-hover py-3 ">Rólunk</a>
                    </li>
                    <li class="nav-item">
                        <a href="review.php" class="nav-link border-hover py-3 ">Értékelések</a>
                    </li>
                    <li class="nav-item">
                        <?php if ($loggedIn): ?>
                            <a href="profile.php" id="sign-in" class="nav-link my-2 px-4 text-white">
                                <i class="bi bi-person-circle"></i>
                                Profil
                            </a>
                        <?php else: ?>
                            <a href="profile.php" id="sign-in" class="nav-link my-2 px-4 text-white">
                                <i class="bi bi-person-circle"></i>
                                Sign Up
                            </a>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
    
    
    
    <div class="container">
        <div class="section">
            <h1>Rólunk</h1>
            <p>Üdvözöljük az étterem és ételkiszállító vállalkozásunkban, ahol friss és ínycsiklandó ételeket szállítunk az Ön otthonába, akár napközben, akár este.
				<br><br>Álmaink azon dolgozunk, hogy bármikor az Ön ajtaja elé varázsolhassunk friss és meleg ételeket. Legyen szó egy gyors ebédről a munkahelyen, egy romantikus 
                vacsoráról otthon, vagy akár egy kényelmes családi összejövetelről, mi itt vagyunk, hogy gondoskodjunk az Ön és szerettei étkezéséről.</p>
        </div>

        <div class="section">
            <div class="section-left">
                <h2>Múltunk</h2>
                <p>Egy kis bódéból indultunk el kezdetben, két munkással, akik hetente hétszer dolgoztak. Kedves vendégeink folyamatosan visszajártak hozzánk és támogattak minket, 
					ami lehetővé tette számunkra, hogy lépésről lépésre növeljük üzletünket és szolgáltatásainkat. Az elkötelezettségünk és a minőségi szolgáltatás iránti szenvedélyünk 
                    meghozta gyümölcsét, és ma már büszkén állunk itt, ahol vagyunk, egy elismert és megbecsült vállalkozásként, amely továbbra is az Önök támogatására és bizalmára 
                    támaszkodik.</p>
            </div>
            <div class="section-right">
                <img src="uploadedImages/bodekep.jpg" width="82%" alt="Bode">
            </div>
            <div style="clear: both;"></div>
        </div>
		<div class="section" style="text-align: center;"><h2>Csapatunk</h2></div>
        <div class="section">
            <div class="section-left"> 
                <div class="team-member">
                    <img src="uploadedImages/Anett.jpg" width="82%" alt="Anett">
                    <h3>Oravec Anett</h3>
                    <p>Ügyvezető igazgató</p>
                </div>
            </div>
            <div class="section-right">
                <div class="team-member">
                    <img src="uploadedImages/David.png" width="82%" alt="David">
                    <h3>Bašić Palković Dávid</h3>
                    <p>Műveleti igazgató</p>
                </div>
            </div>
            <div style="clear: both;"></div>
        </div>

        <div class="section" >
            <h2>Missziónk</h2>
            <p style="text-align: center;">Missziónk, hogy friss és ízletes ételeket szállítsunk közvetlenül az Ön otthonába, miközben kiemelten figyelünk a minőségre és a fenntarthatóságra. Arra törekszünk, 
				hogy helyi termelőktől származó alapanyagokat használjunk, ezzel támogatva a közösségünket és csökkentve az ökológiai lábnyomunkat. Célunk, hogy minden 
				egyes rendelés egy különleges gasztronómiai élményt nyújtson, amely örömet és kényelmet hoz a mindennapokba. Ügyfeleink elégedettsége és egészsége mindig 
				az első helyen áll, ezért folyamatosan fejlődünk és innoválunk.</p>
        </div>

        <div class="section">
            <h2>Kapcsolat</h2>
            <p>Elérhetőségeinken folyamatosan kapcsolatba állhat velünk.</p>
            <address>
                Email: <a href="mailto:teammates@teammates.stud.vts.su.ac.rs" style="color:#FFDBBB;">teammates@teammates.stud.vts.su.ac.rs</a><br>
                Phone: +381-631744-942
            </address>
        </div>
    </div>
</body>
</html>
