<?php
// Vérifie si une session n'est pas déjà démarrée avant de la démarrer
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar avec PHP et CSS intégré</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body>

    <!-- Barre de navigation -->
    <div class="navbar">

        <!-- Liens de navigation à droite -->
        <div class="navbar-right">
            <?php if (!isset($_SESSION['firstname'])): ?>
                <!-- Si l'utilisateur n'est pas connecté, afficher Connexion -->
                <a href="connexion.php">Se connecter</a>
            <?php else: ?>
                <!-- Si l'utilisateur est connecté, afficher Déconnexion -->
                <span>Bienvenue, <?php echo htmlspecialchars($_SESSION['firstname']); ?></span>
                <form action="logout.php" method="POST" style="display:inline;">
                    <button type="submit">Se déconnecter</button>
                </form>
            <?php endif; ?>

            <form class="search-form" role="search">
                <datalist id="data-list"></datalist>
                <input class="nav-search" id="query" type="search" name="query" placeholder="Search" aria-label="Search">
                <button class="btn-search" type="submit">Search</button>
            </form>

            <a href="cart.php">Panier</a>

            <a href="store.php">Store Page</a>
        </div>
    </div>

    <script>
        // Lors de la saisie dans le champ de recherche
        $('input[name="query"]').on('input', function() {
            var searchInput = $(this).val(); // Récupère la valeur saisie
            var datalist = $('#data-list'); // Cible le datalist

            // Si le champ de recherche n'est pas vide
            if (searchInput.length > 0) {
                // Effectue la requête AJAX
                $.ajax({
                    url: 'test1.php',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        search: searchInput
                    },
                    success: function(response) {
                        // Vide le datalist avant d'ajouter de nouvelles options
                        datalist.empty();

                        // Ajoute les options au datalist
                        $.each(response, function(index, product) {
                            datalist.append(
                                $('<option>', {
                                    value: product.nom_produit // Remplace "typeAnimals" par le champ approprié
                                })
                            );
                        });
                    }
                });
            }
        });
    </script>

</body>

</html>