<?php
session_start();
if(isset($_SESSION['email'])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $filename = 'utilisateurs.txt';
        $users = file($filename, FILE_IGNORE_NEW_LINES); 
        for ($i = 0; $i < count($users); $i++) {
            $user_data = explode(',', $users[$i]);
            if ($user_data[7] == $_SESSION['email']) {
                $user_data[0] = $_POST['firstname'];
                $user_data[1] = $_POST['lastname'];
                $user_data[2] = $_POST['birthday'];
                $user_data[3] = $_POST['gender'];
                $user_data[4] = $_POST['physical_description'];
                $user_data[5] = $_POST['relationship_status'];
                $user_data[6] = $_POST['city'];
                $user_data[7] = $_POST['email'];
                if(isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
                    $fileTmpPath = $_FILES['profile_pic']['tmp_name'];
                    $fileName = $_FILES['profile_pic']['name'];
                    $fileNameCmps = explode(".", $fileName);
                    $fileExtension = strtolower(end($fileNameCmps));
                    $newFileName = $_POST['email'] . '.' . $fileExtension;
                    $uploadFileDir = 'images/';
                    $dest_path = $uploadFileDir . $newFileName;
                    if(move_uploaded_file($fileTmpPath, $dest_path)) {
                        $user_data[8] = $newFileName;
                    } else {
                        echo "Une erreur s'est produite lors du téléchargement de votre photo de profil.";
                        exit();
                    }
                }
                $users[$i] = implode(',', $user_data);
                file_put_contents($filename, implode(PHP_EOL, $users));
                header("Location: page_profil.php?update=success");
                exit();
            }
        }
        echo "Erreur : Utilisateur non trouvé.";
    } else {
        header("Location: page_profil.php");
        exit();
    }
} else {
    header("Location: page_connexion.html");
    exit();
}
?>


