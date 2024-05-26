<?php

session_start();

// Vérifie si l'utilisateur est un admin
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    // Redirige l'utilisateur vers la page d'accueil s'il n'est pas administrateur
    header("Location: index.html");
    exit();
}


$usersFile = 'utilisateurs.txt';
$messagesFile = 'messages.txt';
$reportsFile = 'signalements.txt';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Vérifie si l'action de suppression de message est déclenchée
    if (isset($_POST['delete_message'])) {
        $messageIndex = $_POST['message_index'];

        // Charge les messages depuis le fichier
        $messages = file($messagesFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (isset($messages[$messageIndex])) {
            // Supprime le message et renitialiser le fichier avec les nouvelles informations
            unset($messages[$messageIndex]);
            file_put_contents($messagesFile, implode(PHP_EOL, $messages) . PHP_EOL);
            echo "Message supprimé avec succès.";
        }
    }
}

// Charge les utilisateurs depuis le fichier
$users = file($usersFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$selectedUser = isset($_GET['email']) ? $_GET['email'] : null;
$userMessages = [];

// Si un utilisateur est sélectionné, charge ses messages et les afficher
if ($selectedUser) {
    $messages = file($messagesFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($messages as $message) {
        $message_data = explode('|', $message);
        if ($message_data[0] == $selectedUser || $message_data[1] == $selectedUser) {
            $userMessages[] = $message;
        }
    }
}

// Charge les signalements depuis le fichier
$reports = file($reportsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des messages</title>
    <style>
        /* ajout du style pour notre page*/
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
            display: flex;
            align-items: center;
        }

        .button-group {
            display: flex;
            gap: 10px;
            margin-left: auto;
        }
/* Pour mettre les boutons dashboard et profil en rouge */
        .bhead button, .header-buttons a {
            background: red;
            color: #fff;
            padding: 15px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
            text-decoration: none;
        }

        .bhead button:hover, .header-buttons a:hover {
            background-color: #c40000;
        }

        /*Pour centrer les bouttons */
        .centered-button {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .centered-button button {
            padding: 20px 40px;
            font-size: 18px;
        }

        /* Styles pour les titres */
        h1, h2 {
            text-align: left;
            margin: 20px;
            color: white;
            font-size: 36px;
            text-shadow: 2px 2px 4px #000000;
        }

        /* Style du tableau  */
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
    </style>
</head>

<body>

    <div class="bhead">
        <h1 class="header-title"><a href="index.html">Cardate</a></h1>
        <div class="header-buttons">
            <div class="button-group">
 
                <a href="admin_dashboard.php">Dashboard</a>
                <a href="page_profil.php">Profil</a>
            </div>
        </div>
    </div>

    <div class="centered-button">
        <button onclick="window.location.href='rech_ajax.html'">Recherche</button>
    </div>
 
    <h1>Gestion des messages :</h1>
   
    <h2>Liste de tous les utilisateurs enregistrés :</h2>
    <table>
        <thead>
            <tr>
                <th>Prénom</th>
                <th>Nom</th>
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
                    <td><?php echo htmlspecialchars($user_data[7]); ?></td>
                    <td>
             
                        <a href="admin_messagerie.php?email=<?php echo htmlspecialchars($user_data[7]); ?>" class="button-red">Voir les messages</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>


    <?php if ($selectedUser) : ?>
        <h2>Messages de <?php echo htmlspecialchars($selectedUser); ?></h2>
        <table>
            <thead>
                <tr>
                    <th>Expéditeur</th>
                    <th>Destinataire</th>
                    <th>Date</th>
                    <th>Message</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
               
                <?php foreach ($userMessages as $index => $message) : ?>
                    <?php $message_data = explode('|', $message); ?>
                    <tr>
                        <td><?php echo htmlspecialchars($message_data[0]); ?></td>
                        <td><?php echo htmlspecialchars($message_data[1]); ?></td>
                        <td><?php echo htmlspecialchars($message_data[2]); ?></td>
                        <td><?php echo htmlspecialchars($message_data[3]); ?></td>
                        <td>
                       
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="message_index" value="<?php echo $index; ?>">
                                <button type="submit" name="delete_message">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

   
    <h1>Signalements de messages :</h1>
    <table>
        <thead>
            <tr>
                <th>Rapporteur</th>
                <th>Expéditeur</th>
                <th>Destinataire</th>
                <th>Date du message</th>
                <th>Message</th>
                <th>Motif</th>
                <th>Détails</th>
                <th>Date du signalement</th>
            </tr>
        </thead>
        <tbody>
            <!-- Boucle pour afficher chaque signalement -->
            <?php foreach ($reports as $index => $report) : ?>
                <?php $report_data = explode('|', $report); ?>
                <tr>
                    <td><?php echo htmlspecialchars($report_data[0]); ?></td>
                    <td><?php echo htmlspecialchars($report_data[1]); ?></td>
                    <td><?php echo htmlspecialchars($report_data[2]); ?></td>
                    <td><?php echo htmlspecialchars($report_data[3]); ?></td>
                    <td><?php echo htmlspecialchars($report_data[4]); ?></td>
                    <td><?php echo htmlspecialchars($report_data[5]); ?></td>
                    <td><?php echo htmlspecialchars($report_data[6]); ?></td>
                    <td><?php echo htmlspecialchars($report_data[7]); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>
