<?php
session_start(); // Démarrer la session
require_once('header.php');
include('db.php');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: connexion.php");
    exit;
}
?>

<head>
    <link rel="stylesheet" href="css/pageperso.css">
</head>

<?php
// Récupérer les informations de l'utilisateur connecté
$stmt = $pdo->prepare('SELECT prenom, nom, email, numero_telephone FROM clients WHERE utilisateur_id = :utilisateur_id');
$stmt->execute(['utilisateur_id' => $_SESSION['utilisateur_id']]);
$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

if ($utilisateur) {
    echo '<div class="account-container">';
    echo '<h1>Mon Compte</h1>';

    // Menu burger


    echo '<div class="account-section">';
    echo '<p><strong>Prénom :</strong> ' . htmlspecialchars($utilisateur['prenom']) . '</p>';
    echo '<p><strong>Nom :</strong> ' . htmlspecialchars($utilisateur['nom']) . '</p>';
    echo '<p><strong>Email :</strong> ' . htmlspecialchars($utilisateur['email']) . '</p>';
    echo '<p><strong>Téléphone :</strong> ' . htmlspecialchars($utilisateur['numero_telephone']) . '</p>';
    echo '</div>';

    echo '</div>';
} else {
    echo "Aucune information trouvée pour cet utilisateur.";
}
?>


<footer>
    <?php
    require_once('footer.html');
    ?>
</footer>