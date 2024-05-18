<?php
if (isset($_POST['user_id'])) {
    $userId = $_POST['user_id'];
    $blockedFile = 'utilisateurs_bloques.txt';

    // Ajoute l'ID utilisateur au fichier des utilisateurs bloqués
    file_put_contents($blockedFile, $userId . PHP_EOL, FILE_APPEND);
}
?>

