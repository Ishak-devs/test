<?php
session_start();
ob_start(); // Démarre la mise en mémoire tampon de sortie

include('db.php');
require_once('header.php');

// Définir la locale en français
setlocale(LC_TIME, 'fr_FR.UTF-8', 'fr_FR', 'fr');

// Récupération de l'ID utilisateur
$userId = $_SESSION['utilisateur_id'];
if (!$userId) {
    header('Location: connexion.php');
    exit();
}

// Récupération des adresses de l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM adresses WHERE utilisateur_id = :id");
$stmt->execute(['id' => $userId]);
$adresses = $stmt->fetchAll();

$totalPrix = 0;
$totalPanier = 0;

// Calcul du total du panier
$stmt = $pdo->prepare("SELECT SUM(quantite * prix) as total FROM details_panier WHERE utilisateur_id = :id");
$stmt->execute(['id' => $userId]);
$totalPanier = $stmt->fetchColumn() ?: 0;

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['ajouter_adresse'])) {
        // Ajout d'une nouvelle adresse
        $ligne1 = $_POST['ligne1'];
        $ligne2 = $_POST['ligne2'] ?? null;
        $ville = $_POST['ville'];
        $code_postal = $_POST['code_postal'];

        try {
            $stmt = $pdo->prepare("INSERT INTO adresses (utilisateur_id, ligne1, ligne2, ville, code_postal, cree_le) VALUES (:utilisateur_id, :ligne1, :ligne2, :ville, :code_postal, NOW())");
            $stmt->execute([
                'utilisateur_id' => $userId,
                'ligne1' => $ligne1,
                'ligne2' => $ligne2,
                'ville' => $ville,
                'code_postal' => $code_postal,
            ]);
            // Redirection après l'ajout réussi
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } catch (PDOException $e) {
            echo "Erreur : " . htmlspecialchars($e->getMessage());
        }
    }

    if (isset($_POST['adresse_id'])) {
        $adresse_id = $_POST['adresse_id'];

        // Calcul du prix total
        $totalPrix = $totalPanier + $totalPrixLivraison;

        // Vérification des prix
        if ($totalPrix < 0) {
            $_SESSION['erreur'] = 'Le prix total ne peut pas être négatif.';
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }

        // Stocker l'adresse_id et le montant total dans la session
        $_SESSION['adresse_id'] = $adresse_id;
        $_SESSION['totalPrix'] = $totalPrix;

        // Redirection vers la page de paiement
        header("Location: paiement.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Passer Commande</title>
    <link rel="stylesheet" href="css/passercommande.css">
    <style>
        /* Masquer le formulaire d'ajout d'adresse par défaut */
        .ajouter-adresse-form {
            display: none;
        }

        /* Ajouter un petit style pour le lien */
        #ajouter-adresse-link {
            cursor: pointer;
            color: blue;
            text-decoration: underline;
        }

        /* Ajouter des marges et des espacements au formulaire d'ajout d'adresse */
        .ajouter-adresse-form input {
            margin-bottom: 10px;
            padding: 8px;
            width: 100%;
        }

        .ajouter-adresse-form button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        .ajouter-adresse-form button:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <div class="commande-container">
        <h1 class="commande-title">Passer Commande</h1>

        <?php if (isset($_SESSION['erreur'])): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['erreur']);
                                            unset($_SESSION['erreur']); ?></div>
        <?php endif; ?>

        <form class="commande-form" action="" method="post">

            <div class="form-group">
                <label for="adresse_id">Sélectionnez une adresse :</label>
                <select name="adresse_id" id="adresse_id" required>
                    <?php foreach ($adresses as $adresse): ?>
                        <option value="<?php echo $adresse['adresse_id']; ?>">
                            <?php echo htmlspecialchars($adresse['ligne1']) . ', ' . htmlspecialchars($adresse['ville']) . ' ' . htmlspecialchars($adresse['code_postal']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn">Confirmer la commande</button>
        </form>

        <!-- Lien cliquable pour afficher le formulaire d'ajout d'adresse -->
        <a href="#" id="ajouter-adresse-link" onclick="toggleForm()">Ajouter une nouvelle adresse</a>

        <!-- Formulaire d'ajout d'adresse (initialement caché) -->
        <div id="ajouter-adresse-form" class="ajouter-adresse-form">
            <h2 class="commande-subtitle">Ajouter une nouvelle adresse</h2>
            <form action="" method="post">
                <div class="form-group">
                    <label for="ligne1">Ligne 1 :</label>
                    <input type="text" name="ligne1" id="ligne1" required>
                </div>
                <div class="form-group">
                    <label for="ligne2">Ligne 2 :</label>
                    <input type="text" name="ligne2" id="ligne2">
                </div>
                <div class="form-group">
                    <label for="ville">Ville :</label>
                    <input type="text" name="ville" id="ville" required>
                </div>
                <div class="form-group">
                    <label for="code_postal">Code Postal :</label>
                    <input type="text" name="code_postal" id="code_postal" required>
                </div>
                <button type="submit" name="ajouter_adresse" class="btn">Ajouter Adresse</button>
            </form>
        </div>
    </div>

    <script>
        // Fonction JavaScript pour afficher/masquer le formulaire
        function toggleForm() {
            console.log("toggleForm called"); // Vérification si la fonction est appelée
            var form = document.getElementById('ajouter-adresse-form');
            if (form.style.display === 'none' || form.style.display === '') {
                form.style.display = 'block'; // Affiche le formulaire
                console.log("Formulaire affiché"); // Confirmation dans la console
            } else {
                form.style.display = 'none'; // Cache le formulaire
                console.log("Formulaire caché"); // Confirmation dans la console
            }
        }
    </script>
</body>

</html>

<script src="js/adresses.js"></script>
<?php
ob_end_flush(); // Libère le contenu de la mémoire tampon et l'envoie au navigateur
?>