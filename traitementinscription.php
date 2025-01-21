<?php
session_start(); // Démarrer la session

include('db.php'); // Connexion à la base de données
require 'vendor/autoload.php'; // Charger PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Fonction pour envoyer l'email de vérification
function sendVerificationEmail($email, $prenom, $nom, $token)
{
    $mail = new PHPMailer(true);
    try {
        // Configuration de PHPMailer
        $mail->isSMTP();
        $mail->Host = 'smtp.mail.yahoo.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'kouicicontact@yahoo.com';
        $mail->Password = 'ndvmyqlrsnmeecxw';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Expéditeur et destinataire
        $mail->setFrom('kouicicontact@yahoo.com', 'ecom INSTA');
        $mail->addAddress($email, htmlspecialchars("$prenom $nom"));

        // Lien de vérification
        $verification_link = "http://localhost/ecomINSTA/verification.php?token=" . htmlspecialchars($token);

        // Mail HTML et texte brut 
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = 'Vérifiez votre adresse e-mail';
        $mail->Body = "Bonjour " . htmlspecialchars($prenom) . ",<br>Merci de vous être inscrit sur ecom INSTA !<br>
                       Veuillez vérifier votre adresse e-mail en cliquant sur le lien suivant : 
                       <a href='" . $verification_link . "'>Vérifiez votre e-mail</a>.";
        $mail->AltBody = "Bonjour " . htmlspecialchars($prenom) . ",\nMerci de vous être inscrit sur ecom INSTA !\n
                          Veuillez vérifier votre adresse e-mail en cliquant sur le lien suivant : $verification_link";

        // Envoi du mail
        if ($mail->send()) {
            error_log("E-mail de vérification envoyé avec succès à $email.");
            return true;
        } else {
            $_SESSION['message'] = "L'envoi de l'e-mail a échoué.";
            error_log("Erreur lors de l'envoi de l'e-mail: {$mail->ErrorInfo}");
            return false;
        }
    } catch (Exception $e) {
        $_SESSION['message'] = "L'inscription a réussi, mais l'envoi de l'e-mail a échoué. Erreur: {$mail->ErrorInfo}";
        error_log("Erreur lors de l'envoi de l'e-mail: {$mail->ErrorInfo}");
        return false;
    }
}

// Si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['inscription'])) {
    $prenom = trim($_POST['prenom']);
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $numero_telephone = trim($_POST['numero_telephone']);
    $mot_de_passe = $_POST['mot_de_passe'];
    $mot_de_passedeux = $_POST['mot_de_passedeux'];

    // Vérification si les mots de passe correspondent
    if ($mot_de_passe !== $mot_de_passedeux) {
        $_SESSION['message'] = "Les mots de passe ne correspondent pas.";
        header('Location: inscription.php');
        exit;
    }

    // Vérification si l'email existe déjà
    $stmt = $pdo->prepare('SELECT * FROM clients WHERE email = :email');
    $stmt->execute(['email' => $email]);

    // Si l'email existe déjà
    if ($stmt->rowCount() > 0) {
        $client = $stmt->fetch();
        // Stockage de la date du token
        $token_date = strtotime($client['token_date']);

        // Si le token a été envoyé il y a moins de 30 minutes, message au client
        if ($token_date > (time() - 30 * 60)) {
            $_SESSION['messagemail'] = "L'email $email existe déjà. Veuillez valider votre email s'il vous plaît.";
            header('Location: inscription.php');
            exit;
        } else {
            // Sinon création d'un nouveau token
            $new_token = bin2hex(random_bytes(16));
            $new_token_date = date('Y-m-d H:i:s');

            // Mise à jour du token dans la table clients
            $update_stmt = $pdo->prepare('UPDATE clients SET token = :token, token_date = :token_date WHERE email = :email');
            $update_success = $update_stmt->execute([
                'token' => $new_token,
                'token_date' => $new_token_date,
                'email' => $email
            ]);

            // Si la mise à jour a réussi, envoi du mail de vérification
            if ($update_success) {
                $_SESSION['message'] = "Un e-mail de vérification a été envoyé. Veuillez vérifier votre email.";
                sendVerificationEmail($email, $prenom, $nom, $new_token);
                header('Location: inscription.php');
                exit;
            } else {
                $_SESSION['message'] = "Échec de la mise à jour du token pour l'utilisateur $email.";
                header('Location: inscription.php');
                exit;
            }
        }
    } else {
        // Si l'email n'existe pas, inscription de l'utilisateur
        $mot_de_passe_hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);
        $token = bin2hex(random_bytes(16));
        $token_date = date('Y-m-d H:i:s');

        // Insertion du nouvel utilisateur dans la base de données
        $stmt = $pdo->prepare('INSERT INTO clients (prenom, nom, email, mot_de_passe, numero_telephone, token, token_date) 
                                VALUES (:prenom, :nom, :email, :mot_de_passe, :numero_telephone, :token, :token_date)');

        $params = [
            'prenom' => $prenom,
            'nom' => $nom,
            'email' => $email,
            'mot_de_passe' => $mot_de_passe_hash,
            'numero_telephone' => $numero_telephone,
            'token' => $token,
            'token_date' => $token_date,
        ];

        // Si l'insertion réussit, envoi de l'email de vérification
        if ($stmt->execute($params)) {
            $_SESSION['message'] = "Un e-mail de vérification a été envoyé. Veuillez vérifier votre email.";
            sendVerificationEmail($email, $prenom, $nom, $token);
        } else {
            $_SESSION['message'] = "Échec de l'inscription de l'utilisateur $email.";
        }
        header('Location: inscription.php');
        exit;
    }
} else {
    $_SESSION['message'] = "Erreur : le formulaire n'a pas été soumis correctement.";
    header('Location: inscription.php');
    exit;
}
