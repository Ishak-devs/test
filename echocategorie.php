<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/categorie.css">
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Découvrez Nos Catégories</h1>

        <div class="row">
            <?php
            require_once('db.php');

            try {
                $stmt = $pdo->prepare("SELECT * FROM categories");
                $stmt->execute();
                $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo "Erreur lors de la récupération des données : " . $e->getMessage();
                exit;
            }

            if ($categories && count($categories) > 0) {
                foreach ($categories as $categorie) {
                    $categorie_id = htmlspecialchars($categorie['categorie_id']);
                    $nomImage = "images/$categorie_id.jpeg";
                    $categoryLink = "cat.php?id=" . $categorie_id;

                    echo "<div class='col-lg-3 col-md-4 col-sm-6 mb-4'>";
                    echo "<a href='$categoryLink' class='card-link'>";
                    echo "<div class='card h-100'>";

                    if (file_exists($nomImage)) {
                        echo "<img src='$nomImage' class='card-img-top category-image' alt='Image de la catégorie $categorie_id' />";
                    } else {
                        echo "<img src='images/default.jpg' class='card-img-top category-image' alt='Image par défaut' />";
                    }

                    echo "<div class='card-body text-center'>";
                    echo "<h5 class='card-title'>" . htmlspecialchars($categorie['nom_categorie']) . "</h5>";
                    echo "</div>";
                    echo "</div>";
                    echo "</a>";
                    echo "</div>";
                }
            } else {
                echo "<p class='no-categories'>Aucune catégorie trouvée.</p>";
            }
            ?>
        </div>
    </div>
</body>

</html>