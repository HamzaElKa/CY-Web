<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        h1 {
            text-align: center;
            margin-top: 50px;
        }

        .profile-details {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .profile-details img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            display: block;
            margin: 0 auto 20px;
        }

        .profile-details p {
            font-size: 18px;
            margin: 10px 0;
            text-align: center;
        }

        .message-button {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
        }

        .message-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Details du profil</h1>
    <div class="profile-details">
        <?php
        $user_id = $_GET['user_id'];
        $filename = 'utilisateurs.txt';
        $lines = file($filename, FILE_IGNORE_NEW_LINES);
        foreach ($lines as $line) {
            $user_data = explode(',', $line);
            if ($user_data[7] == $user_id) {
                echo "<img src='images/" . $user_data[9] . "' alt='Photo de profil'>";
                echo "<p><strong>Nom:</strong> " . $user_data[0] . "</p>";
                echo "<p><strong>Prénom:</strong> " . $user_data[1] . "</p>";
                echo "<p><strong>Date de naissance:</strong> " . $user_data[2] . "</p>";
                echo "<p><strong>Sexe:</strong> " . $user_data[3] . "</p>";
                echo "<p><strong>Description physique:</strong> " . $user_data[4] . "</p>";
                echo "<p><strong>Statut relationnel:</strong> " . $user_data[5] . "</p>";
                echo "<p><strong>Ville:</strong> " . $user_data[6] . "</p>";
                echo "<a href='boite_messagerie.php?user_id=" . $user_id . "' class='message-button'>Envoyer un message</a>";
                break;
            }
        }
        ?>
    </div>
</body>
</html>
