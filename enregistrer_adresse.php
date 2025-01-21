<?php
session_start(); // Démarre la session

// Vérifiez si la requête est une requête POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données envoyées
    try {
        // Les données JSON sont généralement envoyées dans le corps de la requête
        $data = json_decode(file_get_contents('php://input'), true);

        // Vérifiez si l'ID de l'adresse a été envoyé
        if (isset($data['adresse_id'])) {
            // Enregistrez l'ID de l'adresse dans la session
            $_SESSION['adresse_id'] = $data['adresse_id'];

            // Retourner une réponse JSON
            echo json_encode(['success' => true]);
        } else {
            // Si l'ID n'est pas fourni, retourner une erreur
            echo json_encode(['success' => false, 'message' => 'ID de l\'adresse non fourni.']);
        }
    } catch (Exception $e) {
        // Retourner une erreur si une exception se produit
        echo json_encode(['success' => false, 'message' => 'Erreur inattendue : ' . htmlspecialchars($e->getMessage())]);
    }
} else {
    // Retourner une erreur si la méthode n'est pas autorisée
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
}
