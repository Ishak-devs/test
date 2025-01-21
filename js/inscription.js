//on attent que le html soit chargé
$(document).ready(function () {
    //fonction de validation du formulaire
    function validateForm() {
        let isValid = true;
        //création des messages d'erreurs
        const errorMessages = {
            invalidEmail: 'Adresse email invalide.',
            passwordMismatch: 'Les mots de passe ne correspondent pas.',
            passwordRequirements: 'Le mot de passe doit respecter toutes les exigences.',
            invalidTelephone: 'Numéro de téléphone invalide.',
            invalidAddress: 'Adresse invalide.',
            nameLength: 'Veuillez saisir votre Nom.',
            nameerror: 'Veuillez saisir votre Prénom.',
            repeatedLetters: 'Le Nom ou le prénom est incorrect.'
        };

        //suppression des messages d'erreurs
        $('#inscriptionjs .error').remove();

        //vérifications si les champs requis sont vides
        $('#inscriptionjs input[required]').each(function () {
            if ($(this).val().trim() === '') {
                isValid = false;
                return;
            }
        });

        //vérification si le prénom contient + d'un caractère
        const prenom = $('#inscriptionjs #prenom').val().trim();
        if (prenom.length < 1) {
            $('#inscriptionjs #prenom').after('<div class="error text-danger">' + errorMessages.nameerror + '</div>');
            isValid = false;
            //assurer un prénom valide et empécher les répétitions de caractères
        } else if (/([a-zA-Z])\1{2,}/.test(prenom)) {
            $('#inscriptionjs #prenom').after('<div class="error text-danger">' + errorMessages.repeatedLetters + '</div>');
            isValid = false;
        }

        //vérification si le nom contient + d'un caractère
        const nom = $('#inscriptionjs #nom').val().trim();
        if (nom.length < 1) {
            $('#inscriptionjs #nom').after('<div class="error text-danger">' + errorMessages.nameLength + '</div>');
            isValid = false;
        //assurer un nom valide et empécher les répétitions de caractères
        } else if (/([a-zA-Z])\1{2,}/.test(nom)) {
            $('#inscriptionjs #nom').after('<div class="error text-danger">' + errorMessages.repeatedLetters + '</div>');
            isValid = false;
        }

        //obliger un @ dans l'email
        const email = $('#inscriptionjs #email').val();
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email)) {
            $('#inscriptionjs #email').after('<div class="error text-danger">' + errorMessages.invalidEmail + '</div>');
            isValid = false;
        }

        //assurer des mots de passe identiques
        const password = $('#inscriptionjs #mot_de_passe').val();
        const confirmPassword = $('#inscriptionjs #mot_de_passedeux').val();
        if (password !== confirmPassword) {
            $('#inscriptionjs #mot_de_passedeux').after('<div class="error text-danger">' + errorMessages.passwordMismatch + '</div>');
            isValid = false;
        }

        const requirements = [
            /.{8,}/,
            /[A-Z]/,
            /[a-z]/,
            /\d/,
            /[!@#$%^&*(),.?":{}|<>]/
        ];

        //assurer un respect des exigences du mdp
        const isPasswordValid = requirements.every(req => req.test(password));
        if (!isPasswordValid) {
            $('#inscriptionjs #mot_de_passe').after('<div class="error text-danger">' + errorMessages.passwordRequirements + '</div>');
            isValid = false;
        }

        //assurer uniquement des chiffres dans le téléphone
        const telephone = $('#inscriptionjs #telephone').val();
        const telephonePattern = /^0\d{9}$/;
        if (!telephonePattern.test(telephone)) {
            $('#inscriptionjs #telephone').after('<div class="error text-danger">' + errorMessages.invalidTelephone + '</div>');
            isValid = false;
        }

        //assurer une adresse valide
        const adresse = $('#inscriptionjs #adresse').val();
        const adressePattern = /^[1-9]\d*\s[a-zA-Z0-9\s,'-]{5,}$/;
        if (adresse && !adressePattern.test(adresse)) {
            $('#inscriptionjs #adresse').after('<div class="error text-danger">' + errorMessages.invalidAddress + '</div>');
            isValid = false;
        }

        return isValid;
    }

    //fonction de robustesse du mdp
    function validatePasswordStrength(password) {
        //obligations du mdp
        const requirements = [
            { regex: /.{8,}/, index: 0 },
            { regex: /[A-Z]/, index: 1 },
            { regex: /[a-z]/, index: 2 },
            { regex: /\d/, index: 3 },
            { regex: /[!@#$%^&*(),.?":{}|<>]/, index: 4 }
        ];

        //assurons un début de force à 0
        let strength = 0;
        //boucle pour vérifier les exigences
        requirements.forEach(req => {
            //condition de validation avec la méthode test
            if (req.regex.test(password)) {
                //ajoute 1 à la force
                strength++;
                //ajoute la classe valid à la condition remplie
                $('#password-requirements li').eq(req.index).addClass('valid');
            } else {
                //retirer la class valid si la condition n'est pas remplie
                $('#password-requirements li').eq(req.index).removeClass('valid');
            }
        });
    }

    //vérifier les exigences du mdp à chaque saisie
    $('#inscriptionjs #mot_de_passe').on('input', function () {
        validatePasswordStrength($(this).val());
    });

    //empecher la soumission du formulaire si les conditions ne sont pas remplies
    $('#inscriptionjs').on('submit', function (e) {
        if (!validateForm()) {
            e.preventDefault();
        }
    });
});
