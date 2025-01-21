// Fonction de validation du formulaire
function validateForm(event) {
    // Empêche la soumission du formulaire pour effectuer les vérifications
    event.preventDefault();

    // Réinitialiser les messages d'erreur
    resetErrorMessages();

    // Récupérer les valeurs des champs
    const name = document.getElementById("name").value.trim();
    const cardNumber = document.getElementById("card-number").value.trim();
    const expiry = document.getElementById("expiry").value.trim();
    const cvv = document.getElementById("cvv").value.trim();

    let isValid = true;

    // Validation du nom sur la carte
    if (!validateName(name)) {
        displayError("name-error", "Le nom sur la carte est obligatoire et doit contenir uniquement des lettres et des espaces.");
        isValid = false;
    }

    // Validation du numéro de carte (doit être un nombre de 16 chiffres)
    if (!validateCardNumber(cardNumber)) {
        displayError("card-number-error", "Le numéro de carte doit contenir exactement 16 chiffres et être valide.");
        isValid = false;
    }

    // Validation de la date d'expiration (format MM/AA)
    if (!validateExpiryDate(expiry)) {
        displayError("expiry-error", "La date d'expiration doit être au format MM/AA.");
        isValid = false;
    }

    // Validation de la date d'expiration pour s'assurer qu'elle n'est pas dans le passé
    if (!isExpiryDateValid(expiry)) {
        displayError("expiry-error", "La date d'expiration ne peut pas être dans le passé.");
        isValid = false;
    }

    // Validation du CVV (doit être un nombre à 3 chiffres)
    if (!validateCVV(cvv)) {
        displayError("cvv-error", "Le code de sécurité (CVV) doit être un nombre de 3 chiffres.");
        isValid = false;
    }

    // Si tout est valide, soumettre le formulaire, sinon afficher un message de validation échouée
    if (isValid) {
        // Soumettre le formulaire si toutes les validations sont correctes
        document.getElementById('payment-form').submit();
    } else {
        // Si le formulaire n'est pas valide, afficher un message général ou afficher quelque chose de visible pour l'utilisateur
        console.log("Formulaire invalide. Les erreurs doivent être corrigées avant soumission.");
    }
}

// Fonction de réinitialisation des messages d'erreur
function resetErrorMessages() {
    document.getElementById("name-error").textContent = '';
    document.getElementById("card-number-error").textContent = '';
    document.getElementById("expiry-error").textContent = '';
    document.getElementById("cvv-error").textContent = '';
}

// Fonction d'affichage des messages d'erreur
function displayError(elementId, message) {
    document.getElementById(elementId).textContent = message;
}

// Validation du nom (doit être non vide et ne contenir que des lettres et des espaces)
function validateName(name) {
    const namePattern = /^[A-Za-zÀ-ÿ\s]+$/; // Lettres et espaces
    return name !== "" && namePattern.test(name);
}

// Validation du numéro de carte (doit être un nombre de 16 chiffres)
function validateCardNumber(cardNumber) {
    // Supprimer les espaces éventuels dans le numéro de carte
    cardNumber = cardNumber.replace(/\s+/g, '');

    // Vérification que le numéro contient bien 16 chiffres
    const cardNumberPattern = /^\d{16}$/;
    if (!cardNumberPattern.test(cardNumber)) {
        return false;
    }

    // Vérification avec l'algorithme de Luhn
    return luhnCheck(cardNumber);
}

// Validation de la date d'expiration (format MM/AA)
function validateExpiryDate(expiry) {
    const expiryPattern = /^(0[1-9]|1[0-2])\/\d{2}$/;
    return expiryPattern.test(expiry);
}

// Vérification de la validité de la date d'expiration
function isExpiryDateValid(expiry) {
    const currentDate = new Date();
    const [month, year] = expiry.split('/').map(Number);
    const expiryDate = new Date(2000 + year, month - 1); // Le format MM/AA nécessite de rajouter "20" devant l'année
    return expiryDate >= currentDate;
}

// Validation du CVV (doit être un nombre à 3 chiffres)
function validateCVV(cvv) {
    const cvvPattern = /^\d{3}$/;
    return cvvPattern.test(cvv);
}

// Ajout d'un événement sur la soumission du formulaire
document.getElementById('payment-form').addEventListener('submit', validateForm);

// Fonction de vérification de la validité du numéro de carte avec l'algorithme de Luhn
function luhnCheck(cardNumber) {
    let sum = 0;
    let shouldDouble = false;
    for (let i = cardNumber.length - 1; i >= 0; i--) {
        let digit = parseInt(cardNumber.charAt(i));
        if (shouldDouble) {
            digit *= 2;
            if (digit > 9) digit -= 9;
        }
        sum += digit;
        shouldDouble = !shouldDouble;
    }
    return (sum % 10 === 0);
}
