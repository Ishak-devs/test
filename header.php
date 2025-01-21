<?php
$utilisateurConnecte = isset($_SESSION['utilisateur_id']) && isset($_SESSION['prenom']);
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-pzjw8f+ua7Kw1TIq0jGniDqzN9V0mp7erHKKK7jw8JAzXb6zE15j82le79Zp5gZo" crossorigin="anonymous"></script>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/header.css">

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Accueil</a>
            <a class="navbar-brand" href="boutique.php">Tendances</a>
            <a class="navbar-brand" href="avis.php">Avis client</a>
            <?php if (!$utilisateurConnecte): ?>
                <a class="navbar-brand" href="inscription.php">Inscription</a>
            <?php else: ?>
                <a class="navbar-brand" href="pageperso.php">Mon espace</a>
            <?php endif; ?>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <form class="form-inline d-flex my-2 my-lg-0" onsubmit="handleSubmit(event)">
                    <input list="products" name="product" id="product" class="form-control mr-2" placeholder="Rechercher un produit" style="width: 350px">
                    <datalist id="products"></datalist>
                    <button type="submit" class="btn btn-outline-success">Rechercher</button>
                </form>

                <div class="right-section">
                    <div class="bienvenue-text">
                        <?php if ($utilisateurConnecte): ?>
                            <span class="navbar-brand">Bienvenue, <?php echo htmlspecialchars($_SESSION['prenom']); ?></span>
                        <?php else: ?>
                            <a class="btn btn-outline-success" href="connexion.php">Connectez-vous</a>
                        <?php endif; ?>
                    </div>
                </div>

                <a class="navbar-brand" href="panier.php">
                    <img src="images/logo.jpeg" alt="Logo">
                </a>
                <div class="burger-menu" onclick="toggleMenu()">☰</div>
                <div class="menu" id="menu">
                    <?php if ($utilisateurConnecte): ?>
                        <a href="modifier_profil.php" class="btnperso">Modifier le profil</a>
                        <a href="commandes.php" class="btnperso">Voir mes commandes</a>
                        <a href="historique.php" class="btnperso">Historique des achats</a>
                        <a href="favoris.php" class="btnperso">Mes favoris</a>
                        <a href="parametres.php" class="btnperso">Paramètres du compte</a>
                        <a href="aide.php" class="btnperso">Aide et support</a>
                        <a href="#" onclick="confirmLogout(event)" class="btnperso">Se déconnecter</a>
                    <?php else: ?>
                        <a href="connexion.php" class="btnperso">Se connecter</a>
                        <a href="inscription.php" class="btnperso">S'inscrire</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <script>
        function toggleMenu() {
            const menu = document.getElementById('menu');
            menu.classList.toggle('active'); // Ajoute ou retire la classe active
            if (menu.classList.contains('active')) {
                menu.style.display = 'flex'; // Afficher le menu quand il est actif
            } else {
                setTimeout(() => {
                    menu.style.display = 'none'; // Masquer le menu après la transition
                }, 300); // Délai pour laisser le temps à la transition de finir
            }
        }

        function confirmLogout(event) {
            event.preventDefault(); // Empêche le lien de suivre son href
            const confirmation = confirm("Êtes-vous sûr de vouloir vous déconnecter ?");
            if (confirmation) {
                window.location.href = 'deconnexion.php';
            }
        }
    </script>
</body>
<script src="js/header.js" defer></script>

</html>