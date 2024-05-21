<?php
// admin_supprimer_utilisateur.php
$usersFile = 'utilisateurs.txt';
$bannedFile = 'bannissements.txt';
$email = $_GET['email'];

$users = file($usersFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$newUsers = array_filter($users, function ($user) use ($email) {
    list($userEmail) = explode('|', $user);
    return $userEmail != $email;
});

file_put_contents($usersFile, implode(PHP_EOL, $newUsers) . PHP_EOL);
file_put_contents($bannedFile, $email . PHP_EOL, FILE_APPEND);

echo "Utilisateur supprimé et banni.";