<?php

require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';
require 'vendor/phpmailer/phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = htmlspecialchars($_POST['email']);

    // Validation de l'email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<p style='color: red;'>Veuillez entrer un email valide.</p>";
        exit;
    }

    // Configuration et envoi de l'e-mail de confirmation
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP(); // Utilisation du protocole SMTP
        $mail->Host = 'smtp.mail.yahoo.com'; // Serveur SMTP de Yahoo
        $mail->SMTPAuth = true; // Activer l'authentification SMTP
        $mail->Username = 'kouicicontact@yahoo.com'; // Adresse e-mail Yahoo
        $mail->Password = 'ndvmyqlrsnmeecxw'; // Clé de sécurité Yahoo
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Sécurisation avec STARTTLS
        $mail->Port = 587; // Port SMTP pour STARTTLS

        // Configuration de l'expéditeur et du destinataire
        $mail->setFrom('kouicicontact@yahoo.com', 'ecom INSTA');
        $mail->addAddress($email); // L'email de l'utilisateur

        // Configuration du contenu de l'e-mail
        $mail->isHTML(true); // Format HTML
        $mail->CharSet = 'UTF-8'; // Encodage UTF-8
        $mail->Subject = 'Inscription réussie';
        $mail->Body = "Merci de vous être inscrit sur ecom INSTA. Votre email a été enregistré avec succès.";

        // Tentative d'envoi de l'e-mail
        if ($mail->send()) {
            echo "<p style='color: green;'>E-mail de confirmation envoyé avec succès à $email.</p>";
        } else {
            echo "<p style='color: red;'>Erreur lors de l'envoi de l'e-mail: {$mail->ErrorInfo}</p>";
        }
    } catch (Exception $e) {
        // Gestion des erreurs liées à l'e-mail
        echo "<p style='color: red;'>Erreur lors de la configuration ou de l'envoi de l'e-mail : {$e->getMessage()}</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Envoyer un e-mail</title>
</head>

<body>

    <form method="post" action="">
        <h2>Recevoir un email de confirmation</h2>
        <label for="email">Entrez votre email :</label>
        <input type="email" id="email" name="email" required placeholder="Votre email">
        <br><br>
        <input type="submit" value="reset password">
    </form>

</body>

</html>