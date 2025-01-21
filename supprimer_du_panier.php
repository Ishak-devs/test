<?php
session_start();
require_once('db.php');

if (isset($_POST['produit_id']) && isset($_SESSION['utilisateur_id'])) {
    $produit_id = $_POST['produit_id'];
    $utilisateur_id = $_SESSION['utilisateur_id'];

    try {
        // Supprimer le produit du panier pour cet utilisateur
        $stmt = $pdo->prepare('DELETE FROM details_panier WHERE utilisateur_id = :utilisateur_id AND produit_id = :produit_id');
        $stmt->execute(['utilisateur_id' => $utilisateur_id, 'produit_id' => $produit_id]);

        // Rediriger vers la page du panier
        header('Location: panier.php');
        exit;
    } catch (PDOException $e) {
        echo 'Erreur : ' . $e->getMessage();
    }
} elseif (isset($_POST['produit_id'])) {
    // Pour les utilisateurs non connectés, gérer le panier dans la session
    $produit_id = $_POST['produit_id'];

    if (isset($_SESSION['panier'])) {
        foreach ($_SESSION['panier'] as $key => $item) {
            if ($item['produit_id'] == $produit_id) {
                unset($_SESSION['panier'][$key]);
                break;
            }
        }
    }

    // Rediriger vers la page du panier
    header('Location: panier.php');
    exit;
} else {
    header('Location: panier.php');
    exit;
}
