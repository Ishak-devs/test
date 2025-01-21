<?php
require_once('db.php');
require_once('header.php');

// Vérifier si l'ID de la catégorie est passé dans l'URL
if (isset($_GET['id'])) {
    $categorieId = (int) $_GET['id'];

    // Requête pour récupérer le nom de la catégorie
    $queryCategorie = "SELECT nom_categorie FROM categories WHERE categorie_id = :categorie_id";
    $stmtCategorie = $pdo->prepare($queryCategorie);
    $stmtCategorie->bindParam(':categorie_id', $categorieId, PDO::PARAM_INT);
    $stmtCategorie->execute();
    $categorie = $stmtCategorie->fetch(PDO::FETCH_ASSOC);

    // Requête pour récupérer les produits de la catégorie concernée
    $queryProduits = "SELECT * FROM produits WHERE categorie_id = :categorie_id";
    $stmtProduits = $pdo->prepare($queryProduits);
    $stmtProduits->bindParam(':categorie_id', $categorieId, PDO::PARAM_INT);
    $stmtProduits->execute();
    $produits = $stmtProduits->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Si aucune catégorie n'est sélectionnée, rediriger ou afficher un message
    header("Location: index.php"); // Rediriger vers une autre page si nécessaire
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($categorie['nom_categorie']) ? htmlspecialchars($categorie['nom_categorie']) : 'Produits par Catégorie' ?></title>
    <link rel="stylesheet" href="css/boutique.css">
</head>

<body>
    <div class="container mt-4">
        <?php if ($produits && count($produits) > 0): ?>
            <div class="row">
                <?php foreach ($produits as $produit): ?>
                    <?php

                    $produit_id = htmlspecialchars($produit['produit_id']);
                    // Construire le chemin de l'image
                    $imagePath = 'images/produits/' . $produit_id . '.jpeg';

                    ?>
                    <div class="col-md-3 mb-3">
                        <div class="product-card" onclick="window.location.href='produits_details.php?produit_id=<?= $produit_id ?>'">
                            <!-- Afficher l'image du produit -->
                            <img src="<?= $imageToDisplay ?>" class="product-image" alt="Image du produit">
                            <div class="product-details">
                                <h5 class="product-title"><?= htmlspecialchars($produit['nom_produit']) ?></h5>
                                <p class="product-description"><?= htmlspecialchars($produit['description']) ?></p>
                                <p class="product-price"><?= number_format($produit['prix'], 2) ?> €</p>
                                <p class="stock-info">
                                <p class='textstock'>Plus que <?= htmlspecialchars($produit['quantite_stock']) ?> disponible</p>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="error-message">Aucun produit trouvé pour cette catégorie.</p>
        <?php endif; ?>
    </div>
</body>

</html>