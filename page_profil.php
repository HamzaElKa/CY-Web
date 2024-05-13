<?php
session_start();
if(isset($_SESSION['email'])) {
    $filename = 'utilisateurs.txt';
    $users = file($filename, FILE_IGNORE_NEW_LINES); 
        foreach ($users as $user) {
        $user_data = explode(',', $user);
        if ($user_data[7] == $_SESSION['email']) {
            echo "<h1>Profil de ".$user_data[0]." ".$user_data[1]."</h1>";
            echo "<h2>Informations de profil</h2>";
            echo "<p><strong>Prénom :</strong> ".$user_data[0]."</p>";
            echo "<p><strong>Nom :</strong> ".$user_data[1]."</p>";
            echo "<p><strong>Date de naissance :</strong> ".$user_data[2]."</p>";
            echo "<p><strong>Sexe :</strong> ".$user_data[3]."</p>";
            echo "<p><strong>Description physique :</strong> ".$user_data[4]."</p>";
            echo "<p><strong>Statut relationnel :</strong> ".$user_data[5]."</p>";
            echo "<p><strong>Ville :</strong> ".$user_data[6]."</p>";
            echo "<p><strong>Email :</strong> ".$user_data[7]."</p>";
            echo "<p><a href='logout.php'>Se déconnecter</a></p>";
            echo "<p><a href='modifier_profil.php'>Modifier le profil</a></p>";
            echo "<p><a href='dern_prof.html'>Consultation des profiles</a></p>";
            exit();
        }
    }
    echo "Erreur : Utilisateur non trouvé.";
} else {
    header("Location: page_connexion.html");
    exit();
}
?>
