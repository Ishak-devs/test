<?php
// Démarrer la session (si nécessaire)
session_start();

// Inclure le fichier de connexion à la base de données
require_once('db.php');

try {
    // Préparer et exécuter la requête pour récupérer les données de la table categories
    $stmt = $pdo->prepare("SELECT * FROM categories");
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Nom du fichier où les données seront écrites
    $filename = 'categories.csv';

    // Ouvrir un fichier en mode écriture
    $file = fopen($filename, 'w');

    // Écrire l'en-tête du fichier CSV
    fputcsv($file, ['categorie_id', 'nom_categorie']);

    // Écrire les données dans le fichier
    foreach ($categories as $categorie) {
        fputcsv($file, $categorie);
    }

    // Fermer le fichier
    fclose($file);

    // Message de confirmation
    echo "Les données ont été exportées avec succès dans '$filename'.";
} catch (PDOException $e) {
    echo "Erreur lors de la récupération des données : " . $e->getMessage();
}
