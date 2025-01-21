//attente de la fin du chargement du document
$(document).ready(function() {
    //au moment de validation du formulaire, appel a la fonction event
    $('form').on('submit', function(event) {

        //vider les champs contenant des erreurs 
        $('.error').text('');

        //stockage des éléments saisies
        var email = $('#email_connexion').val();
        var password = $('#mot_de_passe_connexion').val();
        var valid = true; 

        //empecher le champ email vide
        if (email.trim() === '') {
            $('#email-error').text("L'email est requis."); 
            valid = false; 
        }

        //empecher le champ mot de passe vide
        if (password.trim() === '') {
            $('#password-error').text("Le mot de passe est requis."); 
            valid = false; 
        }

        //obliger l'email a contenir un @ et un point
        var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email && !emailPattern.test(email)) {
            $('#email-error').text("L'email est invalide."); 
            valid = false; 
        }

        //si le formulaire est invalide on empeche l'envoi
        if (!valid) {
            event.preventDefault(); 
        }
    });
});
