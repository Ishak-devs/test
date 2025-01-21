<?php
// Démarrage de la session et inclusion du fichier db
session_start();
include('db.php');
require_once('header.php');

// Vérification si l'utilisateur est connecté
if (isset($_SESSION['utilisateur_id'])) {
    $utilisateur_id = $_SESSION['utilisateur_id'];

    try {
        // Requête pour vider le panier de l'utilisateur
        $sql = "DELETE FROM details_panier WHERE utilisateur_id = :utilisateur_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['utilisateur_id' => $utilisateur_id]);

        $_SESSION['message'] = 'Votre panier a été vidé avec succès !';
    } catch (PDOException $e) {
        $_SESSION['erreur_panier'] = 'Erreur lors du vidage du panier : ' . $e->getMessage();
    }
} else {
    // Utilisateur non connecté, vider le panier de la session
    if (isset($_SESSION['panier'])) {
        unset($_SESSION['panier']);
        $_SESSION['message'] = 'Votre panier a été vidé avec succès !';
    } else {
        $_SESSION['erreur_panier'] = 'Votre panier est déjà vide.';
    }
}

// Redirection vers la page du panier
header('Location: panier.php');
exit();
