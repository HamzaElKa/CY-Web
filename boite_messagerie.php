<?php
session_start();

// Vérifie si l'utilisateur est connecté, sinon redirige vers la page de connexion
if (!isset($_SESSION['email'])) {
    header("Location: page_connexion.html");
    exit();
}

// Récupère le destinataire depuis l'URL
$recipient = isset($_GET['recipient']) ? $_GET['recipient'] : '';
$recipient_name = '';

// Recherche le nom complet du destinataire dans le fichier utilisateurs.txt
if ($recipient) {
    $filename = 'utilisateurs.txt';
    if (file_exists($filename)) {
        // Lit le fichier ligne par ligne
        $users = file($filename, FILE_IGNORE_NEW_LINES);
        foreach ($users as $user) {
            $user_data = explode(',', $user);
            // Vérifie si l'email correspond au destinataire
            if ($user_data[7] === $recipient) {
                // Utilise htmlspecialchars pour éviter les injections de code HTML
                $recipient_name = htmlspecialchars($user_data[0] . ' ' . $user_data[1]);
                break;
            }
        }
    }
}

// Envoi d'un message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message']) && !isset($_POST['delete'])) {
    $message = trim($_POST['message']);
    if ($message !== '' && $recipient) {
        $filename = 'messages.txt';
        $sender = $_SESSION['email'];
        $timestamp = date('Y-m-d H:i:s');
        // Construit la ligne de message avec l'expéditeur, le destinataire, l'horodatage et le contenu du message
        $message_line = $sender . '|' . $recipient . '|' . $timestamp . '|' . $message;
        // Ajoute la ligne au fichier messages.txt
        file_put_contents($filename, $message_line . PHP_EOL, FILE_APPEND);
        header("Location: boite_messagerie.php?recipient=" . urlencode($recipient));
        exit();
    }
}

