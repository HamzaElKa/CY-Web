<?php

session_start();

// Vérifie si l'utilisateur est un administrateur
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    // Redirige l'utilisateur vers la page d'accueil s'il n'est pas administrateur
    header("Location: index.html");
    exit();
}

// Fichiers contenant les signalements et les messages
$reportsFile = 'signalements.txt';
$messagesFile = 'messages.txt';


$reports = file_exists($reportsFile) ? file($reportsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];
$messages = file_exists($messagesFile) ? file($messagesFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_report'])) {
    $reportIndex = $_POST['report_index'];

    if (isset($reports[$reportIndex])) {
        // Décompose les données du signalement
        $report_data = explode('|', $reports[$reportIndex]);
        $sender = $report_data[1];
        $recipient = $report_data[2];
        $date = $report_data[3];
        $content = $report_data[4];

        // Supprime le message correspondant de messages.txt
        foreach ($messages as $index => $message) {
            $message_data = explode('|', $message);
            if ($message_data[0] === $sender && $message_data[1] === $recipient && $message_data[2] === $date && $message_data[3] === $content) {
                unset($messages[$index]);
                break;
            }
        }

        // Enregistre les messages mis à jour dans messages.txt
        file_put_contents($messagesFile, implode(PHP_EOL, $messages) . PHP_EOL);

     
        unset($reports[$reportIndex]);
        file_put_contents($reportsFile, implode(PHP_EOL, $reports) . PHP_EOL);

        // Redirige vers la page des signalements après la suppression
        header("Location: admin_signalements.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signalements de messages</title>
    <style>
        /* ajout des styles pour notre page  */
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

        /* Styles pour les tableaux */
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

        /* Styles pour les boutons */
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

    <h1>Signalements de messages</h1>
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
                <th>Actions</th>
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
                    <td>
                        <!-- Supprimer un signalement-->
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="report_index" value="<?php echo $index; ?>">
                            <button type="submit" name="delete_report">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>