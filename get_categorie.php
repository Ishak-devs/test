<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecom2425";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

// Requête SQL pour récupérer les produits
$sql = "SELECT *FROM produits";
$result = $conn->query($sql);

$products = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

$conn->close();

// Retourner les produits en JSON
header('Content-Type: application/json');
echo json_encode($products);
