<?php
session_start();
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("Location: index.html");
    exit();
}

$fichier_utilisateurs = 'utilisateurs.txt';
$fichier_bannis = 'bannissements.txt';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_user'])) {
        // Handle user update
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $firstname = isset($_POST['firstname']) ? $_POST['firstname'] : '';
        $lastname = isset($_POST['lastname']) ? $_POST['lastname'] : '';
        $birthdate = isset($_POST['birthdate']) ? $_POST['birthdate'] : '';
        $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
        $description = isset($_POST['description']) ? $_POST['description'] : '';
        $relationship_status = isset($_POST['relationship_status']) ? $_POST['relationship_status'] : '';
        $city = isset($_POST['city']) ? $_POST['city'] : '';
        $is_admin = isset($_POST['is_admin']) ? $_POST['is_admin'] : '0';
        $subscription_type = isset($_POST['subscription_type']) ? $_POST['subscription_type'] : '';

        $users = file($fichier_utilisateurs, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $updatedUsers = [];

        foreach ($users as $user) {
            $user_data = explode(',', $user);
            if ($user_data[7] == $email) {
                $user_data[0] = $firstname;
                $user_data[1] = $lastname;
                $user_data[2] = $birthdate;
                $user_data[3] = $gender;
                $user_data[4] = $description;
                $user_data[5] = $relationship_status;
                $user_data[6] = $city;
                $user_data[10] = $is_admin;
                $user_data[11] = $subscription_type;
            }
            $updatedUsers[] = implode(',', $user_data);
        }

        file_put_contents($fichier_utilisateurs, implode(PHP_EOL, $updatedUsers) . PHP_EOL);
        echo "Utilisateur mis à jour avec succès.";
    } elseif (isset($_POST['delete_user'])) {
        // Handle user deletion
        $email = isset($_POST['email']) ? $_POST['email'] : '';

        $users = file($fichier_utilisateurs, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $remainingUsers = [];

        foreach ($users as $user) {
            $user_data = explode(',', $user);
            if ($user_data[7] != $email) {
                $remainingUsers[] = $user;
            }
        }

        file_put_contents($fichier_utilisateurs, implode(PHP_EOL, $remainingUsers) . PHP_EOL);
        file_put_contents($fichier_bannis, $email . PHP_EOL, FILE_APPEND);
        echo "Utilisateur supprimé et banni.";
    }
}

