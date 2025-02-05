<?php
session_start();


$captcha_code = random_int(10000, 99999);
$_SESSION['captcha'] = $captcha_code;

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
</head>

<body>
    <form action="captcha.php" method="post">
        <fieldset>
            <legend>Inscription à la newsletter</legend>
            <div>
                <label for="email">Votre Email</label>
                <input type="email" name="email" id="email" placeholder="Votre email" required>
            </div>
            <div>
                <label for="captcha">Copiez le code</label>
                <?php echo $_SESSION['captcha']; ?>
                <input type="text" name="captcha" id="captcha" placeholder="Copiez le code" required>
            </div>
            <?php if (isset($error)): ?>
                <p style="color: red;"><?php echo $error; ?></p>
                <p style="color: green;"></p>
            <?php endif; ?>
            <input type="submit" value="S'inscrire">
        </fieldset>
    </form>
</body>

</html>