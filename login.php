<?php
require_once 'dbcon.php';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="js/script.js"></script>
    <link href="css/style.css" rel="stylesheet">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Narrow:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="css/styles.css">
	<link rel="stylesheet" href="css/login.css">
    <title>Login/Regisztráció</title>


</head>
<body background="images/wallpaper.jpg">

<nav>
    <div class="navbar navbar-expand-lg pt-4">
        <div class="container-fluid">
            <a href="index.php" class="brand text-decoration-none d-block d-lg-none fw-bold fs-1 ">LOGO</a>
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
                        <a href="login.php" id="sign-in" class="nav-link my-2 px-4 text-white">
                            <i class="bi bi-person-circle"></i>
                            Bejelentkezés
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
<div class="container">
    <div class="row m-2 ">
        <div class="col p-3">
            <h1>Regisztráció</h1>
            <form action="web.php" method="post" id="registerForm">
                <div class="pt-3 field">
                    <label for="registerFirstname" class="form-label">Keresztnév</label>
                    <input type="text" class="form-control" id="registerFirstname"
                           placeholder="Írja be a keresztnevét" name="firstname">
                    <small></small>
                </div>

                <div class="pt-3 field">
                    <label for="registerLastname" class="form-label">Vezetéknév</label>
                    <input type="text" class="form-control" id="registerLastname"
                           placeholder="Írja be a vezetéknévét" name="lastname">
                    <small></small>
                </div>

                <div class="pt-3 field">
                    <label for="registerEmail" class="form-label">E-mail cím</label>
                    <input type="text" class="form-control" id="registerEmail"
                           placeholder="Írja be az email címét" name="email">
                    <small></small>
                </div>

                <div class="pt-3 field">
                    <label for="registerPassword" class="form-label">Jelszó <i class="bi bi-eye-slash-fill" id="passwordEye"></i></label>
                    <input type="password" class="form-control passwordVisibiliy" name="password" id="registerPassword" placeholder="Jelszó (minimum 8 karakter)">
                    <small></small>
                    <span id="strengthDisp" class="badge displayBadge">Gyenge</span>
                </div>

                <div class="pt-3 field">
                    <label for="registerPasswordConfirm" class="form-label">Jelszó újra</label>
                    <input type="password" class="form-control" name="passwordConfirm" id="registerPasswordConfirm"
                           placeholder="Jelszó újra">
                    <small></small>
                </div>

                <div class="pt-3">
                    <input type="hidden" name="action" value="register">
                    <button type="submit" class="btn btn-primary">Regisztrálás</button>
                    <button type="reset" class="btn btn-primary resetButton" >Mégsem</button>
                </div>
            </form>

            <?php
            $r = 0;

            if (isset($_GET["r"]) and is_numeric($_GET['r'])) {
                $r = (int)$_GET["r"];

                if (array_key_exists($r, $messages)) {
                    echo '
                    <div class="alert alert-info alert-dismissible fade show m-3" role="alert">
                        ' . $messages[$r] . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                    </div>
                    ';
                }
            }
            ?>
        </div>

        <div class="col  p-3">
            <h1>Login</h1>
            <form action="web.php" method="post" id="loginForm">
                <div class="pt-3">
                    <label for="loginUsername" class="form-label">Email</label>
                    <input type="text" class="form-control" id="loginUsername"
                           placeholder="Írja be az emailét" name="username">
                    <small></small>
                </div>
                <div class="pt-3">
                    <label for="loginPassword" class="form-label">Jelszó</label>
                    <input type="password" class="form-control" id="loginPassword" placeholder="Jelszó"
                           name="password">
                    <small></small>
                </div>
                <div class="pt-3">
                    <input type="hidden" name="action" value="login">
                    <button type="submit" class="btn btn-primary">Login</button>
                    <a href="work_login.php">Munkás vagy?</a>
                </div>
            </form>


            <?php

            $l = 0;

            if (isset($_GET["l"]) and is_numeric($_GET['l'])) {
                $l = (int)$_GET["l"];

                if (array_key_exists($l, $messages)) {
                    echo '
                    <div class="alert alert-info alert-dismissible fade show m-3" role="alert">
                        ' . $messages[$l] . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                    </div>
                    ';
                }
            }
            ?>
            <a href="#" id="fl">Elfelejtette a jelszavát?</a>
            <form action="web.php" method="post" name="forget" id="forgetForm">
                <div class="pt-3">
                    <label for="forgetEmail" class="form-label">E-mail</label>
                    <input type="text" class="form-control" id="forgetEmail" placeholder="Írja be az email címét"
                           name="email">
                    <small></small>
                </div>
                <div class="pt-3">
                    <input type="hidden" name="action" value="forget">
                    <button type="submit" class="btn btn-primary">Jelszó visszaállítása</button>
                </div>
            </form>

            <?php

            $f = 0;

            if (isset($_GET["f"]) and is_numeric($_GET['f'])) {
                $f = (int)$_GET["f"];

                if (array_key_exists($f, $messages)) {
                    echo '
                    <div class="alert alert-info alert-dismissible fade show m-3" role="alert">
                        ' . $messages[$f] . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                    </div>
                    ';
                }
            }
            ?>

        </div>

    </div>
</div>
</body>
</html>