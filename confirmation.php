<?php
session_start();
include 'db.php';
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: login.php");
    exit();
}


if (!isset($_SESSION['adresse_id']) || !isset($_SESSION['totalPrix'])) {
    echo "Erreur : Paramètres manquants dans la session.";
    exit();
}


$adresse_id = $_SESSION['adresse_id'];
$montant_total = $_SESSION['totalPrix'];
$userId = $_SESSION['utilisateur_id'];


$stmt = $pdo->prepare("SELECT * FROM details_panier WHERE utilisateur_id = :utilisateur_id");
$stmt->execute(['utilisateur_id' => $userId]);
$produits = $stmt->fetchAll();

try {

    $pdo->beginTransaction();


    $stmt = $pdo->prepare("INSERT INTO commandes (utilisateur_id, adresse_id, montant, statut, date_commande) 
                           VALUES (:utilisateur_id, :adresse_id, :montant_total, 'En attente de livraison', NOW())");
    $stmt->execute([
        'utilisateur_id' => $userId,
        'adresse_id' => $adresse_id,
        'montant_total' => $montant_total
    ]);


    $commande_id = $pdo->lastInsertId();


    foreach ($produits as $produit) {
        if (isset($produit['produit_id'], $produit['quantite'], $produit['prix'])) {
            $stmt = $pdo->prepare("INSERT INTO details_commandes (commande_id, produit_id, quantite, prix, adresse_id) 
                                   VALUES (:commande_id, :produit_id, :quantite, :prix, :adresse_id)");
            $stmt->execute([
                'commande_id' => $commande_id,
                'produit_id' => $produit['produit_id'],
                'quantite' => $produit['quantite'],
                'prix' => $produit['prix'],
                'adresse_id' => $adresse_id,
            ]);


            $stmt = $pdo->prepare("UPDATE produits SET quantite_stock = quantite_stock - :quantite WHERE produit_id = :produit_id");
            $stmt->execute([
                'quantite' => $produit['quantite'],
                'produit_id' => $produit['produit_id']
            ]);
        } else {
            echo "Erreur : produit_id, quantité ou prix manquant.";
            exit();
        }
    }


    $stmt = $pdo->prepare("DELETE FROM details_panier WHERE utilisateur_id = :utilisateur_id");
    $stmt->execute(['utilisateur_id' => $userId]);

    $stmt = $pdo->prepare("DELETE FROM paniers WHERE utilisateur_id = :utilisateur_id");
    $stmt->execute(['utilisateur_id' => $userId]);


    $pdo->commit();


    $stmt = $pdo->prepare("SELECT email, prenom, nom FROM clients WHERE utilisateur_id = :utilisateur_id");
    $stmt->execute(['utilisateur_id' => $userId]);
    $utilisateur = $stmt->fetch();
    $email = $utilisateur['email'];
    $prenom = $utilisateur['prenom'];
    $nom = $utilisateur['nom'];


    $stmt = $pdo->prepare("SELECT * FROM adresses WHERE adresse_id = :adresse_id");
    $stmt->execute(['adresse_id' => $adresse_id]);
    $adresse = $stmt->fetch();
    $adresse_livraison = $adresse['adresse'] . ', ' . $adresse['ville'] . ' ' . $adresse['code_postal'];


    $mail = new PHPMailer(true);
    try {

        $mail->isSMTP();
        $mail->Host = 'smtp.mail.yahoo.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'kouicicontact@yahoo.com';
        $mail->Password = 'ndvmyqlrsnmeecxw';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;


        $mail->setFrom('kouicicontact@yahoo.com', 'ecom INSTA');
        $mail->addAddress($email, htmlspecialchars("$prenom $nom"));


        $mail->isHTML(true);
        $mail->Subject = 'Confirmation de votre commande';
        $mail->Body    = "<h1>Merci pour votre commande</h1>
                          <p>Votre commande a été reçue avec succès. Voici les détails :</p>
                          <p><strong>Montant total :</strong> $montant_total €</p>
                          <p><strong>Adresse de livraison :</strong> $adresse_livraison</p>
                          <p>Nous vous tiendrons informé du statut de la livraison.</p>";


        $mail->send();
    } catch (Exception $e) {
        echo "L'email de confirmation n'a pas pu être envoyé. Erreur: {$mail->ErrorInfo}";
    }


    header("Location: recapitulatifcommande.php?commande_id=$commande_id");
    exit();
} catch (Exception $e) {
    // En cas d'erreur, annuler la transaction
    $pdo->rollBack();
    echo "Erreur lors de la commande : " . $e->getMessage();
}
