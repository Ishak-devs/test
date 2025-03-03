<?php


$panier = isset($_SESSION['panier']) ? $_SESSION['panier'] : [];


$_SESSION = array();
session_destroy();


session_start();


if (!empty($panier)) {
    $_SESSION['panier'] = $panier;
}


header('Location: index.php');
exit;
