<?php
session_start();
require_once('db.php');

if (isset($_POST['produit_id'])) {
    $produit_id = $_POST['produit_id'];

    if (isset($_SESSION['utilisateur_id'])) {
        $utilisateur_id = $_SESSION['utilisateur_id'];

        try {
            // Vérifier la quantité actuelle du produit dans la base de données
            $stmt = $pdo->prepare('SELECT quantite FROM details_panier WHERE utilisateur_id = :utilisateur_id AND produit_id = :produit_id');
            $stmt->execute(['utilisateur_id' => $utilisateur_id, 'produit_id' => $produit_id]);
            $produit = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($produit) {
                if ($produit['quantite'] > 1) {
                    // Diminuer la quantité de 1
                    $stmt = $pdo->prepare('UPDATE details_panier SET quantite = quantite - 1 WHERE utilisateur_id = :utilisateur_id AND produit_id = :produit_id');
                    $stmt->execute(['utilisateur_id' => $utilisateur_id, 'produit_id' => $produit_id]);
                } else {
                    // Supprimer l'article du panier
                    $stmt = $pdo->prepare('DELETE FROM details_panier WHERE utilisateur_id = :utilisateur_id AND produit_id = :produit_id');
                    $stmt->execute(['utilisateur_id' => $utilisateur_id, 'produit_id' => $produit_id]);
                }
            }


            if (isset($_SESSION['panier'])) {
                foreach ($_SESSION['panier'] as $key => $item) {
                    if ($item['produit_id'] == $produit_id) {
                        // Diminuer la quantité ou supprimer si elle est à 1
                        if ($item['quantite'] > 1) {
                            $_SESSION['panier'][$key]['quantite'] -= 1;
                        } else {
                            unset($_SESSION['panier'][$key]);
                        }
                        break;
                    }
                }
            }
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
        }
    } else {
        // Gestion du panier pour les utilisateurs non connectés
        if (isset($_SESSION['panier'])) {
            foreach ($_SESSION['panier'] as $key => $item) {
                if ($item['produit_id'] == $produit_id) {
                    // Diminuer la quantité ou supprimer si elle est à 1
                    if ($item['quantite'] > 1) {
                        $_SESSION['panier'][$key]['quantite'] -= 1;
                    } else {
                        unset($_SESSION['panier'][$key]);
                    }
                    break;
                }
            }
        }
    }

    // Rediriger vers la page du panier
    header('Location: panier.php');
    exit;
}
