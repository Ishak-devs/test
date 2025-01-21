<?php
session_start();
include('db.php');

// Données reçues au format JSON
header('Content-Type: application/json');

// Stockage des données reçues dans un tableau
$response = [
    'success' => false, // Champ success ajouté pour indiquer si l'opération a réussi
    'totalPanier' => 0,  // Total du panier
    'sousTotal' => 0     // Sous-total de l'article
];

// Si la requête reçue est de type POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $produit_id = $_POST['produit_id'];
    $quantite = intval($_POST['quantite']);

    // Si la quantité est supérieure ou égale à 0
    if ($quantite >= 0) {
        try {
            // Récupérer la quantité disponible en stock et le prix du produit
            $stmt = $pdo->prepare("SELECT quantite_stock, prix FROM produits WHERE produit_id = :produit_id");
            $stmt->execute(['produit_id' => $produit_id]);
            $produit = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($produit !== false) {
                $quantite_disponible = $produit['quantite_stock'];
                $prix = $produit['prix'];

                // Vérification si la quantité demandée est disponible
                if ($quantite <= $quantite_disponible) {
                    // Traitement selon si l'utilisateur est connecté ou non
                    if (isset($_SESSION['utilisateur_id'])) {
                        $utilisateur_id = $_SESSION['utilisateur_id'];

                        // Si la quantité est zéro, on supprime le produit du panier
                        if ($quantite == 0) {
                            $stmt = $pdo->prepare("DELETE FROM details_panier WHERE produit_id = :produit_id AND utilisateur_id = :utilisateur_id");
                            $stmt->execute(['produit_id' => $produit_id, 'utilisateur_id' => $utilisateur_id]);
                        } else {
                            // Mise à jour de la quantité du produit dans la base de données
                            $stmt = $pdo->prepare("UPDATE details_panier SET quantite = :quantite WHERE produit_id = :produit_id AND utilisateur_id = :utilisateur_id");
                            $stmt->execute(['quantite' => $quantite, 'produit_id' => $produit_id, 'utilisateur_id' => $utilisateur_id]);
                        }

                        // Recalculer le total du panier
                        $totalPanier = 0;
                        $stmt = $pdo->prepare("SELECT dp.quantite, p.prix FROM details_panier dp JOIN produits p ON dp.produit_id = p.produit_id WHERE dp.utilisateur_id = :utilisateur_id");
                        $stmt->execute(['utilisateur_id' => $utilisateur_id]);
                        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($items as $item) {
                            $totalPanier += $item['quantite'] * $item['prix'];
                        }
                    } else {
                        // Mise à jour de la session si l'utilisateur n'est pas connecté
                        if (isset($_SESSION['panier'])) {
                            foreach ($_SESSION['panier'] as $key => &$item) {
                                if ($item['produit_id'] == $produit_id) {
                                    // Si la quantité est zéro, on supprime le produit du panier
                                    if ($quantite == 0) {
                                        unset($_SESSION['panier'][$key]);
                                    } else {
                                        $item['quantite'] = $quantite;
                                    }
                                    break;
                                }
                            }
                        }

                        // Recalculer le total du panier dans la session
                        $totalPanier = 0;
                        foreach ($_SESSION['panier'] as $item) {
                            $totalPanier += $item['quantite'] * $item['prix'];
                        }
                    }

                    // Réponse avec succès
                    $response['success'] = true;
                    $response['totalPanier'] = number_format($totalPanier, 2);
                    $response['sousTotal'] = number_format($quantite * $prix, 2);
                }
            }
        } catch (PDOException $exception) {
            // L'exception est capturée sans inclure de message client spécifique
            $response['success'] = false;
        }
    }
}

// Retourner la réponse JSON
echo json_encode($response);
