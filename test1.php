<?php
// Ajouter les en-têtes CORS
header('Access-Control-Allow-Origin: *'); // Autorise toutes les origines (change * pour un domaine spécifique si besoin)
header('Access-Control-Allow-Methods: GET, POST, OPTIONS'); // Autorise les méthodes GET, POST et OPTIONS
header('Access-Control-Allow-Headers: Content-Type'); // Autorise les en-têtes spécifiques comme Content-Type

header('Content-Type: application/json; charset=utf-8');  // Retourner la réponse en JSON
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

$servername = "localhost";
$dbname = "ecom2425";
$dbusername = "root";
$dbpassword = "";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vérifie si 'search' est passé dans la requête GET
    $search = isset($_GET['search']) ? $_GET['search'] : '';

    // Prépare la requête avec une recherche LIKE, % avant et après pour la recherche partielle
    $stmt = $conn->prepare("SELECT nom_produit, typeAnimals FROM produits WHERE nom_produit LIKE :search");
    $stmt->execute(['search' => '%' . $search . '%']);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Retourne les résultats sous forme de JSON
    echo json_encode($results);

    // Générer les options pour le datalist
    foreach ($results as $product) {
        $cleanedProductName = htmlspecialchars($product['nom_produit'], ENT_QUOTES, 'UTF-8'); // Nettoie le nom du produit
        echo '<option value="' . $cleanedProductName . '" data-id="' . htmlspecialchars($product['typeAnimals'], ENT_QUOTES, 'UTF-8') . '"></option>';
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
