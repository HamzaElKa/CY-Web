<?php
$usersFile = 'utilisateurs.txt';
$email = $_GET['email'];
$updated = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newData = implode('|', [
        $_POST['email'],
        $_POST['name'],
        $_POST['firstname'],
        $_POST['birthdate'],
        $_POST['gender'],
        $_POST['physical_description'],
        $_POST['relationship_status'],
        $_POST['city']
    ]);
    $users = file($usersFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($users as &$user) {
        list($userEmail) = explode('|', $user);
        if ($userEmail == $email) {
            $user = $newData;
            $updated = true;
            break;
        }
    }
    if ($updated) {
        file_put_contents($usersFile, implode(PHP_EOL, $users) . PHP_EOL);
        echo "Utilisateur mis à jour avec succès.";
    } else {
        echo "Erreur lors de la mise à jour.";
    }
} else {
    $users = file($usersFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($users as $user) {
        list($userEmail, $name, $firstname, $birthdate, $gender, $physical_description, $relationship_status, $city) = explode('|', $user);
        if ($userEmail == $email) {
?>
            <form method="POST">
                Email: <input type="email" name="email" value="<?php echo $userEmail; ?>" required><br>
                Nom: <input type="text" name="name" value="<?php echo $name; ?>" required><br>
                Prénom: <input type="text" name="firstname" value="<?php echo $firstname; ?>" required><br>
                Date de naissance: <input type="date" name="birthdate" value="<?php echo $birthdate; ?>" required><br>
                Sexe: <input type="text" name="gender" value="<?php echo $gender; ?>" required><br>
                Description physique: <textarea name="physical_description"><?php echo $physical_description; ?></textarea><br>
                Statut relationnel: <input type="text" name="relationship_status" value="<?php echo $relationship_status; ?>" required><br>
                Ville: <input type="text" name="city" value="<?php echo $city; ?>" required><br>
                <button type="submit">Mettre à jour</button>
            </form>
<?php
            break;
        }
    }
}
?>