// Suppression d'un message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $timestamp_to_delete = $_POST['delete'];
    $filename = 'messages.txt';
    if (file_exists($filename)) {
        // Lit tous les messages du fichier
        $messages = file($filename, FILE_IGNORE_NEW_LINES);
        $updated_messages = [];
        foreach ($messages as $message) {
            $message_data = explode('|', $message);
            if (count($message_data) === 4) {
                $timestamp = $message_data[2];
                // Ajoute à la liste des messages mis à jour ceux qui ne correspondent pas à l'horodatage à supprimer
                if ($timestamp !== $timestamp_to_delete) {
                    $updated_messages[] = $message;
                }
            }
        }
        // Écrit les messages mis à jour dans le fichier
        file_put_contents($filename, implode("\n", $updated_messages));
        header("Location: boite_messagerie.php?recipient=" . urlencode($recipient));
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boîte de messagerie</title>
    <style>
        body {
            /* Styles CSS */
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
            background-image: url('voiture2.jpg');
            background-repeat: repeat;
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
            display: flex;
            align-items: center;
            margin: 10px 0;
        }

        .user-list img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
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

        .chat-header {
            text-align: center;
            padding: 10px 0;
            border-bottom: 1px solid #ccc;
            margin-bottom: 10px;
        }

        .chat-header a {
            text-decoration: none;
            color: #007bff;
            font-size: 18px;
            font-weight: bold;
        }

        .chat-header a:hover {
            text-decoration: underline;
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
            display: flex;
            justify-content: flex-end;
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

        .message .delete-button {
            margin-left: 10px;
            background-color: red;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
            padding: 5px 10px;
        }

        .message .delete-button:hover {
            background-color: darkred;
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

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
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
                // Affiche la liste des utilisateurs
                $filename = 'utilisateurs.txt';
                if (file_exists($filename)) {
                    $users = file($filename, FILE_IGNORE_NEW_LINES);
                    foreach ($users as $user) {
                        $user_data = explode(',', $user);
                        if ($user_data[7] !== $_SESSION['email']) {
                            // Vérifie si l'image de profil existe, sinon utilise une image par défaut
                            $profile_pic = 'images/' . $user_data[9];
                            if (!file_exists($profile_pic)) {
                                $profile_pic = 'images/default.jpg';
                            }
                            echo '<li><img src="' . htmlspecialchars($profile_pic) . '" alt="Photo de profil"><a href="boite_messagerie.php?recipient=' . urlencode($user_data[7]) . '">' . htmlspecialchars($user_data[0]) . ' ' . htmlspecialchars($user_data[1]) . '</a></li>';
                        }
                    }
                } else {
                    echo '<li>Aucun utilisateur trouvé.</li>';
                }
                ?>
            </ul>
        </div>
        <div class="chat-container">
            <?php if ($recipient) : ?>
                <div class="chat-header">
                    <a href="detail.php?user_id=<?php echo urlencode($recipient); ?>"><?php echo $recipient_name; ?></a>
                </div>
            <?php endif; ?>
            <div class="messages">
                <?php
                // Affiche les messages entre l'utilisateur connecté et le destinataire sélectionné
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

                                // Affiche le message seulement s'il est envoyé ou reçu par l'utilisateur connecté
                                if (($sender === $_SESSION['email'] && $recipient_in_message === $recipient) ||
                                    ($sender === $recipient && $recipient_in_message === $_SESSION['email'])
                                ) {
                                    $message_class = ($sender === $_SESSION['email']) ? 'sent' : 'received';
                                    echo '<div class="message ' . $message_class . '">';
                                    echo '<p>' . $content . '<br><small>' . $timestamp . '</small></p>';
                                    if ($sender === $_SESSION['email']) {
                                        echo '<form method="post" action="boite_messagerie.php?recipient=' . urlencode($recipient) . '" style="display:inline;"><button type="submit" name="delete" value="' . $timestamp . '" class="delete-button">Supprimer</button></form>';
                                    }
                                    echo '<button onclick="openModal(\'' . addslashes($sender) . '\', \'' . addslashes($recipient_in_message) . '\', \'' . addslashes($timestamp) . '\', \'' . addslashes($content) . '\')">Signaler</button>';
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
            <?php if ($recipient) : ?>
                <form class="message-input" action="boite_messagerie.php?recipient=<?php echo urlencode($recipient); ?>" method="post">
                    <input type="hidden" name="recipient" value="<?php echo htmlspecialchars($recipient); ?>">
                    <input type="text" name="message" placeholder="Tapez votre message" required>
                    <button type="submit">Envoyer</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
    <div id="reportModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Signaler un message</h2>
            <form id="reportForm" method="post" action="signaler_message.php">
                <input type="hidden" name="sender" id="modalSender">
                <input type="hidden" name="recipient" id="modalRecipient">
                <input type="hidden" name="date" id="modalDate">
                <input type="hidden" name="message" id="modalMessage">
                <label for="reason">Motif de signalement :</label>
                <select name="reason" id="reason" required>
                    <option value="Harcèlement">Harcèlement</option>
                    <option value="spam">Spam</option>
                    <option value="inappropriate">Message inapproprié</option>
                </select>
                <label for="details">Détails :</label>
                <textarea name="details" id="details" rows="4" required></textarea>
                <button type="submit" name="report_message">Signaler</button>
            </form>
        </div>
    </div>

    <script>
        // Ouvre le modal de signalement de message
        function openModal(sender, recipient, date, message) {
            document.getElementById('modalSender').value = sender;
            document.getElementById('modalRecipient').value = recipient;
            document.getElementById('modalDate').value = date;
            document.getElementById('modalMessage').value = message;
            document.getElementById('reportModal').style.display = "block";
        }

        // Ferme le modal de signalement de message
        function closeModal() {
            document.getElementById('reportModal').style.display = "none";
        }

        // Ferme le modal si l'utilisateur clique en dehors
        window.onclick = function(event) {
            if (event.target == document.getElementById('reportModal')) {
                closeModal();
            }
        }
    </script>
</body>

</html>
