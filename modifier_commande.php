<?php
session_start();
require 'db.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php'); // Rediriger vers la page de connexion si non connecté
    exit();
}

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $montant = $_POST['montant'];
    $statut = $_POST['statut'];
    $commande_id = $_POST['commande_id'];

    // Vérifier que le montant est un nombre et qu'il n'est pas négatif
    if (is_numeric($montant) && $montant >= 0) {
        // Mettre à jour la commande dans la base de données
        $query = "UPDATE commandes SET montant = :montant, statut = :statut WHERE commande_id = :commande_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'montant' => $montant,
            'statut' => $statut,
            'commande_id' => $commande_id
        ]);

        // Rediriger vers la page administrateur après la mise à jour
        header('Location: admin_espace.php');
        exit();
    } else {
        // Gestion de l'erreur si le montant est invalide
        echo "Veuillez entrer un montant valide et non négatif.";
    }
}
