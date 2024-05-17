<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
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
            align-items: center;
            height: 100vh;
            background-image: url('voiture2.jpg');
        }
        .white-block {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 800px;
            width: 100%;
            margin: 20px;
        }
        .hero-section h2 {
            font-size: 24px;
            color: #333;
            margin-bottom: 10px;
        }
        .hero-section h3 {
            font-size: 18px;
            color: #666;
            margin-top: 0;
        }
        .profile-pic {
            max-width: 200px;
            height: auto;
            margin-top: 20px;
        }
        .page-title {
            background-color: red;
            color: white;
            text-align: center;
            padding: 10px 0;
            margin-top: 0;
        }
        .message {
            border-bottom: 1px solid #ccc;
            padding: 10px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .message:last-child {
            border-bottom: none;
        }
        .message p {
            margin: 5px 0;
        }
        .message button {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .message button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="bhead">
    <h1 class="header-title"><a href="index.html">Cardate</a></h1>
        <div class="header-buttons">
            <button onclick="window.location.href='envoyer_mess.php'">Envoyer un message</button>
            <button onclick="window.location.href='page_profil.php'">Mon profil</button>
        </div>
    </div>
    <div class="content">
        <div class="white-block">
            <h1 class="page-title">Messages reçus</h1>
            <?php
            if (isset($_SESSION['email'])) {
                $filename = 'utilisateurs.txt';
                $users = file($filename, FILE_IGNORE_NEW_LINES);
                $user_id = null;

                foreach ($users as $user) {
                    $user_data = explode(',', $user);
                    if ($user_data[7] == $_SESSION['email']) {
                        $user_id = $user_data[7]; // Utilisez l'index correct pour l'ID utilisateur
                        break;
                    }
                }

                if ($user_id !== null) {
                    $messages_file = 'messages_utilisateur_' . $user_id . '.txt';
                    if (file_exists($messages_file)) {
                        $messages = file($messages_file, FILE_IGNORE_NEW_LINES);

                        if (!empty($messages)) {
                            foreach ($messages as $message) {
                                $message_data = explode('|', $message);
                                echo '<div class="message">';
                                echo '<div>';
                                echo '<p><strong>De:</strong> ' . htmlspecialchars($message_data[0]) . '</p>';
                                echo '<p><strong>Date:</strong> ' . htmlspecialchars($message_data[1]) . '</p>';
                                echo '<p><strong>Message:</strong> ' . htmlspecialchars($message_data[2]) . '</p>';
                                echo '</div>';
                                echo '<button onclick="window.location.href=\'envoyer_mess.php?recipient=' . urlencode($message_data[0]) . '\'">Répondre</button>';
                                echo '</div>';
                            }
                        } else {
                            echo '<p>Aucun message trouvé.</p>';
                        }
                    } else {
                        echo '<p>Aucun message trouvé.</p>';
                    }
                } else {
                    echo '<p>Utilisateur non trouvé.</p>';
                }
            } else {
                echo '<p>Vous devez être connecté pour voir vos messages.</p>';
            }
            ?>
        </div>
    </div>
</body>
</html>
