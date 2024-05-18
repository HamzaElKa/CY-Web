<?php
if (isset($_POST['user_id'])) {
    $userId = $_POST['user_id'];
    $blockedFile = 'utilisateurs_bloques.txt';
    
    // Lit le fichier et supprime l'utilisateur bloqué
    $blockedUsers = file($blockedFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (($key = array_search($userId, $blockedUsers)) !== false) {
        unset($blockedUsers[$key]);
    }

    // Écrit les utilisateurs mis à jour dans le fichier
    file_put_contents($blockedFile, implode(PHP_EOL, $blockedUsers) . PHP_EOL);
}
?>

