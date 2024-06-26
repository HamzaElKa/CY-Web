<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$blocked_file = 'utilisateurs_bloques.txt';
if (!file_exists($blocked_file)) {
    // Si le fichier des utilisateurs bloqués n'existe pas, le créer
    file_put_contents($blocked_file, "");
}
$blocked_users = file($blocked_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Utilisateurs Bloqués</title>
    <style>
        /*styles CSS*/
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: url('voiture2.jpg') repeat;
        }

        .blocked-user {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 10px;
        }

        .blocked-user span {
            font-size: 18px;
            color: blue;
            /* Utilisateurs bloqués en bleu */
        }

        .unblock-button {
            background: red;
            /* Bouton de déblocage en rouge */
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .unblock-button:hover {
            background-color: #c40000;
        }

        h1 {
            color: black;
            /* Titre en noir */
        }
    </style>
    <script>
        // Fonction pour débloquer un utilisateur
        function unblockUser(userId) {
            // Crée une nouvelle requête HTTP
            var xhttp = new XMLHttpRequest();
            // Initialise la requête POST vers unblock_user.php
            xhttp.open("POST", "unblock_user.php", true);
            // Définit le type de contenu de la requête
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            // Fonction de rappel pour gérer la réponse de la requête
            xhttp.onreadystatechange = function () {
                // Si la requête est terminée
                if (this.readyState == 4) {
                    // Si la réponse est OK
                    if (this.status == 200) {
                        alert(this.responseText);
                        location.reload(); // Recharge la page
                    } else {
                        alert("Erreur lors du déblocage de l'utilisateur.");
                    }
                }
            };
            // Envoie la requête avec l'identifiant de l'utilisateur
            xhttp.send("user_id=" + userId);
        }
    </script>
</head>
<body>
    <h1>Utilisateurs Bloqués</h1>
    <div id="blockedUsersList">
        <?php
        //l'affichage des utilisateurs bloqués
        if (empty($blocked_users)) {
            echo "<p>Aucun utilisateur bloqué.</p>";
        } else {
            foreach ($blocked_users as $user_id) {
                echo "<div class='blocked-user'>";
                echo "<span>User ID: " . htmlspecialchars($user_id) . "</span>";
                echo "<button class='unblock-button' onclick='unblockUser(\"" . htmlspecialchars($user_id) . "\")'>Débloquer</button>";
                echo "</div>";
            }
        }
        ?>
    </div>
</body>

</html>