<?php
session_start();
require_once('db.php');


//stockage de l'id de l'utilisateur
$utilisateur_id = $_SESSION['utilisateur_id'];

if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: connexion.php");
    exit;
}
//requete sql pour inserer dans la table commandes
$stmt = $pdo->prepare("
    SELECT c.commande_id, c.date_commande, c.montant, c.statut, 
           GROUP_CONCAT(p.produit_id, ':', p.nom_produit SEPARATOR ', ') AS produits
    FROM commandes c
    JOIN details_commandes dc ON c.commande_id = dc.commande_id
    JOIN produits p ON dc.produit_id = p.produit_id
    WHERE c.utilisateur_id = :utilisateur_id
    GROUP BY c.commande_id
");
$stmt->execute(['utilisateur_id' => $utilisateur_id]);
$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

//si la requete est en post
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_avis'])) {
    $produit_id = $_POST['produit_id'];
    $note = $_POST['note'];
    $commentaire = isset($_POST['commentaire']) ? $_POST['commentaire'] : '';

    //requete our avoir les details de commandes
    $stmt = $pdo->prepare("SELECT * FROM details_commandes dc 
                           JOIN commandes c ON dc.commande_id = c.commande_id 
                           WHERE c.utilisateur_id = :utilisateur_id AND dc.produit_id = :produit_id");
    $stmt->execute(['utilisateur_id' => $utilisateur_id, 'produit_id' => $produit_id]);
    $commande = $stmt->fetch(PDO::FETCH_ASSOC);

    //insertion dans la table avis
    if ($commande) {
        $stmt = $pdo->prepare("
            INSERT INTO avis (client_id, produit_id, note, commentaire)
            VALUES (:client_id, :produit_id, :note, :commentaire)
        ");
        $stmt->execute([
            'client_id' => $utilisateur_id,
            'produit_id' => $produit_id,
            'note' => $note,
            'commentaire' => $commentaire
        ]);
        //message de succès
        $_SESSION['message'] = "Votre avis a été soumis avec succès !";
        header('Location: commandes.php');
        exit;
    } else {
        $_SESSION['error'] = "Vous ne pouvez pas noter un produit que vous n'avez pas acheté.";
    }
}
?>

<!DOCTYPE html>
<?php require_once('header.php'); ?>
<html lang="fr">
<!-- script html -->

<head>
    <!--caractère en utf 8 -->
    <meta charset="UTF-8">
    <!--liaisons du fichier css -->
    <link rel="stylesheet" href="css/commandes.css">
    <!-- responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Commandes</title>
</head>

<body>
    <!--titre  -->
    <h1 class="commande-title">Mes Commandes</h1>

    <?php if (isset($_SESSION['message'])): ?>
        <!--si un mesage de succès existe l'afficher -->
        <p class="success-message"><?php echo $_SESSION['message'];
                                    unset($_SESSION['message']); ?></p>
        <!-- si aucun message de succès existe erreur logic-->
    <?php elseif (isset($_SESSION['error'])): ?>
        <p class="error-message"><?php echo $_SESSION['error'];
                                    unset($_SESSION['error']); ?></p>
    <?php endif; ?>

    <?php if (count($commandes) > 0): ?>
        <!-- si une commande est trouvé afficher un tableau-->
        <table class="commandes-table">
            <thead>
                <tr>
                    <th class="bouttonth">ID Commande</th>
                    <th class="bouttonth">Date Commande</th>
                    <th class="bouttonth">Montant</th>
                    <th class="bouttonth">Statut</th>
                    <th class="bouttonth">Nom du Produit(s)</th>
                    <th class="bouttonth">Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- afficher boucle de la table commandes-->
                <?php foreach ($commandes as $commande): ?>
                    <tr>
                        <td class="commande-id"><?php echo htmlspecialchars($commande['commande_id']); ?></td>
                        <td class="commande-date"><?php echo htmlspecialchars($commande['date_commande']); ?></td>
                        <td class="commande-montant"><?php echo htmlspecialchars($commande['montant']); ?> €</td>
                        <td class="commande-statut"><?php echo htmlspecialchars($commande['statut']); ?></td>
                        <td class="commande-produits"><?php echo htmlspecialchars($commande['produits']); ?></td>
                        <td class="commande-actions">
                            <a href="voircommande/<?php echo htmlspecialchars($commande['commande_id']); ?>">Voir cette commande</a>
                        </td>
                    </tr>

                    <?php
                    //conditionner l'émission d'avis si la livraison est faite
                    if ($commande['statut'] === 'Livré'):
                        // consulter la chaine de caractère contenue dans produit
                        $produits = explode(', ', $commande['produits']);
                        foreach ($produits as $produit) {
                            list($produit_id, $produit_nom) = explode(':', $produit);
                    ?>
                            <tr>
                                <td colspan="6">
                                    <form action="commandes.php" method="POST">
                                        <!-- cacher l'id dans un boutton hidden-->
                                        <input type="hidden" name="produit_id" value="<?php echo htmlspecialchars($produit_id); ?>">
                                        <label for="note_<?php echo $produit_id; ?>">Notez ce produit :</label>
                                        <div class="stars">
                                            <!-- gestion des étoiles -->
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <input type="radio" id="star<?php echo $i; ?>_<?php echo $produit_id; ?>" name="note" value="<?php echo $i; ?>" required>
                                                <label for="star<?php echo $i; ?>_<?php echo $produit_id; ?>">★</label>
                                            <?php endfor; ?>
                                        </div>
                                        <textarea name="commentaire" placeholder="Laissez un commentaire (optionnel)"></textarea>
                                        <button type="submit" name="submit_avis">Soumettre votre avis</button>
                                    </form>
                                </td>
                            </tr>
                    <?php
                        } // End foreach produits
                    endif; // End if statut "Livré"
                    ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucune commande trouvée.</p>
    <?php endif; ?>

    <a href="pageperso.php" class="retour-button">Retour à ma page personnelle</a>

</body>

</style>

</html>