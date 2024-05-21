<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $filename = 'utilisateurs.txt';
    $users = file($filename, FILE_IGNORE_NEW_LINES);
    $is_authenticated = false;
    $user_data = [];

    foreach ($users as $user) {
        $user_data = explode(',', $user);
        if ($user_data[7] == $email && $user_data[8] == $password) {
            $_SESSION["email"] = $email;
            $_SESSION['user_data'] = $user_data; // Stocker les données utilisateur dans la session
            $_SESSION['is_admin'] = ($user_data[10] == '1'); // Stocker le statut d'administrateur dans la session
            $is_authenticated = true;
            break;
        }
    }

    if ($is_authenticated) {
        $subscriptionType = isset($user_data[11]) ? $user_data[11] : 'a';
        switch ($subscriptionType) {
            case 'a':
                header('Location: page_profil.php');
                exit();
            case 'basique':
                header('Location: page_profil_bas.php');
                exit();
            case 'premium':
                header('Location: page_profil_prem.php');
                exit();
            case 'essai':
                header('Location: page_profil_ess.php');
                exit();
            default:
                header('Location: index.html');
                exit();
        }
    } else {
        header("Location: page_connexion.html?error=1");
        exit();
    }
}