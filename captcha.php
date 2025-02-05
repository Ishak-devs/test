<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['captcha'] != $_SESSION['captcha']) {
        echo 'captcha incorrect';
    }
    //     $error = "Captcha incorrect !";  
    // } else {
    //     $success = "Formulaire envoyé avec succès !";  
    // }
}
