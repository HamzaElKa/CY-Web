<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
    $subscriptionType="a";
    if(isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['profile_pic']['tmp_name'];
        $fileName = $_FILES['profile_pic']['name'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        $newFileName = $email . '.' . $fileExtension;
        $uploadFileDir = 'images/';
        $dest_path = $uploadFileDir . $newFileName;
        if(move_uploaded_file($fileTmpPath, $dest_path)) {
            $filename = 'utilisateurs.txt';
            $data = $firstname . ',' . $name . ',' . $birthdate . ',' . $gender . ',' . $physical_description . ',' . $relationship_status . ',' . $city . ',' . $email . ',' . $password . ',' . $newFileName . ',' . $subscriptionType. PHP_EOL;
            file_put_contents($filename, $data, FILE_APPEND | LOCK_EX);
            header("Location: page_connexion.html");
        } else {
            echo "Une erreur s'est produite lors du téléchargement de votre fichier.";
        }
    } else {
        echo "Erreur: Aucun fichier téléchargé ou une erreur s'est produite lors du téléchargement.";
    }
}
?>
