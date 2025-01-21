<?php
session_start();
include('db.php');
require_once('header.php');

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: connexion.php");
    exit;
}

$utilisateur_id = $_SESSION['utilisateur_id']; // Récupérer l'ID utilisateur de la session
$message = isset($_GET['message']) ? $_GET['message'] : ""; // Récupérer le message de confirmation

// Récupérer les détails de la dernière commande
try {
    $sql = "
        SELECT c.commande_id, c.montant, c.date_commande, d.produit_id, d.quantite, d.prix, a.ligne1, a.ligne2, a.ville, a.code_postal
        FROM commandes c
        JOIN details_commandes d ON c.commande_id = d.commande_id
        JOIN adresses a ON c.adresse_id = a.adresse_id
        WHERE c.utilisateur_id = :utilisateur_id
        ORDER BY c.date_commande DESC LIMIT 1"; // Récupérer la dernière commande
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['utilisateur_id' => $utilisateur_id]);
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
    <title>Récapitulatif de la Commande</title>
    <link rel="stylesheet" href="css/recapitulatifcommande.css"> <!-- Lien vers votre CSS -->
</head>

<body>
    <div class="container">
        <?php if ($message): ?>
            <p class="confirmation-message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <h1 class="title">Récapitulatif de votre Commande</h1>

        <?php if ($commande): ?>
            <h2 class="section-title">Détails de la Commande #<?php echo htmlspecialchars($commande['commande_id']); ?></h2>
            <p><strong>Date de la commande:</strong> <?php echo htmlspecialchars($commande['date_commande']); ?></p>
            <p><strong>Adresse de livraison:</strong>
                <?php echo htmlspecialchars($commande['ligne1']); ?>
                <?php if (!empty($commande['ligne2'])) echo htmlspecialchars($commande['ligne2']) . "<br>"; ?>
            </p>
            <h3 class="section-title">Produits Commandés:</h3>
            <ul class="product-list">
                <li class="product-item">
                    <strong>Nom du produit:</strong> <?php echo htmlspecialchars($commande['produit_id']); ?><br>
                    <strong>Quantité:</strong> <?php echo htmlspecialchars($commande['quantite']); ?><br>
                    <strong>Prix des articles:</strong> <?php echo htmlspecialchars($commande['prix']); ?> €<br>
                </li>
            </ul>
            <h3 class="section-title">Montant Total: <?php echo htmlspecialchars($commande['montant']); ?> €</h3>
        <?php else: ?>
            <p>Aucune commande trouvée.</p>
        <?php endif; ?>

        <a class="return-link" href="boutique.php">Retour à la Boutique</a>
    </div>
    <?php require_once('footer.html'); ?>
</body>

</html>