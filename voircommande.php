<?php
session_start(); // Démarrer la session
require_once('db.php');
require_once('header.php');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: connexion.php");
    exit;
}

// Vérifier si l'ID de commande est passé en paramètre et est valide
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) { // Modifié ici
    echo "ID de commande invalide.";
    exit;
}

$commande_id = (int)$_GET['id']; // Modifié ici
$utilisateur_id = $_SESSION['utilisateur_id'];

// Récupérer les détails de la commande
try {
    $sql = "
        SELECT c.commande_id, c.date_commande, c.montant, c.statut, 
               d.produit_id, d.quantite, d.prix, 
               GROUP_CONCAT(p.nom_produit SEPARATOR ', ') AS noms_produits
        FROM commandes c
        JOIN details_commandes d ON c.commande_id = d.commande_id
        JOIN produits p ON d.produit_id = p.produit_id
        WHERE c.commande_id = :commande_id AND c.utilisateur_id = :utilisateur_id
        GROUP BY c.commande_id
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['commande_id' => $commande_id, 'utilisateur_id' => $utilisateur_id]);
    $commande = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur lors de la récupération de la commande: " . htmlspecialchars($e->getMessage());
    exit;
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/voircommande.css"> <!-- Lien vers votre CSS -->
    <title>Détails de la Commande</title>
</head>

<body>

    <h1 class="page-title">Détails de la Commande #<?php echo htmlspecialchars($commande['commande_id']); ?></h1>

    <div class="order-details">
        <p class="order-info"><strong>Date de la commande:</strong> <?php echo htmlspecialchars($commande['date_commande']); ?></p>
        <p class="order-info"><strong>Montant Total:</strong> <?php echo htmlspecialchars($commande['montant']); ?> €</p>
        <p class="order-info"><strong>Statut de la commande:</strong> <?php echo htmlspecialchars($commande['statut']); ?></p>

        <h3 class="order-section-title">Produits Commandés:</h3>
        <ul class="product-list">
            <li class="product-item">
                <strong>Produits:</strong> <?php echo htmlspecialchars($commande['noms_produits']); ?><br>
                <strong>Quantité:</strong> <?php echo htmlspecialchars($commande['quantite']); ?><br>
                <strong>Montant de la commande:</strong> <?php echo htmlspecialchars($commande['prix']); ?> €
            </li>
        </ul>
        <a href="commandes.php" class="return-button">Retour à Mes Commandes</a>
    </div>

    <?php require_once('footer.html'); ?>

</body>

</html>