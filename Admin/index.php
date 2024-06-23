<?php
session_start();
include '../dbcon.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/SMTP.php';

// Function to generate a random secret
function generateSecret() {
    return mt_rand(100000, 999999);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['verify_secret'])) {
        $entered_secret = $_POST['secret'];
        if (isset($_SESSION['admin_email'])) {
            $stmt = $conn->prepare("SELECT secret FROM Admin WHERE admin_email = :email");
            $stmt->execute(['email' => $_SESSION['admin_email']]);
            $admin = $stmt->fetch();

            if ($admin && $entered_secret == $admin['secret']) {
                // Clear the secret in the database
                $stmt = $conn->prepare("UPDATE Admin SET secret = NULL WHERE admin_email = :email");
                $stmt->execute(['email' => $_SESSION['admin_email']]);

                // Redirect to menu.php
                header("Location: menu.php");
                exit();
            } else {
                $error = "Invalid secret code.";
            }
        } else {
            $error = "Session expired. Please log in again.";
        }
    } else {
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        // Validate input
        if (!empty($email) && !empty($password)) {
            // Prepare and execute the query
            $stmt = $conn->prepare("SELECT * FROM Admin WHERE admin_email = :email");
            $stmt->execute(['email' => $email]);
            $admin = $stmt->fetch();

            // Verify hashed password
            if ($admin && password_verify($password, $admin['admin_password'])) {
                // Set session variables
                $_SESSION['admin_email'] = trim($admin['admin_email']);

                // Generate and store secret in database
                $secret = generateSecret();
                $stmt = $conn->prepare("UPDATE Admin SET secret = :secret WHERE admin_email = :email");
                $stmt->execute(['secret' => $secret, 'email' => $_SESSION['admin_email']]);

                // Use the email from the database
                $dbEmail = $admin['admin_email'];

                // Send email with the secret code
                try {
                    $mail = new PHPMailer(true);
                    $mail->CharSet = "UTF-8";
                    // SMTP settings
                    $mail->isSMTP();
                    $mail->Host = 'teammates.stud.vts.su.ac.rs'; // SMTP server address
                    $mail->SMTPAuth = true;
                    $mail->Username = 'teammates'; // SMTP username
                    $mail->Password = 'zuFoAHz3Hx82uC4'; // SMTP password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;
                    $mail->isHTML(true);
                    $mail->Encoding = 'base64';
                    $mail->setFrom('teammates@teammates.stud.vts.su.ac.rs', 'Teammates');
                    $mail->addAddress($dbEmail); // Recipient email from the database
                    $mail->Subject = 'Bejelentkezési kód'; // Subject of the email
                    $mail->Body = '<p>Kedves Admin, itt van a bejelentkezési kódja: ' . $secret . '</p>'; // Body of the email

                    // Send email
                    $mail->send();
                    echo json_encode(['status' => 'success', 'email' => $dbEmail]); // AJAX response: successfully sent secret code via email
                    exit();
                } catch (Exception $e) {
                    echo json_encode(['status' => 'error', 'message' => "Hiba az e-mail küldése közben: {$mail->ErrorInfo}", 'email' => $dbEmail]); // Error message if sending email fails
                    exit();
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Invalid email or password.', 'email' => $email]);
                exit();
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Please fill in all fields.', 'email' => $email]);
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="css/styles.css">
	<link rel="stylesheet" href="css/index.css">
	//<script src="js/index.js"></script>
</head>
    
<body background="../images/wallpaper.jpg">
    <div class="login-container">
        <h2>Login</h2>
        <div id="message"></div>
        <form id="login-form" action="index.php" method="post">
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div>
                <button type="submit">Login</button>
            </div>
        </form>
        <div class="secret-container" id="secret-container">
            <h2>Kétlépcsős azonosítás</h2>
            <form id="secret-form" action="index.php" method="post">
                <input type="hidden" name="verify_secret" value="1">
                <div>
                    <label for="secret">Kód:</label>
                    <input type="text" id="secret" name="secret" required>
                </div>
                <div>
                    <button type="submit">Ellenőrzés</button>
                </div>
            </form>
        </div>
    </div>
       <script>
        document.getElementById('login-form').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            var formData = new FormData(this);

            fetch('index.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    document.getElementById('secret-container').style.display = 'block';
                    document.getElementById('message').innerHTML = ''; // Clear any previous error messages
                    console.log('Email:', data.email); // Log the email to the console for debugging
                } else {
                    document.getElementById('message').innerHTML = '<p class="error">' + data.message + '</p>';
                    console.log('Email:', data.email); // Log the email to the console for debugging
                }
            })
            .catch(error => console.error('Error:', error));
        });

        document.getElementById('secret-form').addEventListener('submit', function(event) {
            // You can either use this JS part to send form via fetch to the backend without redirecting or leave this for traditional form submission.
        });
    </script>
</body>
</html>
