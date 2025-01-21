<?php
include 'db.php'; // Inclure votre fichier de connexion PDO
session_start(); // Démarrer la session

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur_id'])) {
    // Si l'utilisateur n'est pas connecté, rediriger vers la page de connexion
    header("Location: connexion.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifier si l'utilisateur veut ajouter une nouvelle adresse
    if (isset($_POST['ajouter_adresse'])) {
        // Récupérer les données du formulaire d'adresse
        $ligne1 = $_POST['ligne1'];
        $ligne2 = $_POST['ligne2'] ?? null; // Ligne 2 peut être vide
        $ville = $_POST['ville'];
        $code_postal = $_POST['code_postal'];

        // Insérer la nouvelle adresse dans la base de données
        $stmt = $pdo->prepare("INSERT INTO adresses (utilisateur_id, ligne1, ligne2, ville, code_postal, cree_le) VALUES (:utilisateur_id, :ligne1, :ligne2, :ville, :code_postal, NOW())");
        $stmt->execute([
            'utilisateur_id' => $userId,
            'ligne1' => $ligne1,
            'ligne2' => $ligne2,
            'ville' => $ville,
            'code_postal' => $code_postal,
        ]);

        // Optionnel : Rediriger pour éviter le double envoi du formulaire
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}
