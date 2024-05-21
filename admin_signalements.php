<?php
session_start();
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("Location: index.html");
    exit();
}

$reportsFile = 'signalements.txt';
$messagesFile = 'messages.txt';
$reports = file_exists($reportsFile) ? file($reportsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];
$messages = file_exists($messagesFile) ? file($messagesFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_report'])) {
    $reportIndex = $_POST['report_index'];

    if (isset($reports[$reportIndex])) {
        $report_data = explode('|', $reports[$reportIndex]);
        $sender = $report_data[1];
        $recipient = $report_data[2];
        $date = $report_data[3];
        $content = $report_data[4];

        // Remove the message from messages.txt
        foreach ($messages as $index => $message) {
            $message_data = explode('|', $message);
            if ($message_data[0] === $sender && $message_data[1] === $recipient && $message_data[2] === $date && $message_data[3] === $content) {
                unset($messages[$index]);
                break;
            }
        }

        // Save the updated messages to messages.txt
        file_put_contents($messagesFile, implode(PHP_EOL, $messages) . PHP_EOL);

        // Remove the report from signalements.txt
        unset($reports[$reportIndex]);
        file_put_contents($reportsFile, implode(PHP_EOL, $reports) . PHP_EOL);

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