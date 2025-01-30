<?php


require 'vendor/autoload.php';

/*require 'PHPMailer.php';
require 'SMTP.php';
require 'Exception.php';*/

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars($_POST['email']);
}
try {
    $token = bin2hex(random_bytes(16));
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = 'smtp.mail.yahoo.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'kouicicontact@yahoo.com';
    $mail->Password = 'ndvmyqlrsnmeecxw';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->setFrom('kouicicontact@yahoo.com', 'E-commerce');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Subject = 'Réinitialisation de mot de passe';
    $mail->Body = "Bonjour, <br>Vous avez demandé une réinitialisation de votre mot de passe sur E-Commerce.
Cliquez sur le lien ci-dessous pour réinitialiser votre mot de passe :
<br><a href='http://localhost/BTS-project/newE-project/emailverif.php?token=" . urlencode($token) . "'>Réinitialiser mon mot de passe</a>";

    // Connexion à la base de données
    $servername = "localhost";
    $dbname = "e_commerce_project";
    $dbusername = "root";
    $dbpassword = "";

    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    // Vérifier si l'email existe
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() == 0) {
        echo "<p style='color: red;'>Cet email n'existe pas dans nos bases.</p>";
        exit;
    }

    // Insertion du token
    $expires_at = date("Y-m-d H:i:s", strtotime("+15 minutes"));
    $etat_du_ticket = 1;

    $insertStmt = $conn->prepare("INSERT INTO password_resets (email, token, expires_at, etat_du_ticket) VALUES (:email, :token, :expires_at, :etat_du_ticket)");
    $insertStmt->bindParam(':email', $email);
    $insertStmt->bindParam(':token', $token);
    $insertStmt->bindParam(':expires_at', $expires_at);
    $insertStmt->bindParam(':etat_du_ticket', $etat_du_ticket);
    $insertStmt->execute();
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
