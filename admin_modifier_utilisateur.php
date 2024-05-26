<?php
// Fichier qui contient toutes les informartions de l'utilisateur
$usersFile = 'utilisateurs.txt';

// Récupère l'email de l'utilisateur à qui on veut modifier
$email = $_GET['email'];
$updated = false;


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Crée une nouvelle ligne de données utilisateur à partir des donnees qu'on a deja
    $newData= implode('|',[
        $_POST['email'],
        $_POST['name'],
        $_POST['firstname'],
        $_POST['birthdate'],
        $_POST['gender'],
        $_POST['physical_description'],
        $_POST['relationship_status'],
        $_POST['city']
    ]);

    // Charge les utilisateurs depuis le fichier
    $users = file($usersFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    // Parcourt les utilisateurs pour trouver celui qui correspond à l'email
    foreach ($users as &$user) {
        list($userEmail) = explode('|', $user);
        if ($userEmail == $email) {
            // Met à jour les informations de l'utilisateur
            $user = $newData;
            $updated = true;
            break;
        }
    }

    // Si la mise à jour a été effectuée, réécrit le fichier avec les nouvelles données
    if ($updated) {
        file_put_contents($usersFile, implode(PHP_EOL, $users) . PHP_EOL);
        echo "Utilisateur mis à jour avec succès.";
    } else {
        echo "Erreur lors de la mise à jour.";
    }
} else {
    // Charge les utilisateurs depuis le fichier
    $users = file($usersFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    // Parcourt les utilisateurs pour trouver celui qui correspond à l'email
    foreach ($users as $user) {
        // Décompose les informations utilisateur
        list($userEmail, $name, $firstname, $birthdate, $gender, $physical_description, $relationship_status, $city) = explode('|', $user);
        if ($userEmail == $email) {
?>
            <!-- Formulaire de mise à jour des informations utilisateur -->
            <form method="POST">
                Email: <input type="email" name="email" value="<?php echo htmlspecialchars($userEmail); ?>" required><br>
                Nom: <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" required><br>
                Prénom: <input type="text" name="firstname" value="<?php echo htmlspecialchars($firstname); ?>" required><br>
                Date de naissance: <input type="date" name="birthdate" value="<?php echo htmlspecialchars($birthdate); ?>" required><br>
                Sexe: <input type="text" name="gender" value="<?php echo htmlspecialchars($gender); ?>" required><br>
                Interêt en voitures: <textarea name="physical_description"><?php echo htmlspecialchars($physical_description); ?></textarea><br>
                Statut relationnel: <input type="text" name="relationship_status" value="<?php echo htmlspecialchars($relationship_status); ?>" required><br>
                Ville: <input type="text" name="city" value="<?php echo htmlspecialchars($city); ?>" required><br>
                <button type="submit">Mettre à jour</button>
            </form>
<?php
            break;
        }
    }
}
?>

