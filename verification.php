<?php
session_start();
include('db.php'); // Connexion à la base de données

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Vérifier si le token existe dans la base de données
    $stmt = $pdo->prepare('SELECT * FROM clients WHERE token = :token');
    $stmt->execute(['token' => $token]);
    $client = $stmt->fetch();

    if ($client) {
        // Mettre à jour l'état de l'utilisateur (par exemple, vérifier l'email)
        $stmt = $pdo->prepare('UPDATE clients SET email_verified = 1, token = NULL WHERE token = :token');
        $stmt->execute(['token' => $token]);

        // Message de succès
        $_SESSION['message'] = "Votre adresse e-mail a été vérifiée avec succès ! Vous pouvez maintenant vous connecter.";
        header("Location: index.php"); // Redirige vers la page d'accueil
        exit;
    } else {
        // Token invalide
        $_SESSION['message'] = "Le lien de vérification est invalide ou a déjà été utilisé.";
        header("Location: index.php"); // Redirige vers la page d'accueil
        exit;
    }
} else {
    // Pas de token fourni
    $_SESSION['message'] = "Aucun token fourni.";
    header("Location: index.php"); // Redirige vers la page d'accueil
    exit;
}
