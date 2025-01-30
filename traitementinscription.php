<?php

// Chargement des fichiers nécessaires pour PHPMailer
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';
require 'vendor/phpmailer/phpmailer/src/Exception.php';

// Connexion à la base de données
include "connexion.php";  // Fichier de connexion

// Utilisation des classes PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Vérification si la méthode de la requête est POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Récupérer et sécuriser l'email
    $email = htmlspecialchars($_POST['email']);

    // Validation de l'email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<p style='color: red;'>Veuillez entrer un email valide.</p>";
        exit;
    }

    try {
        // Connexion à la base de données avec PDO
        $dsn = 'mysql:host=localhost;dbname=ecom2425;charset=utf8mb4';
        $user = 'root';
        $pass = '';
        $pdo = new PDO($dsn, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if (!$user) {
            echo "<p style='color: red;'>Cet email n'existe pas dans nos bases.</p>";
            exit;
        }

        $current_time = new DateTime();
        $token_creation_time = new DateTime($user['date']);
        $interval = $token_creation_time->diff($current_time);
        $minutes_passed = $interval->i + ($interval->h * 60); // Convertir en minutes

        if (!$user['token'] || $minutes_passed >= 15) {
            // Générer un nouveau token
            $new_token = bin2hex(random_bytes(32));

            // Mise à jour du token et de la date dans la base de données
            $update_stmt = $pdo->prepare("UPDATE utilisateurs SET token = :token, date = NOW() WHERE email = :email");



















            $update_stmt->bindParam(':token', $new_token);
            $update_stmt->bindParam(':email', $email);
            $update_stmt->execute();
        } else {
            // Si le token est encore valide, on utilise l'existant
            $new_token = $user['token'];
        }

        // Lien de réinitialisation
        $reset_link = "https://votre-site.com/reset_password.php?token=$new_token";

        // Envoi de l'email
        $mail = new PHPMailer(true);
        try {
            // Paramétrage SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.mail.yahoo.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'kouicicontact@yahoo.com';
            $mail->Password = 'ndvmyqlrsnmeecxw';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Configuration de l'expéditeur et du destinataire
            $mail->setFrom('kouicicontact@yahoo.com', 'ecom INSTA');
            $mail->addAddress($email);

            // Configuration du contenu de l'e-mail
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'Réinitialisation du mot de passe';
            $mail->Body = "
                Bonjour,<br>
                Vous avez demandé une réinitialisation de votre mot de passe sur ecom INSTA.<br>
                Cliquez sur le lien ci-dessous pour réinitialiser votre mot de passe :<br>
                <a href='$reset_link'>$reset_link</a><br><br>
                Ce lien expirera dans 15 minutes.
            ";

            // Envoi de l'e-mail
            if ($mail->send()) {
                echo "<p style='color: green;'>Un e-mail de réinitialisation a été envoyé à $email.</p>";
            } else {
                echo "<p style='color: red;'>Erreur lors de l'envoi de l'e-mail : {$mail->ErrorInfo}</p>";
            }
        } catch (Exception $e) {
            echo "<p style='color: red;'>Erreur lors de l'envoi de l'e-mail : {$e->getMessage()}</p>";
        }
    } catch (PDOException $e) {
        echo "<p style='color: red;'>Erreur de connexion à la base de données : {$e->getMessage()}</p>";
    }
}
