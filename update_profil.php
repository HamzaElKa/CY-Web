<?php
session_start();
//si l'utilisateur est connecté
if (isset($_SESSION['email']) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $filename = 'utilisateurs.txt';
    $users = file($filename, FILE_IGNORE_NEW_LINES);
    //récuperer les informations du formulaire
    $emailExists = false;
    $currentEmail = $_SESSION['email'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $birthdate = $_POST['birthdate'];
    $gender = $_POST['gender'];
    $physical_description = $_POST['physical_description'];
    $relationship_status = $_POST['relationship_status'];
    $city = $_POST['city'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    foreach ($users as $user) {
        //verifier les lignes du fichier utilisateurs.txt ligne par ligne jusqu'à ce qu'on trouve la ligne concerné
        $user_data = explode(',', $user);
        //partir à la ligne concerné
        if ($user_data[7] == $email && $email != $currentEmail) {
            $emailExists = true;
            break;
        }
    }
//si l'adresse mail est déjà utilisé
    if ($emailExists) {
        die("Cette adresse email est déjà utilisée.");
    }
//verification d'age
    try {
        $birthDate = new DateTime($birthdate);
        $today = new DateTime();
        $age = $today->diff($birthDate)->y;
    } catch (Exception $e) {
        die("Date de naissance invalide.");
    }
//si l'age est inferieur à 18, une generation d'erreur
    if ($age < 18) {
        die("Vous devez avoir au moins 18 ans pour modifier votre profil.");
    }
//sinon
    for ($i = 0; $i < count($users); $i++) {
        $user_data = explode(',', $users[$i]);
        //mettre à jour les nouvelles informations
        if ($user_data[7] == $currentEmail) {
            $user_data[0] = $firstname;
            $user_data[1] = $lastname;
            $user_data[2] = $birthdate;
            $user_data[3] = $gender;
            $user_data[4] = $physical_description;
            $user_data[5] = $relationship_status;
            $user_data[6] = $city;
            $user_data[7] = $email;
            $user_data[8] = $password;
//modifier la photo de profil
            if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['profile_pic']['tmp_name'];
                $fileName = $_FILES['profile_pic']['name'];
                $fileNameCmps = explode(".", $fileName);
                $fileExtension = strtolower(end($fileNameCmps));
                $newFileName = $email . '.' . $fileExtension;
                $uploadFileDir = 'images/';
                $dest_path = $uploadFileDir . $newFileName;
                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    $user_data[9] = $newFileName;
                } else {
                    echo "Une erreur s'est produite lors du téléchargement de votre photo de profil.";
                    exit();
                }
            }
//si les informations ont bien été modifié
            $users[$i] = implode(',', $user_data);
            file_put_contents($filename, implode(PHP_EOL, $users));
            $_SESSION['email'] = $email; 
            header("Location: page_profil.php?update=success");
            exit();
        }
    }//sinon
    echo "Erreur : Utilisateur non trouvé.";
} else {//redirection vers la page de connexion
    header("Location: page_connexion.html");
    exit();
}
?>
