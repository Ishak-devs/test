<?php
// Démarrage de la session et inclusion du fichier db
session_start();
ob_start();
include('db.php');
require_once('header.php');

// Vérification si un produit est à supprimer
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['produit_id'])) {
    $produit_id = $_GET['produit_id'];

    if (isset($_SESSION['utilisateur_id'])) {
        // Utilisateur connecté, suppression du produit de la base de données
        $utilisateur_id = $_SESSION['utilisateur_id'];

        try {
            $stmt = $pdo->prepare("DELETE FROM details_panier WHERE produit_id = :produit_id AND utilisateur_id = :utilisateur_id");
            $stmt->execute(['produit_id' => $produit_id, 'utilisateur_id' => $utilisateur_id]);

            if ($stmt->rowCount() > 0) {
                $_SESSION['message'] = 'Produit supprimé du panier.';
            } else {
                $_SESSION['erreur_panier'] = 'Produit non trouvé dans le panier.';
            }
        } catch (PDOException $e) {
            $_SESSION['erreur_panier'] = 'Erreur lors de la suppression : ' . $e->getMessage();
        }
    } else {
        // Utilisateur non connecté, suppression du produit de la session
        if (isset($_SESSION['panier'][$produit_id])) {
            unset($_SESSION['panier'][$produit_id]);
            $_SESSION['message'] = 'Produit supprimé du panier.';
        } else {
            $_SESSION['erreur_panier'] = 'Produit non trouvé dans le panier.';
        }
    }
}

// Redirection vers la page du panier
header('Location: panier.php');
ob_end_flush();
exit();
