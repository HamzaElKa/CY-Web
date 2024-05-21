<?php
session_start();
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("Location: index.html");
    exit();
}

$usersFile = 'utilisateurs.txt';
$messagesFile = 'messages.txt';
$reportsFile = 'signalements.txt';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete_message'])) {
        $messageIndex = $_POST['message_index'];

        $messages = file($messagesFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (isset($messages[$messageIndex])) {
            unset($messages[$messageIndex]);
            file_put_contents($messagesFile, implode(PHP_EOL, $messages) . PHP_EOL);
            echo "Message supprimé avec succès.";
        }
    }
}

$users = file($usersFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$selectedUser = isset($_GET['email']) ? $_GET['email'] : null;
$userMessages = [];

if ($selectedUser) {
    $messages = file($messagesFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($messages as $message) {
        $message_data = explode('|', $message);
        if ($message_data[0] == $selectedUser || $message_data[1] == $selectedUser) {
            $userMessages[] = $message;
        }
    }
}

$reports = file($reportsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des messages</title>
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
    <h1>Gestion des messages</h1>
    <h2>Liste de tous les utilisateurs enregistrés</h2>
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
                    <td><?php echo $user_data[0]; ?></td>
                    <td><?php echo $user_data[1]; ?></td>
                    <td><?php echo $user_data[7]; ?></td>
                    <td>
                        <a href="admin_messagerie.php?email=<?php echo $user_data[7]; ?>">Voir les messages</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php if ($selectedUser) : ?>
        <h2>Messages de <?php echo $selectedUser; ?></h2>
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
                        <td><?php echo $message_data[0]; ?></td>
                        <td><?php echo $message_data[1]; ?></td>
                        <td><?php echo $message_data[2]; ?></td>
                        <td><?php echo $message_data[3]; ?></td>
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
            </tr>
        </thead>
        <tbody>
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