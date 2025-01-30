<?php
// Connexion à la base de données
include "connexion.php";  // Assurez-vous que $pdo est bien défini ici

// Récupérer l'email depuis la requête (ex: via GET ou POST)
$email = htmlspecialchars($_GET['email'] ?? '');

if (empty($email)) {
    die("<p style='color: red;'>Email invalide.</p>");
}

// Récupérer le token et la date de création depuis la base de données
$stmt = $pdo->prepare("SELECT token, date_token FROM utilisateurs WHERE email = :email");
$stmt->bindParam(':email', $email);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("<p style='color: red;'>Utilisateur introuvable.</p>");
}

// Comparer le temps écoulé depuis la création du token
$current_time = new DateTime();
$token_creation_time = new DateTime($user['date_token']);
$interval = $token_creation_time->diff($current_time);

if ($interval->i >= 15 || $interval->h > 0) {
    echo ("<p style='color: red;'>Le lien a expiré. Veuillez demander un nouveau lien de confirmation.</p>");
}

// Si le délai de 15 minutes n'est pas dépassé, mettre à jour l'état du token
$updateStmt = $pdo->prepare("UPDATE utilisateurs SET etat_token = 1 WHERE email = :email");
$updateStmt->bindParam(':email', $email);
$updateStmt->execute();

echo "<h1>Email vérifié avec succès !</h1>";
