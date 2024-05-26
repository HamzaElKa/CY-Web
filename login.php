<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
//recuperer les donnes de connexion
    $email = $_POST["email"];
    $password = $_POST["password"];
    $filename = 'utilisateurs.txt';
    $users = file($filename, FILE_IGNORE_NEW_LINES);
    $is_authenticated = false;
    $user_data = [];
//parcourir chaque utilisateur dans le fichier
    foreach ($users as $user) {
        $user_data = explode(',', $user);
        if ($user_data[7] == $email && $user_data[8] == $password) {
            $_SESSION["email"] = $email;
            $_SESSION['user_data'] = $user_data;
            $_SESSION['is_admin'] = ($user_data[10] == '1'); //verifie si l'utilisateur est admin ou pas
           
            $is_authenticated = true;
            break;
        }
    }
// si l'utilisateur est authentifié redirige le vers page_profil.php
    if ($is_authenticated) {
                header('Location: page_profil.php');
        }
    } else {
        header("Location: page_connexion.html?error=1");
        exit();
    }