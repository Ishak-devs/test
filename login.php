<?php
session_start();
require 'db.php'; // Inclure le fichier de configuration avec les informations de connexion à la base de données

// Vérifier si l'utilisateur est déjà connecté
if (isset($_SESSION['admin_id'])) {
    header('Location: admin_espace.php'); // Rediriger vers l'espace administrateur si déjà connecté
    exit();
}

// Gestion du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']); // Utiliser le nom d'utilisateur
    $mot_de_passe = $_POST['mot_de_passe'];

    // Vérifier si les champs sont remplis
    if (!empty($username) && !empty($mot_de_passe)) {
        // Requête pour récupérer l'administrateur par nom d'utilisateur
        $query = "SELECT * FROM admins WHERE username = :username"; // Requête pour le nom d'utilisateur
        $stmt = $pdo->prepare($query);
        $stmt->execute(['username' => $username]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérifier si l'administrateur existe et si le mot de passe est correct
        if ($admin) {
            // Vérifier le mot de passe avec MD5
            if (md5($mot_de_passe) === $admin['password']) {
                // Stocker l'ID de l'administrateur dans la session
                $_SESSION['admin_id'] = $admin['admin_id'];
                header('Location: admin_espace.php'); // Rediriger vers l'espace administrateur
                exit();
            } else {
                $error_message = "Nom d'utilisateur ou mot de passe incorrect.";
            }
        } else {
            $error_message = "Nom d'utilisateur ou mot de passe incorrect.";
        }
    } else {
        $error_message = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Administrateur</title>
    <link rel="stylesheet" href="css/login.css">
</head>

<body>
    <header>
        <h1>Connexion Administrateur</h1>
    </header>

    <main>
        <form action="login.php" method="post">
            <div>
                <label for="username">Nom d'utilisateur:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div>
                <label for="mot_de_passe">Mot de passe:</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" required>
            </div>
            <button type="submit">Se connecter</button>
        </form>

        <?php if (isset($error_message)): ?>
            <div class="error">
                <p><?php echo htmlspecialchars($error_message); ?></p>
            </div>
        <?php endif; ?>
    </main>

    <footer>
        <p>Ecom INSTA &copy; <?php echo date("Y"); ?></p>
    </footer>

</body>

</html>