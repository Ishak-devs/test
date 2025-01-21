<head>
    <link rel="stylesheet" href="css/mdp.css">
</head>

<?php
$includeBootstrap = false;
require_once('header.php');
// Affichage des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Démarrage de la session
session_start();

// Connexion à la base de données
require_once('db.php');

// Chargement des fichiers PHPMailer
require 'vendor/phpmailer/phpmailer/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/SMTP.php';
require 'vendor/phpmailer/phpmailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Vérification si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reset'])) {
    $email = trim($_POST['email']);

    // Vérification de l'existence de l'email dans la base de données
    $stmt = $pdo->prepare('SELECT * FROM clients WHERE email = :email');
    $stmt->execute(['email' => $email]);

    if ($stmt->rowCount() > 0) {
        $client = $stmt->fetch();
        $token = bin2hex(random_bytes(16));
        $token_date = date('Y-m-d H:i:s');

        // Mise à jour du token et de la date dans la base de données
        $update_stmt = $pdo->prepare('UPDATE clients SET token = :token, token_date = :token_date WHERE email = :email');
        $update_success = $update_stmt->execute([
            'token' => $token,
            'token_date' => $token_date,
            'email' => $email
        ]);

        // Vérifier si le token a été mis à jour
        if (!$update_success) {
            error_log("Erreur lors de la mise à jour du token pour l'email : $email");
            $_SESSION['message'] = "Erreur interne. Veuillez réessayer plus tard.";
            header('Location: mdpoublie.php');
            exit;
        }

        // Envoi de l'e-mail de réinitialisation
        $mail = new PHPMailer(true);

        try {
            // Configuration du serveur SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.mail.yahoo.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'kouicicontact@yahoo.com';
            $mail->Password = 'ndvmyqlrsnmeecxw'; // À sécuriser avec des variables d'environnement
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Paramètres du message
            $mail->setFrom('kouicicontact@yahoo.com', 'ecom INSTA');
            $mail->addAddress($email);

            // Lien de réinitialisation
            $verification_link = "http://localhost/ecomishak/ecom/formulaire_reset.php?token=" . htmlspecialchars($token);

            // Contenu de l'e-mail
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'Réinitialisation de votre mot de passe';
            $mail->Body = "Bonjour,<br>Vous avez demandé à réinitialiser votre mot de passe. Cliquez sur le lien suivant pour le faire : 
                           <a href='" . $verification_link . "'>Réinitialiser mon mot de passe</a>.";
            $mail->AltBody = "Bonjour,\nVous avez demandé à réinitialiser votre mot de passe. Cliquez sur le lien suivant pour le faire : $verification_link";

            // Envoi de l'e-mail
            if ($mail->send()) {
                $_SESSION['message'] = "Un e-mail de réinitialisation a été envoyé à $email.";
            } else {
                $_SESSION['message'] = "Erreur lors de l'envoi de l'e-mail.";
            }
        } catch (Exception $e) {
            $_SESSION['message'] = "L'envoi de l'e-mail a échoué. Erreur: {$mail->ErrorInfo}";
        }

        header('Location: mdpoublie.php');
        exit;
    } else {
        $_SESSION['message'] = "Aucun utilisateur trouvé avec cet e-mail.";
    }
}
?>



<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Réinitialiser le Mot de Passe</title>
</head>

<body>
    <div class="reset-password-container">
        <h1>Réinitialiser votre Mot de Passe</h1>
        <?php if (isset($_SESSION['message'])): ?>
            <div class="message"><?php echo $_SESSION['message'];
                                    unset($_SESSION['message']); ?></div>
        <?php endif; ?>

        <form class="reset-password-form" action="mdpoublie.php" method="POST">
            <label class="form-label" for="email">Votre Email :</label>
            <input class="form-input" type="email" id="email" name="email" required>
            <button class="form-button" type="submit" name="reset">Réinitialiser</button>
        </form>
    </div>
</body>
<?php require_once('footer.html'); ?>
<script src="js/modifmdp.js"></script>

</html>