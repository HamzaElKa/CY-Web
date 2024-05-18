<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$blocked_file = 'utilisateurs_bloques.txt';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];

    // Vérifier l'existence du fichier
    if (!file_exists($blocked_file)) {
        die("Le fichier des utilisateurs bloqués n'existe pas.");
    }

    // Lire les utilisateurs bloqués actuels
    $blocked_users = file($blocked_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    // Vérifier si l'utilisateur est dans la liste des utilisateurs bloqués
    if (in_array($user_id, $blocked_users)) {
        // Retirer l'utilisateur du fichier des utilisateurs bloqués
        $blocked_users = array_diff($blocked_users, [$user_id]);
        file_put_contents($blocked_file, implode(PHP_EOL, $blocked_users) . PHP_EOL);
        echo "Utilisateur débloqué avec succès.";
    } else {
        echo "Utilisateur non trouvé dans la liste des utilisateurs bloqués.";
    }
}
?>
