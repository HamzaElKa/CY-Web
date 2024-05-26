<?php
//nom d'utilisateur
$filename = 'utilisateurs.txt';
//lecture du fichier
$lines = file($filename, FILE_IGNORE_NEW_LINES);
//inversion de l'ordre des lignes
$lines = array_reverse($lines);
//initialisation de la variable de recherche 
$results = '';

// verifie si les parametres de requete sont definis et non vide
if (isset($_GET['query']) && !empty($_GET['query'])) {
    //conversion en miniscules pour la comparaison
    $query = strtolower($_GET['query']);
    $found = false; // indacateur pour utilisateru trouvé ou non
    //parcout chaque ligne utilisateur
    foreach ($lines as $line) {
        //conversion en tableau
        $user_data = explode(',', $line);
        // verification du nom et prenom par rapport à la requete
        if (strpos(strtolower($user_data[0]), $query) !== false || strpos(strtolower($user_data[1]), $query) !== false) {
            //création d'un bloc html pour afficher les informations de l'utilisateur trouvé
            $results .= "<div class='profile'>";
            $results .= "<p><strong>Pseudo:</strong> " . $user_data[0] . " " . $user_data[1] . "</p>";
            $results .= "<p><strong>Date de naissance:</strong> " . $user_data[2] . "</p>";
            $results .= "<p><strong>Sexe:</strong> " . $user_data[3] . "</p>";
            $results .= "<p><strong>Description physique:</strong> " . $user_data[4] . "</p>";
            $results .= "<p><strong>Statut relationnel:</strong> " . $user_data[5] . "</p>";
            $results .= "<p><strong>Ville:</strong> " . $user_data[6] . "</p>";
            // si l'utilisateur a une photo de profil on 'ajoute au bloc
            if (!empty($user_data[9])) {
                $results .= "<img src='images/" . $user_data[9] . "' alt='Photo de profil' style='max-width: 200px; max-height: 200px;'>";
            }
            $results .= "</div>";
            $found = true; // indique qu'un utilisateur a été trouvé
        }
    }
    //si on a pas trouvé d'utilisateru affichage d'un message d'erreur.
    if (!$found) {
        $results = "<p>Aucun utilisateur trouvé avec ce nom.</p>";
    }
} else {
    //si aucune recherhce n'est demandée affichage de tous les utilisateurs
    foreach ($lines as $line) {
        //conversion en tableau
        $user_data = explode(',', $line);
        $results .= "<div class='profile'>";
        $results .= "<p><strong>Pseudo:</strong> " . $user_data[0] . " " . $user_data[1] . "</p>";
        $results .= "<p><strong>Date de naissance:</strong> " . $user_data[2] . "</p>";
        $results .= "<p><strong>Sexe:</strong> " . $user_data[3] . "</p>";
        $results .= "<p><strong>Interêt en voitures :</strong> " . $user_data[4] . "</p>";
        $results .= "<p><strong>Statut relationnel:</strong> " . $user_data[5] . "</p>";
        $results .= "<p><strong>Ville:</strong> " . $user_data[6] . "</p>";
        //si l'utilisateur a une photo de profil on l'ajoute au bloc.
        if (!empty($user_data[9])) {
            $results .= "<img src='images/" . $user_data[9] . "' alt='Photo de profil' style='max-width: 200px; max-height: 200px;'>";
        }
        $results .= "</div>";
    }
}
//affichage du resultat 
echo "<div id='profiles'>" . $results . "</div>";