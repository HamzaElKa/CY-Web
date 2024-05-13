<?php
$filename = 'utilisateurs.txt';
$lines = file($filename, FILE_IGNORE_NEW_LINES);
$lines = array_reverse($lines);
echo "<div>";
for ($i = 0; $i < min(10, count($lines)); $i++) {
    $user_data = explode(',', $lines[$i]);
    echo "<p>Pseudo: " . $user_data[0] . " " . $user_data[1] . "</p>";
    echo "<p>Date de naissance: " . $user_data[2] . "</p>";
    echo "<p>Sexe: " . $user_data[3] . "</p>";
    echo "<p>Description physique: " . $user_data[4] . "</p>";
    echo "<p>Statut relationnel: " . $user_data[5] . "</p>";
    echo "<p>Ville: " . $user_data[6] . "</p>";
    echo "<hr>";
}
echo "</div>";
?>
