<?php

$dsn = 'mysql:host=localhost;dbname=ecom2425;charset=utf8mb4';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

session_start();
include('header.php');

// Affichage du message d'erreur, s'il existe
if (isset($_SESSION['erreur_panier'])) {
    echo '<p class="alert alert-danger">' . htmlspecialchars($_SESSION['erreur_panier']) . '</p>';
    unset($_SESSION['erreur_panier']); // Supprimer le message après l'affichage
}

// Requête pour récupérer les tendances avec les informations sur les produits
$sql = "
    SELECT t.tendance_id, t.nombre_ventes, t.date_ajout, 
           p.produit_id, p.nom_produit, p.description, p.prix, p.quantite_stock 
    FROM tendances t
    JOIN produits p ON t.produit_id = p.produit_id";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$tendances = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/boutique.css">
    <title>Tendances</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>

<body>

    <div class="container mt-4">



        <div class="row">
            <?php foreach ($tendances as $tendance): ?>
                <div class="col-md-3 mb-4"> <!-- 4 colonnes sur les écrans moyens -->
                    <div class="product-card">
                        <a href="produits_details.php?produit_id=<?php echo $tendance['produit_id']; ?>" class="product-title">
                            <img src="images/<?php echo $tendance['produit_id']; ?>.jpeg" alt="<?php echo htmlspecialchars($tendance['nom_produit']); ?>" class="product-image">

                            <div class="product-details">
                                <?php echo htmlspecialchars($tendance['nom_produit']); ?>
                                <div class="product-price"><?php echo htmlspecialchars($tendance['prix']); ?> €</div>
                                <div class="product-description"><?php echo htmlspecialchars($tendance['description']); ?></div>
                                <div class="stock-info" onclick="window.location.href='produits_details.php?produit_id=<?php echo $tendance['produit_id']; ?>';">
                                    Plus que <?php echo htmlspecialchars($tendance['quantite_stock']); ?> disponible
                        </a>
                    </div>
                </div>
        </div>
    </div>
<?php endforeach; ?>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-0dH1fepIEX6pHzc3B5QdA4Rjy5vxgAY4geF5nA9L1jPqIb70r6ByVD/5PbOmbFEr" crossorigin="anonymous"></script>
<footer><?php require_once('footer.html'); ?></footer>
</body>

</html>