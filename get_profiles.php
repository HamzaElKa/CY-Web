<?php
$filename = 'utilisateurs.txt';
$lines = file($filename, FILE_IGNORE_NEW_LINES);
$lines = array_reverse($lines);
echo "<div id='profiles'>";
for ($i = 0; $i < min(10, count($lines)); $i++) {
    $user_data = explode(',', $lines[$i]);
    echo "<div class='profile'>";
    echo "<p><strong>Pseudo:</strong> " . $user_data[0] . " " . $user_data[1] . "</p>";
    echo "<p><strong>Date de naissance:</strong> " . $user_data[2] . "</p>";
    echo "<p><strong>Sexe:</strong> " . $user_data[3] . "</p>";
    echo "<p><strong>Description physique:</strong> " . $user_data[4] . "</p>";
    echo "<p><strong>Statut relationnel:</strong> " . $user_data[5] . "</p>";
    echo "<p><strong>Ville:</strong> " . $user_data[6] . "</p>";
    if (!empty($user_data[9])) {
        echo "<img src='images/" . $user_data[9] . "' alt='Photo de profil'>";
    }
    echo "</div>";
}
echo "</div>";
?>
