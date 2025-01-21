<head>
    <link rel="stylesheet" href="css/mdp.css">
</head>
<?php
session_start();
require_once('header.php');

// Afficher les messages de session
if (isset($_SESSION['message'])) {
    echo "<div class='message'>" . htmlspecialchars($_SESSION['message']) . "</div>";
    unset($_SESSION['message']); // Supprimer le message après affichage
}

if (isset($_GET['token'])) {
    $token = htmlspecialchars($_GET['token']);
?>
    <div class="reset-password-container">
        <form class="reset-password-form" id="resetForm" action="reset.php?token=<?php echo $token; ?>" method="POST" onsubmit="return validatePassword()">
            <h2 class="form-title">Réinitialiser le Mot de Passe</h2>

            <label class="form-label" for="new_password">Nouveau Mot de Passe :</label>
            <input class="form-input" type="password" id="new_password" name="new_password" required autocomplete="new-password">

            <label class="form-label" for="confirm_password">Confirmer le Mot de Passe :</label>
            <input class="form-input" type="password" id="confirm_password" name="confirm_password" required autocomplete="new-password">

            <ul id="password-requirements" class="requirements-list">
                <li class="requirement">Au moins 8 caractères</li>
                <li class="requirement">Une lettre majuscule</li>
                <li class="requirement">Une lettre minuscule</li>
                <li class="requirement">Un chiffre</li>
                <li class="requirement">Un caractère spécial</li>
            </ul>

            <p id="error-message" style="color: red;"></p> <!-- Message d'erreur affiché en rouge -->

            <button class="form-button" type="submit" name="update">Mettre à Jour</button>
        </form>
    </div>
<?php
} else {
    echo "<div class='error-message'>Token invalide.</div>";
}

require_once('footer.html');
?>
<script src="js/validate_password.js"></script>