<?php
session_start();
include('db.php');

// vérification si la méthode est requête POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $produit_id = $_POST['produit_id'];
    $quantite = intval($_POST['quantite']);
    // vérification si un utilisateur est connecté
    $utilisateur_id = isset($_SESSION['utilisateur_id']) ? $_SESSION['utilisateur_id'] : null;

    //vérification d'au moins une quantité
    if ($quantite > 0) {
        try {
            // préparation de la requête
            $stmt = $pdo->prepare("SELECT nom_produit, prix FROM produits WHERE produit_id = :produit_id");
            $stmt->execute(['produit_id' => $produit_id]);
            $produit = $stmt->fetch(PDO::FETCH_ASSOC);

            // si un produit est retrouvé on stocke les infos
            if ($produit) {
                $nom_produit = $produit['nom_produit'];
                $prix = $produit['prix'];

                // si l'utilisateur est connecté, on gère le panier en base de données
                if ($utilisateur_id) {
                    $stmt = $pdo->prepare("SELECT utilisateur_id FROM clients WHERE utilisateur_id = :utilisateur_id");
                    $stmt->execute(['utilisateur_id' => $utilisateur_id]);
                    $client = $stmt->fetch(PDO::FETCH_ASSOC);

                    // si un client est retrouvé on vérifie si un panier existe
                    if ($client) {
                        $stmt = $pdo->prepare("SELECT panier_id FROM paniers WHERE utilisateur_id = :utilisateur_id");
                        $stmt->execute(['utilisateur_id' => $utilisateur_id]);
                        $panier_id = $stmt->fetchColumn();

                        // si un panier n'existe pas, on en crée un pour l'utilisateur
                        if (!$panier_id) {
                            $stmt = $pdo->prepare("INSERT INTO paniers (utilisateur_id) VALUES (:utilisateur_id)");
                            $stmt->execute(['utilisateur_id' => $utilisateur_id]);
                            $panier_id = $pdo->lastInsertId();
                        }

                        // vérification de l'existence du produit dans le panier
                        $stmt = $pdo->prepare("SELECT quantite FROM details_panier WHERE produit_id = :produit_id AND panier_id = :panier_id");
                        $stmt->execute(['produit_id' => $produit_id, 'panier_id' => $panier_id]);
                        $existing_quantite = $stmt->fetchColumn();

                        // mise à jour de la quantité
                        if ($existing_quantite) {
                            $new_quantite = $existing_quantite + $quantite;
                            $stmt = $pdo->prepare("UPDATE details_panier SET quantite = :quantite WHERE produit_id = :produit_id AND panier_id = :panier_id");
                            $stmt->execute(['quantite' => $new_quantite, 'produit_id' => $produit_id, 'panier_id' => $panier_id]);
                        } else {
                            $stmt = $pdo->prepare("INSERT INTO details_panier (panier_id, produit_id, quantite, prix, utilisateur_id) VALUES (:panier_id, :produit_id, :quantite, :prix, :utilisateur_id)");
                            $stmt->execute(['panier_id' => $panier_id, 'produit_id' => $produit_id, 'quantite' => $quantite, 'prix' => $prix, 'utilisateur_id' => $utilisateur_id]);
                        }

                        // Message de succès
                        echo json_encode(['success' => true, 'message' => 'Produit ajouté au panier.']);
                    } else {
                        // Utilisateur non trouvé
                        echo json_encode(['success' => false, 'message' => 'Utilisateur non trouvé.']);
                    }
                } else {
                    // Si l'utilisateur n'est pas connecté, on gère le panier en session
                    if (!isset($_SESSION['panier'])) {
                        $_SESSION['panier'] = [];
                    }

                    // Vérification de l'existence du produit dans le panier de session et mise à jour de la quantité
                    $produit_trouve = false;
                    foreach ($_SESSION['panier'] as &$item) {
                        if ($item['produit_id'] == $produit_id) {
                            $item['quantite'] += $quantite;
                            $produit_trouve = true;
                            break;
                        }
                    }

                    // Si aucun produit n'est trouvé dans le panier de session, on l'ajoute
                    if (!$produit_trouve) {
                        $_SESSION['panier'][] = [
                            'produit_id' => $produit_id,
                            'nom_produit' => $nom_produit,
                            'quantite' => $quantite,
                            'prix' => $prix
                        ];
                    }

                    // Message de succès
                    echo json_encode(['success' => true, 'message' => 'Produit ajouté au panier.']);
                }
            } else {
                // Produit non trouvé
                echo json_encode(['success' => false, 'message' => 'Produit non trouvé.']);
            }
        } catch (PDOException $e) {
            // Gestion des erreurs
            echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'ajout au panier : ' . $e->getMessage()]);
        }
    } else {
        // Quantité invalide
        echo json_encode(['success' => false, 'message' => 'La quantité doit être supérieure à zéro.']);
    }
} else {
    // Données invalides
    echo json_encode(['success' => false, 'message' => 'Données de produit manquantes ou invalides.']);
}
