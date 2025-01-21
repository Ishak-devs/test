<?php

include('db.php');

// Démarrer la session
session_start();

require_once('header.php');
require_once('echocategorie.php');

// Vérifier si l'utilisateur est connecté
$utilisateurConnecte = isset($_SESSION['utilisateur_id']);

// Requête pour récupérer les catégories
$query = "SELECT * FROM categories";
$stmt = $pdo->query($query);
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Affichage des messages d'inscription
if (isset($_SESSION['message'])) {
    echo '<div class="alert alert-info" role="alert">' . htmlspecialchars($_SESSION['message']) . '</div>';
    unset($_SESSION['message']);
}
?>

<body>

    <head>
        <title>Acceuil</title>
    </head>
    <footer>
        <?php require_once('footer.html'); ?>
    </footer>
</body>