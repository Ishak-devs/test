<?php
session_start();
include('db.php');
require_once('header.php');
?>

<head>
    <link rel="stylesheet" href="css/produitdetails.css">
</head>

<body>
    <?php
    // Si une erreur survient, on stocke le message dans une variable
    if (isset($_SESSION['erreur_panier'])) {
        echo '<p style="color: red;" class="error-message">' . htmlspecialchars($_SESSION['erreur_panier']) . '</p>';
        unset($_SESSION['erreur_panier']);
    }

    // Vérifier si un produit est sélectionné avec la méthode GET
    if (isset($_GET['produit_id'])) {
        // S'assurer que l'ID est un int 
        $produit_id = intval($_GET['produit_id']);

        // Requête SQL pour récupérer les infos du produit avec une jointure à la table fabricants
        $sql = "SELECT p.*, f.nom_fabricant 
                FROM produits p
                JOIN fabricants f ON p.fabricant_id = f.fabricant_id 
                WHERE p.produit_id = :produit_id LIMIT 1";

        // Création de stmt afin de préparer notre requête
        $stmt = $pdo->prepare($sql);
        // Exécution de stmt avec la méthode execute
        $stmt->execute(['produit_id' => $produit_id]);

        // Création de tableau associatif pour stocker les infos dans $produit
        $produit = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si un produit est trouvé, on affiche les infos
        if ($produit) {
            // Affichage des infos avec htmlspecialchars pour éviter les failles XSS
            echo '<div class="container-produit">';
            echo '<h2>' . htmlspecialchars($produit['nom_produit']) . '</h2>';
            echo '<p>' . htmlspecialchars($produit['description']) . '</p>';
            echo '<p class="prix">' . htmlspecialchars($produit['prix']) . ' €</p>';
            echo '<p class="quantite-stock">Quantité en stock : ' . htmlspecialchars($produit['quantite_stock']) . '</p>';
            echo '<p class="fabricant">Fabricant : ' . htmlspecialchars($produit['nom_fabricant']) . '</p>';

            // Affichage des images
            $image_path = 'images/produits/' . $produit['produit_id'] . '/';
            // Compteur d'images
            $image_count = 0;

            echo '<div class="slider-container">';
            echo '<div class="image-track">';

            // Boucle pour afficher les images
            for ($i = 1; $i <= 5; $i++) {
                $image_file = $image_path . $produit['produit_id'] . '_' . $i . '.jpeg';
                if (file_exists($image_file)) {
                    echo '<div class="product-image">';
                    echo '<img src="' . $image_file . '" alt="Image du produit ' . htmlspecialchars($produit['nom_produit']) . '">';
                    echo '</div>';
                    $image_count++;
                }
            }

            echo '</div>';

            // Affichage des boutons slide des images
            if ($image_count > 1) {
                echo '<button class="slider-button prev-button" onclick="moveSlide(-1)">&#10094;</button>';
                echo '<button class="slider-button next-button" onclick="moveSlide(1)">&#10095;</button>';
            }

            echo '</div>';

            // Formulaire d'ajout d'un produit au panier avec AJAX
            echo '<form id="ajout-panier-form">';
            echo '<input type="hidden" name="produit_id" value="' . $produit['produit_id'] . '">';
            echo '<label for="quantite">Quantité :</label>';
            echo '<input type="number" id="quantite" name="quantite" value="1" min="1">'; // Retirer l'attribut max pour l'exemple

            // Zone de message pour afficher l'erreur si nécessaire
            echo '<p id="message" style="color: red;"></p>';

            echo '<button type="submit">Ajouter au panier</button>';
            echo '</form>';

            echo '</div>';
        } else {
            echo '<p>Produit non trouvé.</p>';
        }
    } else {
        echo '<p>Aucun produit sélectionné.</p>';
    }

    require_once('footer.html');
    ?>

    <script>
        // Fonction pour les images du slider
        let position = 0;
        const track = document.querySelector('.image-track');
        // Récupération des images
        const images = document.querySelectorAll('.product-image');
        const totalImages = images.length;

        // Fonction pour le déplacement des images
        function moveSlide(direction) {
            // Gestion de la direction du slider
            position += direction;

            // Si on arrive à la fin, on revient au début des images
            if (position < 0) {
                position = totalImages - 1;
            } else if (position >= totalImages) {
                position = 0;
            }

            // Déplacement des images
            const offset = position * -100;
            track.style.transform = `translateX(${offset}%)`;
        }

        document.getElementById('ajout-panier-form').addEventListener('submit', function(event) {
            event.preventDefault(); // Empêche la soumission classique du formulaire

            // Récupérer la quantité saisie par l'utilisateur
            const quantiteDemandee = document.getElementById('quantite').value;
            const quantiteStock = <?php echo $produit['quantite_stock']; ?>;

            // Vérification si la quantité demandée est inférieure à 1 ou supérieure au stock
            if (quantiteDemandee < 1) {
                document.getElementById('message').innerText = 'Veuillez saisir une quantité supérieure ou égale à 1.';
            } else if (quantiteDemandee > quantiteStock) {
                document.getElementById('message').innerText = 'Quantité non disponible'
            } else {
                // Si la quantité est valide, on envoie le formulaire via AJAX
                var formData = new FormData(this);

                fetch('ajoutpanier.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json()) // Attendre la réponse du serveur et la parser en JSON
                    .then(data => {
                        // Afficher la réponse (confirmation ou erreur) dans la div message
                        if (data.success) {
                            document.getElementById('message').innerText = data.message;
                        } else {
                            document.getElementById('message').innerText = "Une erreur s'est produite. Veuillez réessayer.";
                        }
                    })
                    .catch(error => {
                        // Gérer les erreurs
                        document.getElementById('message').innerText = "Une erreur s'est produite. Veuillez réessayer.";
                    });
            }
        });
    </script>
</body>