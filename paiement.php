<?php
session_start();
ob_start(); // Démarre la mise en mémoire tampon de sortie

include('db.php');
require_once('header.php');

// Vérifier si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $name = $_POST['name'];
    $card_number = $_POST['card-number'];
    $expiry = $_POST['expiry'];
    $cvv = $_POST['cvv'];

    // Après le traitement, rediriger vers la page de confirmation
    header("Location: confirmation.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire de Paiement</title>
    <link rel="stylesheet" href="css/paiement.css">
</head>

<body>
    <div class="payment-container">
        <div class="payment-card">
            <h2>Payer Maintenant</h2>
            <!-- Formulaire qui va soumettre avec la méthode POST -->
            <form action="" method="POST" id="paiement-form"> <!-- Ajout d'un id au formulaire -->
                <div class="form-group">
                    <label for="name">Nom sur la carte</label>
                    <input type="text" id="name" name="name" placeholder="John Doe" required>
                    <span id="name-error" class="error-message"></span> <!-- Message d'erreur pour le nom -->
                </div>
                <div class="form-group">
                    <label for="card-number">Numéro de carte</label>
                    <input type="text" id="card-number" name="card-number" placeholder="1234 5678 9876 5432" required>
                    <span id="card-number-error" class="error-message"></span> <!-- Message d'erreur pour le numéro de carte -->
                </div>
                <div class="form-group">
                    <label for="expiry">Date d'expiration</label>
                    <input type="text" id="expiry" name="expiry" placeholder="MM/AA" required>
                    <span id="expiry-error" class="error-message"></span> <!-- Message d'erreur pour la date d'expiration -->
                </div>
                <div class="form-group">
                    <label for="cvv">Code de sécurité (CVV)</label>
                    <input type="text" id="cvv" name="cvv" placeholder="123" required>
                    <span id="cvv-error" class="error-message"></span> <!-- Message d'erreur pour le CVV -->
                </div>
                <div class="form-group">
                    <button type="submit" class="btn-submit">Payer</button>
                </div>
            </form>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('paiement-form'); // Cibler le formulaire via son id
            const nameInput = document.getElementById('name');
            const cardNumberInput = document.getElementById('card-number');
            const expiryInput = document.getElementById('expiry');
            const cvvInput = document.getElementById('cvv');

            const nameError = document.getElementById('name-error');
            const cardNumberError = document.getElementById('card-number-error');
            const expiryError = document.getElementById('expiry-error');
            const cvvError = document.getElementById('cvv-error');

            // Fonction pour valider le numéro de carte (16 chiffres, séparés par des espaces)
            function validateCardNumber(cardNumber) {
                const regex = /^[0-9]{4}\s[0-9]{4}\s[0-9]{4}\s[0-9]{4}$/; // Format: XXXX XXXX XXXX XXXX
                return regex.test(cardNumber);
            }

            // Fonction pour valider la date d'expiration (MM/AA)
            function validateExpiry(expiry) {
                const regex = /^(0[1-9]|1[0-2])\/\d{2}$/; // Format: MM/AA, ex: 12/25
                return regex.test(expiry);
            }

            // Fonction pour valider le CVV (3 chiffres)
            function validateCVV(cvv) {
                const regex = /^[0-9]{3}$/; // 3 chiffres
                return regex.test(cvv);
            }

            // Fonction pour afficher les erreurs
            function displayError(element, message) {
                element.textContent = message;
                element.style.display = 'block';
            }

            // Fonction pour masquer les erreurs
            function hideError(element) {
                element.style.display = 'none';
            }

            // Écouteur d'événement sur la soumission du formulaire
            form.addEventListener('submit', function(event) {
                let valid = true;

                // Validation du nom (vérification que le champ n'est pas vide)
                if (nameInput.value.trim() === '') {
                    displayError(nameError, "Le nom sur la carte est requis.");
                    valid = false;
                } else {
                    hideError(nameError);
                }

                // Validation du numéro de carte
                if (!validateCardNumber(cardNumberInput.value)) {
                    displayError(cardNumberError, "Le numéro de carte est invalide. Veuillez entrer un numéro valide au format XXXX XXXX XXXX XXXX.");
                    valid = false;
                } else {
                    hideError(cardNumberError);
                }

                // Validation de la date d'expiration
                if (!validateExpiry(expiryInput.value)) {
                    displayError(expiryError, "La date d'expiration est invalide. Veuillez entrer une date au format MM/AA.");
                    valid = false;
                } else {
                    hideError(expiryError);
                }

                // Validation du CVV
                if (!validateCVV(cvvInput.value)) {
                    displayError(cvvError, "Le CVV est invalide. Veuillez entrer un code de sécurité valide (3 chiffres).");
                    valid = false;
                } else {
                    hideError(cvvError);
                }

                // Si une validation échoue, empêcher la soumission du formulaire
                if (!valid) {
                    event.preventDefault();
                }
            });
        });
    </script>

</body>

</html>