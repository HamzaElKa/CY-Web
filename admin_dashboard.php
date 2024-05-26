<?php

session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Stocker les donnes du formulaire
    $firstname = $_POST["firstname"];
    $name = $_POST["name"];
    $birthdate = $_POST["birthdate"];
    $gender = $_POST["gender"];
    $physical_description = isset($_POST["physical_description"]) ? $_POST["physical_description"] : "";
    $relationship_status = isset($_POST["relationship_status"]) ? $_POST["relationship_status"] : "";
    $city = $_POST["city"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $passwordConf = $_POST["passwordConf"];
    $adminstatut = 0;
    $subscriptionType = "a";
    $profilePic = $_FILES['profile_pic'];

    // Calcule l'age de l'utilisateur
    $birthDate = new DateTime($birthdate);
    $today = new DateTime();
    $age = $today->diff($birthDate)->y;

    // Vérifie si l'utilisateur a  18 ans
    if ($age < 18) {
        die("Vous devez avoir au moins 18 ans pour vous inscrire.");
    }

    // Vérifie si les mots de passe sont corrects
    if ($password !== $passwordConf) {
        die("Les mots de passe ne correspondent pas.");
    }

    // Fichiers pour stocker les utilisateurs et les bannissements
    $filename = 'utilisateurs.txt';
    $bannedFile = 'bannissements.txt';
    $emailExists = false;

    // Verifie si l'adresse email est bannie
    if (file_exists($bannedFile)) {
        $bannedEmails = file($bannedFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (in_array($email, $bannedEmails)) {
            die("Erreur : Cette adresse email a été bannie.");
        }
    }

    // Verifie si l'adresse email existe déjà
    if (file_exists($filename)) {
        $users = file($filename, FILE_IGNORE_NEW_LINES);
        foreach ($users as $user) {
            $user_data = explode(',', $user);
            if ($user_data[7] == $email) {
                $emailExists = true;
                break;
            }
        }
    }

    // Si l'adresse email est deja utilisee, afficher une erreur
    if ($emailExists) {
        die("Cette adresse email est déjà utilisée.");
    }
//Télécharger les photos de profils et les placer
 
    if (isset($profilePic) && $profilePic['error'] === UPLOAD_ERR_OK) {
        // Chemin temporaire du fichier
        $fileTmpPath = $profilePic['tmp_name'];
        $fileName = $profilePic['name'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        $newFileName = $email . '.' . $fileExtension;

       
        $uploadFileDir = 'images/';
        $dest_path = $uploadFileDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            // les donnes qui seront ecrites dans utilisateurs.txt
            $data = $firstname . ',' . $name . ',' . $birthdate . ',' . $gender . ',' . $physical_description . ',' . $relationship_status . ',' . $city . ',' . $email . ',' . $password . ',' . $newFileName . ',' . $adminstatut . ',' . $subscriptionType . PHP_EOL;

            // Écrit les données dans le fichier utilisateurs
            file_put_contents($filename, $data, FILE_APPEND | LOCK_EX);

            // Redirige l'utilisateur vers la page de connexion
            header("Location: page_connexion.html");
            exit();
        } else {
            // Affiche une erreur si le telechargement a échoue
            echo "Une erreur s'est produite lors du téléchargement de votre fichier.";
        }
    } else {
        // Affiche une erreur si aucun fichier n'a ete telecharge
        echo "Erreur: Aucun fichier téléchargé ou une erreur s'est produite lors du téléchargement.";
    }
}
?>