$users = file($fichier_utilisateurs, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des utilisateurs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: url('voiture2.jpg') repeat;
        }

        .header-title {
            margin: 0;
            padding: 0;
            color: white;
            font-size: 24px;
        }

        .header-title a {
            color: white;
            text-decoration: none;
        }

        .header-title a:hover {
            text-decoration: underline;
        }

        .bhead {
            background: #000;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-buttons {
            flex: 1;
            display: flex;
            justify-content: flex-end;
        }

        .header-buttons a, .bhead button {
            background: red;
            color: #fff;
            padding: 15px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
            margin-left: 10px;
            text-decoration: none;
        }

        .header-buttons a:hover, .bhead button:hover {
            background-color: #c40000;
        }

        h1, h2 {
            text-align: left;
            margin: 20px;
            color: white;
            font-size: 36px;
            text-shadow: 2px 2px 4px #000000;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        button, .button-red {
            padding: 10px 20px;
            background-color: red;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        button:hover, .button-red:hover {
            background-color: darkred;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }

        #update-form {
            display: none;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
        }

        .centered-button {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .centered-button button {
            padding: 20px 40px; /* Increase the size of the button */
            font-size: 18px; /* Increase the font size */
        }
    </style>
    <script>
        function validateForm() {
            // Add form validation logic here if needed
            return true;
        }

        function showUpdateForm(email, firstname, lastname, birthdate, gender, description, relationship_status, city, is_admin, subscription_type) {
            document.getElementById('update-email').value = email;
            document.getElementById('update-firstname').value = firstname;
            document.getElementById('update-lastname').value = lastname;
            document.getElementById('update-birthdate').value = birthdate;
            document.getElementById('update-gender-' + gender).checked = true;
            document.getElementById('update-description').value = description;
            document.getElementById('update-relationship_status').value = relationship_status;
            document.getElementById('update-city').value = city;
            document.getElementById('update-is_admin').value = is_admin;
            document.getElementById('update-subscription_type').value = subscription_type;
            document.getElementById('update-form').style.display = 'block';
        }
    </script>
</head>

<body>
    <div class="bhead">
        <h1 class="header-title"><a href="index.html">Cardate</a></h1>
        <div class="header-buttons">
            <a href="admin_dashboard.php">Dashboard</a>
        </div>
    </div>
    <div class="centered-button">
        <button onclick="window.location.href='rech_ajax.html'">Recherche</button>
    </div>
    <h1>Gestion des utilisateurs</h1>
    <h2>Liste de tous les utilisateurs enregistrés</h2>
    <table>
        <thead>
            <tr>
                <th>Prénom</th>
                <th>Nom</th>
                <th>Date de naissance</th>
                <th>Sexe</th>
                <th>Description</th>
                <th>Statut relationnel</th>
                <th>Ville</</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user) : ?>
                <?php $user_data = explode(',', $user); ?>
                <tr>
                    <td><?php echo htmlspecialchars($user_data[0]); ?></td>
                    <td><?php echo htmlspecialchars($user_data[1]); ?></td>
                    <td><?php echo htmlspecialchars($user_data[2]); ?></td>
                    <td><?php echo htmlspecialchars($user_data[3]); ?></td>
                    <td><?php echo htmlspecialchars($user_data[4]); ?></td>
                    <td><?php echo htmlspecialchars($user_data[5]); ?></td>
                    <td><?php echo htmlspecialchars($user_data[6]); ?></td>
                    <td><?php echo htmlspecialchars($user_data[7]); ?></td>
                    <td>
                        <button onclick="showUpdateForm(
                            '<?php echo htmlspecialchars($user_data[7]); ?>',
                            '<?php echo htmlspecialchars($user_data[0]); ?>',
                            '<?php echo htmlspecialchars($user_data[1]); ?>',
                            '<?php echo htmlspecialchars($user_data[2]); ?>',
                            '<?php echo htmlspecialchars($user_data[3]); ?>',
                            '<?php echo htmlspecialchars($user_data[4]); ?>',
                            '<?php echo htmlspecialchars($user_data[5]); ?>',
                            '<?php echo htmlspecialchars($user_data[6]); ?>',
                            '<?php echo htmlspecialchars($user_data[10]); ?>',
                            '<?php echo htmlspecialchars($user_data[11]); ?>'
                        )">Modifier</button>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="email" value="<?php echo htmlspecialchars($user_data[7]); ?>">
                            <button type="submit" name="delete_user">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div id="update-form">
        <h2>Modifier les informations de l'utilisateur</h2>
        <form method="post" onsubmit="return validateForm()">
            <div class="form-group">
                <label>Email</label>
                <input type="text" name="email" id="update-email" readonly>
            </div>
            <div class="form-group">
                <label>Prénom</label>
                <input type="text" name="firstname" id="update-firstname" required>
            </div>
            <div class="form-group">
                <label>Nom</label>
                <input type="text" name="lastname" id="update-lastname" required>
            </div>
            <div class="form-group">
                <label>Date de naissance</label>
                <input type="date" name="birthdate" id="update-birthdate" required>
            </div>
            <div class="form-group">
                <label>Sexe</label>
                <input type="radio" id="update-gender-homme" name="gender" value="homme" required>
                <label for="update-gender-homme">Homme</label>
                <input type="radio" id="update-gender-femme" name="gender" value="femme" required>
                <label for="update-gender-femme">Femme</label>
            </div>
            <div class="form-group">
                <label>Interêt en voitures</label>
                <textarea name="description" id="update-description"></textarea>
            </div>
            <div class="form-group">
                <label>Statut relationnel</label>
                <select name="relationship_status" id="update-relationship_status">
                    <option value="célibataire">Célibataire</option>
                    <option value="en couple">En couple</option>
                    <option value="marié(e)">Marié(e)</option>
                    <option value="divorcé(e)">Divorcé(e)</option>
                    <option value="veuf/veuve">Veuf/Veuve</option>
                </select>
            </div>
            <div class="form-group">
                <label>Ville</label>
                <input type="text" name="city" id="update-city" required>
            </div>
            <div class="form-group">
                <label>Statut d'admin</label>
                <select name="is_admin" id="update-is_admin" required>
                    <option value="0">Non</option>
                    <option value="1">Oui</option>
                </select>
            </div>
            <div class="form-group">
                <label>Type d'abonnement</label>
                <select name="subscription_type" id="update-subscription_type" required>
                    <option value="essai">Essai</option>
                    <option value="basique">Basique</option>
                    <option value="premium">Premium</option>
                </select>
            </div>
            <button type="submit" name="update_user">Mettre à jour</button>
        </form>
    </div>
</body>

</html>
