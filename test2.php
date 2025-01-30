<?php

// Chargement des fichiers nécessaires pour PHPMailer
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';
require 'vendor/phpmailer/phpmailer/src/Exception.php';

// Connexion à la base de données
include "connexion.php";  // Connexion à la base de données

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

    // Connexion à la base de données
    $dsn = 'mysql:host=localhost;dbname=ecom2425;charset=utf8mb4';
    $user = 'root';
    $pass = '';

    try {
        // Connexion à la base de données avec PDO
        $pdo = new PDO($dsn, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Vérification si l'email existe dans la base de données
        $stmt = $pdo->prepare("SELECT * FROM clients WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            echo "<p style='color: red;'>Cet email n'existe pas dans nos bases.</p>";
            exit;
        }

        // Configuration et envoi de l'e-mail de réinitialisation du mot de passe
        $mail = new PHPMailer(true);
        try {
            // Paramétrage de l'SMTP
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
            $mail->Subject = 'Réinitialisation du mot de passe';
            $mail->Body = "Bonjour, <br>Vous avez demandé une réinitialisation de votre mot de passe sur ecom INSTA. Cliquez sur le lien ci-dessous pour réinitialiser votre mot de passe : <br></a>";

            // Envoi de l'e-mail
            if ($mail->send()) {
                echo "<p style='color: green;'>E-mail de réinitialisation envoyé avec succès à $email.</p>";
            } else {
                echo "<p style='color: red;'>Erreur lors de l'envoi de l'e-mail: {$mail->ErrorInfo}</p>";
            }
        } catch (Exception $e) {
            // Gestion des erreurs liées à l'e-mail
            echo "<p style='color: red;'>Erreur lors de la configuration ou de l'envoi de l'e-mail : {$e->getMessage()}</p>";
        }
    } catch (PDOException $e) {
        // Gestion des erreurs liées à la base de données
        echo "<p style='color: red;'>Erreur lors de la connexion à la base de données : {$e->getMessage()}</p>";
    }
}
