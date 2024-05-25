<?php
session_start();

if (isset($_POST['user_id']) && isset($_SESSION['email'])) {
    $blockedUserId = $_POST['user_id'];
    $blockerEmail = $_SESSION['email'];
    $blockedFile = 'utilisateurs_bloques.txt';
    file_put_contents($blockedFile, $blockerEmail . ',' . $blockedUserId . PHP_EOL, FILE_APPEND);
    echo "Utilisateur bloqué avec succès.";
} else {
    echo "Erreur: données manquantes.";
}
?>
