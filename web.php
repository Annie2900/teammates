<?php
session_start();
require_once "dbcon.php";
require_once "functions_def.php";

$password = "";
$passwordConfirm = "";
$firstname = "";
$lastname = "";
$email = "";
$action = "";

$referer = $_SERVER['HTTP_REFERER'];

$action = $_POST["action"];

if ($action != "" and in_array($action, $actions) and strpos($referer, SITE) !== false ) {


    switch ($action) {
        case "login":

            $username = trim($_POST["username"]);
            $password = trim($_POST["password"]);

            if (!empty($username) and !empty($password)) {
                $data = checkUserLogin( $username, $password);

                if ($data and is_int($data['id_user'])) {
                    $_SESSION['username'] = $username;
                    $_SESSION['id_user'] = $data['id_user'];
                    redirection('index.php');
                } else {
                    redirection('login.php?l=1');
                }

            } else {
                redirection('login.php?l=1');
            }
            break;


        case "register" :

            if (isset($_POST['firstname'])) {
                $firstname = trim($_POST["firstname"]);
            }

            if (isset($_POST['lastname'])) {
                $lastname = trim($_POST["lastname"]);
            }

            if (isset($_POST['password'])) {
                $password = trim($_POST["password"]);
            }

            if (isset($_POST['passwordConfirm'])) {
                $passwordConfirm = trim($_POST["passwordConfirm"]);
            }

            if (isset($_POST['email'])) {
                $email = trim($_POST["email"]);
            }

            if (empty($firstname)) {
                redirection('login.php?r=4');
            }

            if (empty($lastname)) {
                redirection('login.php?r=4');
            }

            if (empty($password)) {
                redirection('login.php?r=9');
            }

            if (!preg_match("#.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#", $password)) {
                redirection('login.php?l=10');
            }

            if (empty($passwordConfirm)) {
                redirection('login.php?r=9');
            }

            if ($password !== $passwordConfirm) {
                redirection('login.php?r=7');
            }

            if (empty($email) or !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                redirection('login.php?r=8');
            }

            if (!existsUser($pdo, $email)) {
                $token = createToken(20);
                if ($token) {
                    $id_user = registerUser($pdo, $password, $firstname, $lastname, $email, $token);
                    try {
                        $body = "Az emailje $email. A felhasználó aktiválása céljából kattintson erre a  <a href=" . SITE . "active.php?token=$token>linkre.</a>";
                        sendEmail($pdo, $email, $emailMessages['register'], $body, $id_user);
                        redirection("login.php?r=3");
                    } catch (Exception $e) {
                        error_log("****************************************");
                        error_log($e->getMessage());
                        error_log("file:" . $e->getFile() . " line:" . $e->getLine());
                        redirection("login.php?r=11");
                    }
                }
            } else {
                redirection('login.php?r=2');
            }

            break;

        case "forget" :
            $email = trim($_POST["email"]);
            if (!empty($email) and getUserData($pdo, 'id_user', 'email', $email)) {
                $token = createToken(20);
                if ($token) {
                    setForgottenToken($pdo, $email, $token);
                    $id_user = getUserData($pdo, 'id_user', 'email', $email);
                    try {
                        $body = "Jelszó megváltoztatása céljából kattintson erre a linkre: <a href=" . SITE . "forget.php?token=$token>link</a>.";
                        sendEmail($pdo, $email, $emailMessages['forget'], $body, $id_user);
                        redirection('login.php?f=13');
                    } catch (Exception $e) {
                        error_log("****************************************");
                        error_log($e->getMessage());
                        error_log("file:" . $e->getFile() . " line:" . $e->getLine());
                        redirection("login.php?f=11");
                    }
                } else {
                    redirection('login.php?f=14');
                }
            } else {
                redirection('login.php?f=13');
            }
            break;

        default:
            redirection('login.php?l=0');
            break;
    }

} else {
    redirection('login.php?l=0');
}
