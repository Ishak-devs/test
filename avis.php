<?php
session_start(); // Démarrer la session
require_once('db.php');
require_once('header.php');

// Récupérer tous les avis pour chaque produit
$stmt = $pdo->prepare("
    SELECT a.avis_id, a.note, a.commentaire, a.date_avis, 
           c.prenom, c.nom, p.nom_produit
    FROM avis a
    JOIN clients c ON a.client_id = c.utilisateur_id
    JOIN produits p ON a.produit_id = p.produit_id
    ORDER BY a.date_avis DESC
");
$stmt->execute();
$avis = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/commandes.css"> <!-- Assurez-vous que ce fichier ne touche pas à l'en-tête -->
    <title>Tous les Avis</title>
    <link rel="stylesheet" href="css/avis.css">
</head>

<body>

    <h1 class="commande-title">Tous les Avis des Produits</h1>

    <?php if (count($avis) > 0): ?>
        <table class="avis-table">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Note</th>
                    <th>Commentaire</th>
                    <th>Client</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($avis as $avis_item): ?>
                    <tr>
                        <td class="avis-produit"><?php echo htmlspecialchars($avis_item['nom_produit']); ?></td>
                        <td class="avis-note">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <span class="star"><?php echo $i <= $avis_item['note'] ? '★' : '☆'; ?></span>
                            <?php endfor; ?>
                        </td>
                        <td class="avis-commentaire"><?php echo nl2br(htmlspecialchars($avis_item['commentaire'])); ?></td>
                        <td class="avis-client"><?php echo htmlspecialchars($avis_item['prenom']) . ' ' . htmlspecialchars($avis_item['nom']); ?></td>
                        <td class="avis-date"><?php echo htmlspecialchars($avis_item['date_avis']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucun avis trouvé.</p>
    <?php endif; ?>

    <a href="pageperso.php" class="retour-button">Retour à ma page personnelle</a>

</body>

</html>