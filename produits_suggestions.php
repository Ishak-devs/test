<?php
session_start();
include('db.php');

// Désactivation du cache pour éviter les résultats périmés
header('Content-Type: text/plain; charset=utf-8');
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

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
            $cleanedProductName = str_replace('"', '', $product['nom_produit']);
            $cleanedProductName = htmlspecialchars($cleanedProductName, ENT_QUOTES, 'UTF-8');

            // Génération des options
            echo '<option value="' . $cleanedProductName . '" data-id="' . htmlspecialchars($product['produit_id'], ENT_QUOTES, 'UTF-8') . '">' . $cleanedProductName . '</option>';
        }
    } else {
        echo '<option value="">Aucun produit trouvé.</option>';
    }
} else {
    echo '<option value="">Termes de recherche manquants.</option>';
}
?>
<footer>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</footer>