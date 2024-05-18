<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: page_connexion.html");
    exit();
}

$recipient = isset($_GET['recipient']) ? $_GET['recipient'] : '';

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boîte de messagerie</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
        }
        .bhead {
            background-color: black;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header-title {
            color: white;
            margin: 0;
        }
        .header-buttons {
            display: flex;
        }
        .bhead button {
            background-color: red;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-left: 10px;
        }
        .bhead button:hover {
            background-color: darkred;
        }
        .content {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 20px;
            height: 100vh;
            background-color: #e5ddd5;
            background-image: url('voiture2.jpg');
        }
        .user-list {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.2);
            max-width: 250px;
            width: 100%;
            margin-right: 20px;
        }
        .user-list ul {
            list-style: none;
            padding: 0;
        }
        .user-list li {
            margin: 10px 0;
        }
        .user-list a {
            text-decoration: none;
            color: #007bff;
        }
        .user-list a:hover {
            text-decoration: underline;
        }
        .chat-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            width: 100%;
            height: 80vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .messages {
            overflow-y: auto;
            flex-grow: 1;
            padding: 10px;
            border-bottom: 1px solid #ccc;
        }
        .message {
            margin: 10px 0;
        }
        .message.sent {
            text-align: right;
        }
        .message.received {
            text-align: left;
        }
        .message p {
            display: inline-block;
            padding: 10px;
            border-radius: 10px;
            margin: 0;
        }
        .message.sent p {
            background-color: #dcf8c6;
        }
        .message.received p {
            background-color: #fff;
        }
        .message-input {
            display: flex;
            padding: 10px;
        }
        .message-input input[type="text"] {
            flex-grow: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .message-input button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 10px;
        }
        .message-input button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="bhead">
        <h1 class="header-title"><a href="index.html" style="color: white; text-decoration: none;">Cardate</a></h1>
        <div class="header-buttons">
            <button onclick="window.location.href='page_profil.php'">Mon profil</button>
            <button onclick="window.location.href='logout.php'">Se déconnecter</button>
        </div>
    </div>
    <div class="content">
        <div class="user-list">
            <h2>Utilisateurs</h2>
            <ul>
                <?php
                $filename = 'utilisateurs.txt';
                if (file_exists($filename)) {
                    $users = file($filename, FILE_IGNORE_NEW_LINES);
                    foreach ($users as $user) {
                        $user_data = explode(',', $user);
                        if ($user_data[7] !== $_SESSION['email']) { // N'affiche pas l'utilisateur connecté
                            echo '<li><a href="boite_messagerie.php?recipient=' . urlencode($user_data[7]) . '">' . htmlspecialchars($user_data[0]) . ' ' . htmlspecialchars($user_data[1]) . '</a></li>';
                        }
                    }
                } else {
                    echo '<li>Aucun utilisateur trouvé.</li>';
                }
                ?>
            </ul>
        </div>
        <div class="chat-container">
            <div class="messages">
                <?php
                if ($recipient) {
                    $filename = 'messages.txt';
                    if (file_exists($filename)) {
                        $messages = file($filename, FILE_IGNORE_NEW_LINES);
                        foreach ($messages as $message) {
                            $message_data = explode('|', $message);
                            if (count($message_data) === 4) {
                                $sender = htmlspecialchars($message_data[0]);
                                $recipient_in_message = htmlspecialchars($message_data[1]);
                                $timestamp = htmlspecialchars($message_data[2]);
                                $content = htmlspecialchars($message_data[3]);

                                if (($sender === $_SESSION['email'] && $recipient_in_message === $recipient) ||
                                    ($sender === $recipient && $recipient_in_message === $_SESSION['email'])) {
                                    $message_class = ($sender === $_SESSION['email']) ? 'sent' : 'received';
                                    echo '<div class="message ' . $message_class . '">';
                                    echo '<p>' . $content . '<br><small>' . $timestamp . '</small></p>';
                                    echo '</div>';
                                }
                            }
                        }
                    } else {
                        echo '<p>Aucun message trouvé.</p>';
                    }
                } else {
                    echo '<p>Veuillez sélectionner un destinataire pour voir les messages.</p>';
                }
                ?>
            </div>
            <?php if ($recipient): ?>
            <form class="message-input" action="envoyer_message.php" method="post">
                <input type="hidden" name="recipient" value="<?php echo htmlspecialchars($recipient); ?>">
                <input type="text" name="message" placeholder="Tapez votre message" required>
                <button type="submit">Envoyer</button>
            </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
