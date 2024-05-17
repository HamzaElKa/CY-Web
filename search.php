<?php
$filename = 'utilisateurs.txt';
$lines = file($filename, FILE_IGNORE_NEW_LINES);
$lines = array_reverse($lines);
$results = '';

if (isset($_GET['query']) && !empty($_GET['query'])) {
    $query = strtolower($_GET['query']);
    $found = false;
    foreach ($lines as $line) {
        $user_data = explode(',', $line);
        if (strpos(strtolower($user_data[0]), $query) !== false || strpos(strtolower($user_data[1]), $query) !== false) {
            $results .= "<div class='profile'>";
            $results .= "<p><strong>Pseudo:</strong> " . $user_data[0] . " " . $user_data[1] . "</p>";
            $results .= "<p><strong>Date de naissance:</strong> " . $user_data[2] . "</p>";
            $results .= "<p><strong>Sexe:</strong> " . $user_data[3] . "</p>";
            $results .= "<p><strong>Description physique:</strong> " . $user_data[4] . "</p>";
            $results .= "<p><strong>Statut relationnel:</strong> " . $user_data[5] . "</p>";
            $results .= "<p><strong>Ville:</strong> " . $user_data[6] . "</p>";
            if (!empty($user_data[9])) {
                $results .= "<img src='images/" . $user_data[9] . "' alt='Photo de profil' style='max-width: 200px; max-height: 200px;'>";
            }
            $results .= "</div>";
            $found = true;
        }
    }
    if (!$found) {
        $results = "<p>Aucun utilisateur trouvé avec ce nom.</p>";
    }
} else {
    foreach ($lines as $line) {
        $user_data = explode(',', $line);
        $results .= "<div class='profile'>";
        $results .= "<p><strong>Pseudo:</strong> " . $user_data[0] . " " . $user_data[1] . "</p>";
        $results .= "<p><strong>Date de naissance:</strong> " . $user_data[2] . "</p>";
        $results .= "<p><strong>Sexe:</strong> " . $user_data[3] . "</p>";
        $results .= "<p><strong>Description physique:</strong> " . $user_data[4] . "</p>";
        $results .= "<p><strong>Statut relationnel:</strong> " . $user_data[5] . "</p>";
        $results .= "<p><strong>Ville:</strong> " . $user_data[6] . "</p>";
        if (!empty($user_data[9])) {
            $results .= "<img src='images/" . $user_data[9] . "' alt='Photo de profil' style='max-width: 200px; max-height: 200px;'>";
        }
        $results .= "</div>";
    }
}
echo "<div id='profiles'>" . $results . "</div>";
?>
