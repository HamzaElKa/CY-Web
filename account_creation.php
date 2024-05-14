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
        $filename = 'utilisateurs.txt';
        $data = $firstname . ',' .$name . ',' . $birthdate . ',' . $gender . ',' . $physical_description .',' .$relationship_status . ',' .$city .',' .$email . ',' .$password .',' ."\r\n";
        file_put_contents($filename, $data, FILE_APPEND | LOCK_EX);        
        header("Location: page_connexion.html");     
} 
 ?>
