<?php
session_start(); // Démarrer la session
require_once('db.php');

// Traitement de la connexion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['connexion'])) {
    $email = $_POST['email_connexion'];
    $mot_de_passe = $_POST['mot_de_passe_connexion'];

    // Vérification des identifiants
    $stmt = $pdo->prepare('SELECT * FROM clients WHERE email = :email');
    $stmt->execute(['email' => $email]);
    $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($utilisateur && password_verify($mot_de_passe, $utilisateur['mot_de_passe'])) {
        // Vérification si l'email est vérifié
        if ($utilisateur['email_verified'] == 1) {
            // Démarrer la session et stocker des informations
            $_SESSION['utilisateur_id'] = $utilisateur['utilisateur_id'];
            $_SESSION['prenom'] = $utilisateur['prenom'];

            // Transférer le panier de la session à la table 'paniers' et 'details_panier'
            if (isset($_SESSION['panier']) && !empty($_SESSION['panier'])) {
                $utilisateur_id = $utilisateur['utilisateur_id'];
                $panier = $_SESSION['panier'];

                // Vérifier si un panier existe pour l'utilisateur
                $stmt = $pdo->prepare('SELECT panier_id FROM paniers WHERE utilisateur_id = :utilisateur_id');
                $stmt->execute(['utilisateur_id' => $utilisateur_id]);
                $panierExist = $stmt->fetch(PDO::FETCH_ASSOC);

                // Si aucun panier n'existe, en créer un
                if (!$panierExist) {
                    try {
                        $stmt = $pdo->prepare('INSERT INTO paniers (utilisateur_id) VALUES (:utilisateur_id)');
                        $stmt->execute(['utilisateur_id' => $utilisateur_id]);
                        $panier_id = $pdo->lastInsertId(); // Récupérer l'ID du nouveau panier
                    } catch (PDOException $e) {
                        echo "Erreur lors de l'insertion dans paniers: " . $e->getMessage();
                    }
                } else {
                    $panier_id = $panierExist['panier_id'];
                }

                // Ajout des produits dans details_panier
                foreach ($panier as $item) {
                    $produit_id = $item['produit_id'];
                    $quantite = $item['quantite'];
                    $nom_produit = $item['nom_produit'];
                    $prix = $item['prix'];

                    // Vérifier si le produit existe déjà dans details_panier pour cet utilisateur
                    $sql = "SELECT quantite FROM details_panier WHERE panier_id = :panier_id AND produit_id = :produit_id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(['panier_id' => $panier_id, 'produit_id' => $produit_id]);
                    $existingProduct = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($existingProduct) {
                        // Si le produit existe déjà, mettre à jour la quantité
                        $sql = "UPDATE details_panier SET quantite = quantite + :quantite WHERE panier_id = :panier_id AND produit_id = :produit_id";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute(['quantite' => $quantite, 'panier_id' => $panier_id, 'produit_id' => $produit_id]);
                    } else {
                        // Sinon, insérer le produit
                        $sql = "INSERT INTO details_panier (panier_id, produit_id, quantite, utilisateur_id, prix) 
                                VALUES (:panier_id, :produit_id, :quantite, :utilisateur_id, :prix)";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([
                            'panier_id' => $panier_id,
                            'produit_id' => $produit_id,
                            'quantite' => $quantite,
                            'utilisateur_id' => $utilisateur_id,
                            'prix' => $prix
                        ]);
                    }
                }
            }

            // Redirection vers pageperso.php après connexion réussie
            header("Location: pageperso.php");
            exit;
        } else {
            // E-mail non vérifié
            $_SESSION['message'] = "Veuillez vérifier votre adresse e-mail avant de vous connecter.";
            header("Location: connexion.php");
            exit;
        }
    } else {
        // Identifiants incorrects
        $_SESSION['message'] = "Email ou mot de passe incorrect.";
        header("Location: connexion.php");
        exit;
    }
}
