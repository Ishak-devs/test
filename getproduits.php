<?php
header('Content-Type: application/json; charset=utf-8');
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

$servername = "localhost";
$dbname = "e_commerce_project";
$dbusername = "root";
$dbpassword = "";

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Récupérer la saisie de l'utilisateur
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Préparer la requête SQL
$stmt = $conn->prepare("SELECT produit_id, nom FROM produits WHERE nom LIKE :search LIMIT 10");
$stmt->execute(['search' => '%' . $search . '%']);

// Récupérer les résultats
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Retourner les résultats en format JSON
echo json_encode($results);
