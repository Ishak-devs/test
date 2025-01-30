<?php
session_start();
include('db.php');

// Désactivation du cache pour éviter les résultats périmés
if (isset($_GET['search_term'])) {
    $searchTerm = '%' . $_GET['search_term'] . '%';

    // Utilisation de COLLATE pour ignorer les accents
    $sql = "SELECT produit_id, nom_produit 
            FROM produits 
            WHERE nom_produit LIKE :search_term COLLATE utf8mb4_general_ci 
            LIMIT 10";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['search_term' => $searchTerm]);

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($results) {
        foreach ($results as $product) {
            // Nettoyage du nom du produit pour éviter les guillemets indésirables
            $cleanedProductName = $product['nom_produit'];

            // Génération des options
            echo '<option value="' . $cleanedProductName . htmlspecialchars($product['produit_id'], ENT_QUOTES, 'UTF-8') . '">' . $cleanedProductName . '</option>';
        }
    } else {
        echo '<option value="">Aucun produit trouvé.</option>';
    }
} else {
    echo '<option value="">Termes de recherche manquants.</option>';
}
