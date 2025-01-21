

<?php


session_start();



$dsn = 'mysql:host=localhost;dbname=ecommerce;charset=utf8mb4';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}
$utilisateur_id = $utilisateur['utilisateur_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $produit_id = trim($_POST['produit_id']) ?: null;
}
$panier_id = $pdo->lastInsertId();

if ($utilisateur_id) {

    $sql = "INSERT INTO paniers (utilisateur_id) VALUES (:utilisateur_id)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['utilisateur_id' => $utilisateur_id]);

    $sql = "
                INSERT INTO details_panier (panier_id, produit_id, nom_produit, prix, quantite, utilisateur_id)
                VALUES (:panier_id, :produit_id, :nom_produit, :prix, :quantite, :utilisateur_id)
                ON DUPLICATE KEY UPDATE quantite = quantite + :quantite";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'panier_id' => $panier_id,
        'produit_id' => $produit_id,
        'nom_produit' => $nom_produit,
        'prix' => $prix,
        'quantite' => $quantite,
        'utilisateur_id' => $utilisateur_id
    ]);


    exit();
}
