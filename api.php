<?php


header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json; charset=utf-8');

// Paramètres de connexion à la base de données
$dsn = 'mysql:host=localhost;dbname=ecom2425;charset=utf8mb4';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

// Requête SQL pour récupérer les tendances et produits
$sql = "
    SELECT t.tendance_id, t.nombre_ventes, t.date_ajout, 
           p.produit_id, p.nom_produit, p.description, p.prix, p.quantite_stock 
    FROM tendances t
    JOIN produits p ON t.produit_id = p.produit_id";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $tendances = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Vérifie si des résultats sont trouvés
    if ($tendances) {
        echo json_encode(['success' => true, 'data' => $tendances]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Aucune tendance trouvée.']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error fetching data: ' . $e->getMessage()]);
}

exit;
