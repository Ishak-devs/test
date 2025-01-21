function validatePassword() {
    const password = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    const errorMessage = document.getElementById('error-message');

    // Définir les critères de robustesse du mot de passe
    const minLength = 8;
    const hasUpperCase = /[A-Z]/;
    const hasLowerCase = /[a-z]/;
    const hasNumber = /[0-9]/;
    const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/;

    // Vérifier si les mots de passe correspondent
    if (password !== confirmPassword) {
        errorMessage.textContent = "Les mots de passe ne correspondent pas.";
        return false;
    }

    // Vérifier si le mot de passe est suffisamment robuste
    if (password.length < minLength) {
        errorMessage.textContent = `Le mot de passe doit contenir au moins ${minLength} caractères.`;
        return false;
    }
    if (!hasUpperCase.test(password)) {
        errorMessage.textContent = "Le mot de passe doit contenir au moins une lettre majuscule.";
        return false;
    }
    if (!hasLowerCase.test(password)) {
        errorMessage.textContent = "Le mot de passe doit contenir au moins une lettre minuscule.";
        return false;
    }
    if (!hasNumber.test(password)) {
        errorMessage.textContent = "Le mot de passe doit contenir au moins un chiffre.";
        return false;
    }
    if (!hasSpecialChar.test(password)) {
        errorMessage.textContent = "Le mot de passe doit contenir au moins un caractère spécial (ex: !@#$%^&*).";
        return false;
    }
    return true; // Si toutes les vérifications passent
}

// Vérifier les exigences du mot de passe à chaque saisie
document.getElementById('new_password').addEventListener('input', function () {
    validatePasswordStrength(this.value);
});

function validatePasswordStrength(password) {
    const requirements = [
        { regex: /.{8,}/, index: 0 },
        { regex: /[A-Z]/, index: 1 },
        { regex: /[a-z]/, index: 2 },
        { regex: /\d/, index: 3 },
        { regex: /[!@#$%^&*(),.?":{}|<>]/, index: 4 }
    ];

    const requirementsListItems = document.querySelectorAll('#password-requirements li');

    requirements.forEach(req => {
        if (req.regex.test(password)) {
            requirementsListItems[req.index].classList.add('valid'); // Ajoute la classe pour le texte vert
        } else {
            requirementsListItems[req.index].classList.remove('valid'); // Retire la classe si non respectée
        }
    });
}
