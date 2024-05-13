<?php
session_start();
if(isset($_SESSION['email'])) {
    $filename = 'utilisateurs.txt';
    $users = file($filename, FILE_IGNORE_NEW_LINES);
    foreach ($users as $user) {
        $user_data = explode(',', $user);
        if ($user_data[7] == $_SESSION['email']) {
            ?>
            <!DOCTYPE html>
            <html lang="fr">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Modifier le profil</title>
                <link rel="stylesheet" href="style_modif.css">
            </head>
            <body>
                <h1>Modifier le profil</h1>
                <form action="update_profil.php" method="POST">
                    <label for="firstname">Prénom :</label>
                    <input type="text" name="firstname" value="<?php echo $user_data[0]; ?>"><br>
                    <label for="lastname">Nom :</label>
                    <input type="text" name="lastname" value="<?php echo $user_data[1]; ?>"><br>
                    <label for="birthday">Date de naissance :</label>
                    <input type="date" name="birthday" value="<?php echo $user_data[2]; ?>"><br>
                    <label for="gender">Sexe :</label>
                    <input type="text" name="gender" value="<?php echo $user_data[3]; ?>"><br>
                    <label for="physical_description">Description physique :</label>
                    <input type="text" name="physical_description" value="<?php echo $user_data[4]; ?>"><br>
                    <label for="relationship_status">Statut relationnel :</label>
                    <input type="text" name="relationship_status" value="<?php echo $user_data[5]; ?>"><br>
                    <label for="city">Ville :</label>
                    <input type="text" name="city" value="<?php echo $user_data[6]; ?>"><br>
                    <label for="email">Email :</label>
                    <input type="email" name="email" value="<?php echo $user_data[7]; ?>"><br>
                    <button type="submit">Enregistrer les modifications</button>
                </form>
                <a href="page_profil.php">Retour au profil</a>
            </body>
            </html>
            <?php
            exit(); 
        }
    }
    echo "Erreur : Utilisateur non trouvé.";
} else {
    header("Location: page_connexion.html");
    exit();
}
?>
