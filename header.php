<?php
$utilisateurConnecte = isset($_SESSION['utilisateur_id']) && isset($_SESSION['prenom']);
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-pzjw8f+ua7Kw1TIq0jGniDqzN9V0mp7erHKKK7jw8JAzXb6zE15j82le79Zp5gZo" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/header.css">
    <script src="js/header.js" defer></script>
    <style>

    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="collapse navbar-collapse" id="navbarNav">
            <form class="form-inline d-flex my-2 my-lg-0" onsubmit="handleSubmit(event)">
                <datalist id="products"></datalist>
                <input list="products" name="product" id="product" class="form-control mr-2" placeholder="Rechercher un produit" style="width: 350px">

                <button type="submit" class="btn btn-outline-success">Rechercher</button>
            </form>


        </div>
        </div>
        </div>
    </nav>


</body>

</html>