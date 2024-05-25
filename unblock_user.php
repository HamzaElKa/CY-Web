<?php
session_start();

if (isset($_POST['user_id']) && isset($_SESSION['email'])) {
    $userId = $_POST['user_id'];
    $blockerEmail = $_SESSION['email'];
    $blockedFile = 'utilisateurs_bloques.txt';
    $blockedUsers = file($blockedFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $updatedBlockedUsers = [];
    foreach ($blockedUsers as $line) {
        list($blocker, $blocked) = explode(',', $line);
        if ($blocker !== $blockerEmail || $blocked !== $userId) {
            $updatedBlockedUsers[] = $line;
        }
    }
    file_put_contents($blockedFile, implode(PHP_EOL, $updatedBlockedUsers) . PHP_EOL);
    echo "Utilisateur débloqué avec succès.";
} else {
    echo "Erreur: données manquantes.";
}
?>

