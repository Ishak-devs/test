<?php
session_start();
require 'db.php'; // Inclure le fichier de configuration avec les informations de connexion à la base de données

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php'); // Rediriger vers la page de connexion si non connecté
    exit();
}

// Connexion à la base de données
try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

// Récupérer la liste des produits
$query = "SELECT * FROM produits";
$stmt = $pdo->query($query);
$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer la liste des commandes
$queryCommandes = "SELECT * FROM commandes";
$stmtCommandes = $pdo->query($queryCommandes);
$commandes = $stmtCommandes->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les informations de l'administrateur connecté
$admin_id = $_SESSION['admin_id'];
$queryAdmin = "SELECT * FROM admins WHERE admin_id = :admin_id";
$stmtAdmin = $pdo->prepare($queryAdmin);
$stmtAdmin->execute(['admin_id' => $admin_id]);
$admin = $stmtAdmin->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Administrateur</title>
    <link rel="stylesheet" href="css/admins.css">
</head>

<body>
    <header>
        <h1>Bienvenue dans l'espace administrateur, <?php echo htmlspecialchars($admin['username']); ?></h1>
    </header>

    <main>

        <section>
            <h2>Liste des commandes</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID de commande</th>
                        <th>ID Utilisateur</th>
                        <th>Date de commande</th>
                        <th>Montant</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($commandes as $commande): ?>
                        <tr>
                            <form method="POST" action="modifier_commande.php">
                                <td><?php echo htmlspecialchars($commande['commande_id']); ?></td>
                                <td><?php echo htmlspecialchars($commande['utilisateur_id']); ?></td>
                                <td><?php echo htmlspecialchars($commande['date_commande']); ?></td>
                                <td><input type="number" name="montant" value="<?php echo htmlspecialchars($commande['montant']); ?>" required></td>
                                <td>
                                    <select name="statut" required>
                                        <option value="En attente" <?php if ($commande['statut'] == 'En attente') echo 'selected'; ?>>En attente</option>
                                        <option value="En cours" <?php if ($commande['statut'] == 'En cours') echo 'selected'; ?>>En cours</option>
                                        <option value="Effectué" <?php if ($commande['statut'] == 'Effectué') echo 'selected'; ?>>Effectué</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="hidden" name="commande_id" value="<?php echo htmlspecialchars($commande['commande_id']); ?>">
                                    <input type="submit" class="btn" value="Modifier">
                                    <a href="supprimer_commande.php?id=<?php echo htmlspecialchars($commande['commande_id']); ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette commande ?');">Supprimer</a>
                                </td>
                            </form>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
        <a href="deconnexion.php">Déconnexion</a>

    </main>

    <footer>

        &copy; 2024 INSTA
    </footer>
</body>

</html>