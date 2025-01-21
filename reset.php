<?php
// Affichage des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Démarrage de la session
session_start();

// Connexion à la base de données
require_once('db.php');

// Vérifiez si la connexion à la base de données a réussi
if ($pdo) {
    error_log("Connexion à la base de données réussie.");
} else {
    error_log("Échec de la connexion à la base de données.");
}

// Vérification du token
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Vérification de l'existence du token dans la base de données
    $stmt = $pdo->prepare('SELECT * FROM clients WHERE token = :token');
    $stmt->execute(['token' => $token]);

    if ($stmt->rowCount() > 0) {
        // Soumission du formulaire
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password']; // Vérification du champ de confirmation

            // Vérifier si les mots de passe correspondent
            if ($new_password !== $confirm_password) {
                $_SESSION['message'] = "Les mots de passe ne correspondent pas.";
            } else if (empty($new_password)) {
                $_SESSION['message'] = "Le mot de passe ne peut pas être vide.";
            } else {
                // Hachage du nouveau mot de passe
                $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);

                // Mise à jour du mot de passe, suppression du token, et vérification de l'email
                $update_stmt = $pdo->prepare('
                    UPDATE clients 
                    SET mot_de_passe = :mot_de_passe, token = NULL, token_date = NULL, email_verified = 1 
                    WHERE token = :token
                ');

                $success = $update_stmt->execute([
                    'mot_de_passe' => $new_password_hash,
                    'token' => $token
                ]);

                // Vérification de la mise à jour
                if ($success) {
                    $_SESSION['message'] = "Votre mot de passe a été réinitialisé avec succès et votre e-mail est vérifié.";
                    // Ne plus afficher le formulaire après succès
                } else {
                    // Gestion des erreurs SQL
                    $errorInfo = $update_stmt->errorInfo();
                    $_SESSION['message'] = "Erreur lors de la mise à jour du mot de passe : " . $errorInfo[2];
                }
            }
        }
    } else {
        $_SESSION['message'] = "Token invalide.";
    }
} else {
    $_SESSION['message'] = "Aucune méthode de requête valide trouvée.";
}
