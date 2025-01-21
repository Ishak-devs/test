<?php
session_start(); // Démarre la session ici pour garantir l'accès aux messages
require_once('header.php');
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/inscription.css">
</head>

<body>

    <div class="form-container">
        <h2 class="text-center">Inscription</h2>


        <?php
        if (isset($_SESSION['message'])) {
            echo "<div class='alert alert-info'>" . htmlspecialchars($_SESSION['message'], ENT_QUOTES, 'UTF-8') . "</div>";
            unset($_SESSION['message']); // Supprime le message après l'affichage
        }

        if (isset($_SESSION['messagemail'])) {
            echo "<div class='alert alert-danger' role='alert'>" . htmlspecialchars($_SESSION['messagemail'], ENT_QUOTES, 'UTF-8') . "</div>";
            unset($_SESSION['messagemail']);
        }
        ?>

        <form action="traitementinscription.php" id="inscriptionjs" class="needs-validation" method="POST" novalidate>
            <div class="mb-3 form-group">
                <label for="prenom" class="form-label">Prénom <span class="text-danger">*</span></label>
                <input type="text" id="prenom" name="prenom" class="form-control" required>
                <div class="error-message"></div>
            </div>
            <div class="mb-3 form-group">
                <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                <input type="text" id="nom" name="nom" class="form-control" required>
                <div class="error-message"></div>
            </div>
            <div class="mb-3 form-group">
                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" id="email" name="email" class="form-control" required>
                <div class="error-message"></div>
            </div>
            <div class="mb-3 form-group">
                <label for="telephone" class="form-label">Téléphone <span class="text-danger">*</span></label>
                <input type="tel" id="telephone" name="numero_telephone" class="form-control" required>
                <div class="error-message"></div>
            </div>
            <div class="mb-3 form-group">
                <label for="mot_de_passe" class="form-label">Mot de passe <span class="text-danger">*</span></label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" class="form-control" required>
                <ul id="password-requirements" class="requirements-list">
                    <li class="requirement">Au moins 8 caractères</li>
                    <li class="requirement">Une lettre majuscule</li>
                    <li class="requirement">Une lettre minuscule</li>
                    <li class="requirement">Un chiffre</li>
                    <li class="requirement">Un caractère spécial</li>
                </ul>
                <div class="error-message"></div>
            </div>
            <div class="mb-3 form-group">
                <label for="mot_de_passedeux" class="form-label">Confirmez le mot de passe <span class="text-danger">*</span></label>
                <input type="password" id="mot_de_passedeux" name="mot_de_passedeux" class="form-control" required>
                <div class="error-message"></div>
            </div>
            <button type="submit" name="inscription" class="btn btn-primary">S'inscrire</button>
        </form>
    </div>

    <footer>
        <script src="js/inscription.js"></script>
    </footer>
</body>

</html>