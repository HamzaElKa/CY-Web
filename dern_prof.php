<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
//definir les fichiers qu'on doit utiliser
$filename = 'utilisateurs.txt';
$blocked_file = 'utilisateurs_bloques.txt';
$visits_file = 'visites.txt';
//verifier l'existence des fichiers
if (!file_exists($filename)) {
    die("Le fichier des utilisateurs n'existe pas.");
}
if (!file_exists($blocked_file)) {
    file_put_contents($blocked_file, "");
}
if (!file_exists($visits_file)) {
    file_put_contents($visits_file, "");
}
//definir les variables
$user_email = $_SESSION['email'];
$blocked_users = [];

// Lire les utilisateurs bloqués par l'utilisateur connecté
$blocked_lines = file($blocked_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
foreach ($blocked_lines as $line) {
    list($blocker, $blocked) = explode(',', $line);
    if ($blocker == $user_email) {
        $blocked_users[] = $blocked;
    }
}

$lines = file($filename, FILE_IGNORE_NEW_LINES);
$lines = array_reverse($lines);
//definir l'abonnement de l'utilisateur
$current_user_subscription = 'basique';
foreach ($lines as $line) {
    $user_data = explode(',', $line);
    if ($user_data[7] == $user_email) {
        $current_user_subscription = $user_data[11];
        break;
    }
}
//fonction pour enregistrer les visites
function recordVisit($visitorEmail, $visitedEmail) {
    global $visits_file;
    $visitRecord = $visitorEmail . ',' . $visitedEmail . ',' . date('Y-m-d H:i:s') . "\n";
    file_put_contents($visits_file, $visitRecord, FILE_APPEND);
}
//si l'utilisateur visite un profil
if (isset($_GET['visited_user_id'])) {
    $visited_user_id = $_GET['visited_user_id'];
    recordVisit($user_email, $visited_user_id); //enregistrer la visite
    header('Location: detail.php?user_id=' . $visited_user_id); //rediriger vers la page de profil de celui souhaité
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profils</title>
    <style>
        /*styles CSS*/
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
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
            flex: 1;
            display: flex;
            justify-content: flex-end;
        }

        .bhead button {
            background: red;
            color: #fff;
            padding: 15px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
            margin-left: 10px;
        }

        .bhead button:hover {
            background-color: #c40000;
        }

        h1 {
            text-align: left;
            margin: 20px;
            color: #fff;
            font-size: 36px;
        }

        #profiles {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            align-items: flex-start;
            padding: 20px;
        }

        .profile {
            width: 45%;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin-bottom: 20px;
        }

        .profile img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin: 0 auto 20px;
            display: block;
        }

        .profile h2 {
            font-size: 18px;
            margin-bottom: 10px;
            color: #333;
        }

        .profile p {
            color: #666;
            margin: 0;
            line-height: 1.5;
        }

        .profile-button {
            background: blue; 
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            transition: background-color 0.3s;
            margin-top: 10px;
            display: inline-block;
        }

        .profile-button:hover {
            background-color: #0000c4;
        }

        .block-button {
            background: red; 
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            transition: background-color 0.3s;
            margin-top: 10px;
            display: inline-block;
        }

        .block-button:hover {
            background-color: #c40000;
        }

        .blocked-users-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: blue;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .blocked-users-btn:hover {
            background-color: #0000c4;
        }

        #blockedUsersModal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        #blockedUsersModal .close-btn {
            background: red;
            color: #fff;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            float: right;
        }

        #blockedUsersModal .close-btn:hover {
            background-color: #c40000;
        }
    </style>
<script>
    // Déclare une fonction pour bloquer un utilisateur en utilisant son identifiant 
    function blockUser(userId) { 
        // Crée une nouvelle instance de XMLHttpRequest pour envoyer une requête HTTP
        var xhttp = new XMLHttpRequest(); 
        // Initialise une requête HTTP POST vers le fichier block_user.php
        xhttp.open("POST", "block_user.php", true); 
        // Définit l'en-tête de la requête pour spécifier le type de contenu
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        // Définit une fonction de rappel (callback) qui sera exécutée à chaque changement d'état de la requête
        xhttp.onreadystatechange = function() {
            // Vérifie si la requête est terminée et que la réponse est OK
            if (this.readyState == 4 && this.status == 200) {
                // Affiche une alerte avec le texte de la réponse reçue
                alert(this.responseText);
                // Recharge la page actuelle pour refléter les changements
                location.reload();
            } else if (this.readyState == 4) {
                // Si la requête est terminée mais que le statut n'est pas 200, affiche un message d'erreur
                alert("Erreur lors du blocage de l'utilisateur.");
            }
        };
        // Envoie la requête avec les données nécessaires 
        xhttp.send("user_id=" + userId);
    }
    // Déclare une fonction pour afficher la liste des utilisateurs bloqués
    function showBlockedUsers() {
        // Redirige le navigateur vers la page get_blocked_users.php
        window.location.href = 'get_blocked_users.php';
    }
    // Déclare une fonction pour fermer une fenêtre modale
    function closeModal() {
        var modal = document.getElementById('blockedUsersModal');
        modal.style.display = 'none';
    }
</script>
</head>
<body>
    <div class="bhead">
        <h1 class="header-title"><a href="index.html">Cardate</a></h1>
        <div class="header-buttons">
            <!-- Mettre le bouton de recherche-->
            <button onclick="window.location.href='rech_ajax.html'">Recherche</button>
        </div>
    </div>
    <div id="profiles">
        <?php
        $count = 0;
        foreach ($lines as $line) {
            $user_data = explode(',', $line);
            $user_id = $user_data[7];
            if ($user_id == $user_email || in_array($user_id, $blocked_users)) {
                continue;
            }
            //afichage des informations des profils
            echo "<div class='profile'>";
            echo "<img src='images/" . htmlspecialchars($user_data[9]) . "' alt='Photo de profil'>";
            echo "<h2>" . htmlspecialchars($user_data[0]) . " " . htmlspecialchars($user_data[1]) . "</h2>";
            echo "<p><strong>Date de naissance:</strong> " . htmlspecialchars($user_data[2]) . "</p>";
            echo "<p><strong>Sexe:</strong> " . htmlspecialchars($user_data[3]) . "</p>";
            echo "<p><strong>Interêt en voitures:</strong> " . htmlspecialchars($user_data[4]) . "</p>";
            echo "<p><strong>Statut relationnel:</strong> " . htmlspecialchars($user_data[5]) . "</p>";
            echo "<p><strong>Ville:</strong> " . htmlspecialchars($user_data[6]) . "</p>";
            echo "<a href='?visited_user_id=" . htmlspecialchars($user_id) . "' class='profile-button'>Voir les détails</a>";
            echo "<button class='block-button' onclick='blockUser(\"" . htmlspecialchars($user_id) . "\")'>Bloquer</button>";
            echo "</div>";
            $count++;
            if (($current_user_subscription == 'basique' || $current_user_subscription == 'a') && $count >= 10) { //si l'utilisateur n'as qu'un abonnement simple
                break;
            }
        }
        ?>
    </div>
    <!-- bouton vers l'affichafe des utilisateurs bloqués-->
    <button class="blocked-users-btn" onclick="showBlockedUsers()">Voir les utilisateurs bloqués</button>
</body>
</html>
