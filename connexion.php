<?php
session_start();
ob_start();
require_once('db.php');


if (isset($_SESSION['utilisateur_id'])) {
    header('Location: index.php');
    exit;
}

// Gestion des messages
$message = "";
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}

include('header.php');
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="css/connexion.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .error {
            color: red;

            font-size: 14px;

            margin-top: 5px;

        }
    </style>
</head>

<body>
    <div class="container d-flex justify-content-center align-items-center">
        <div class="form-container">
            <div class="identifier-box text-center mb-4">
                <h2 class="title">S'identifier</h2>
                <div class="rectangles mb-3">
                    <div class="rectangle"></div>
                    <div class="rectangle"></div>
                </div>
                <p class="description">Entrez vos informations pour vous connecter à votre compte.</p>
            </div>

            <div class="form-box">
                <form action="traitementconnexion.php" method="POST">
                    <div class="mb-3">
                        <label for="email_connexion" class="form-label">Email</label>
                        <input type="email" id="email_connexion" name="email_connexion" class="form-control" required>
                        <div class="error" id="email-error"></div>
                    </div>
                    <div class="mb-3">
                        <label for="mot_de_passe_connexion" class="form-label">Mot de passe</label>
                        <input type="password" id="mot_de_passe_connexion" name="mot_de_passe_connexion" class="form-control" required>
                        <div class="error" id="password-error"></div>

                    </div>
                    <button type="submit" name="connexion" class="btn btn-primary w-100">Se connecter</button>
                </form>

                <button class="btn btn-link create-account" onclick="window.location.href='inscription.php';">Créer un compte</button>
                <button class="btn btn-link create-account" onclick="window.location.href='mdpoublie.php';">Mot de passe oublié ?</button>
            </div>

            <?php if ($message): ?>
                <div class="alert alert-info mt-3">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php
    require_once('footer.html');
    ?>
</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="js/formulaireconn.js"></script>

</html>

<?php
ob_end_flush(); // Envoyer le contenu tamponné
?>