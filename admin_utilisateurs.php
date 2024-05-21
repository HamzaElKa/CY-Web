<?php
session_start();
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("Location: index.html");
    exit();
}

$usersFile = 'utilisateurs.txt';
$bannedFile = 'bannissements.txt';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_user'])) {
        // Handle user update
        $email = $_POST['email'];
        $new_description = $_POST['description'];
        $new_message = $_POST['message'];

        $users = file($usersFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $updatedUsers = [];

        foreach ($users as $user) {
            $user_data = explode(',', $user);
            if ($user_data[7] == $email) {
                $user_data[4] = $new_description; // Update description
                $user_data[10] = $new_message; // Update message
            }
            $updatedUsers[] = implode(',', $user_data);
        }

        file_put_contents($usersFile, implode(PHP_EOL, $updatedUsers) . PHP_EOL);
        echo "Utilisateur mis à jour avec succès.";
    } elseif (isset($_POST['delete_user'])) {
        // Handle user deletion
        $email = $_POST['email'];

        $users = file($usersFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $remainingUsers = [];

        foreach ($users as $user) {
            $user_data = explode(',', $user);
            if ($user_data[7] != $email) {
                $remainingUsers[] = $user;
            }
        }

        file_put_contents($usersFile, implode(PHP_EOL, $remainingUsers) . PHP_EOL);
        file_put_contents($bannedFile, $email . PHP_EOL, FILE_APPEND);
        echo "Utilisateur supprimé et banni.";
    }
}

$users = file($usersFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
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
            background-color: #f2f2f2;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }

        button {
            padding: 10px 20px;
            background-color: red;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: darkred;
        }
    </style>
</head>

<body>
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
                <th>Ville</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user) : ?>
                <?php $user_data = explode(',', $user); ?>
                <tr>
                    <td><?php echo $user_data[0]; ?></td>
                    <td><?php echo $user_data[1]; ?></td>
                    <td><?php echo $user_data[2]; ?></td>
                    <td><?php echo $user_data[3]; ?></td>
                    <td><?php echo $user_data[4]; ?></td>
                    <td><?php echo $user_data[5]; ?></td>
                    <td><?php echo $user_data[6]; ?></td>
                    <td><?php echo $user_data[7]; ?></td>
                    <td>
                        <button onclick="showUpdateForm('<?php echo $user_data[7]; ?>')">Modifier</button>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="email" value="<?php echo $user_data[7]; ?>">
                            <button type="submit" name="delete_user">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div id="update-form" style="display:none;">
        <h2>Modifier les informations de l'utilisateur</h2>
        <form method="post">
            <div class="form-group">
                <label>Email</label>
                <input type="text" name="email" id="update-email" readonly>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" id="update-description"></textarea>
            </div>
            <div class="form-group">
                <label>Message d'accueil</label>
                <textarea name="message" id="update-message"></textarea>
            </div>
            <button type="submit" name="update_user">Mettre à jour</button>
        </form>
    </div>

    <script>
        function showUpdateForm(email) {
            document.getElementById('update-email').value = email;
            document.getElementById('update-form').style.display = 'block';
        }
    </script>
</body>

</html>