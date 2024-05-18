<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$blocked_file = 'utilisateurs_bloques.txt';
$blocked_users = file($blocked_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
?>

<!DOCTYPE html>
<html lang='fr'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Liste des Utilisateurs Bloqués</title>
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
            background-color: #c40000;
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
        }
        .blocked-users {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .blocked-users h2 {
            font-size: 24px;
            color: #333;
            margin-bottom: 10px;
        }
        .blocked-user {
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .blocked-user:last-child {
            border-bottom: none;
        }
        .blocked-user span {
            font-size: 18px;
            color: #333;
        }
        .blocked-user button {
            background-color: red;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
            margin-left: 10px;
        }
        .blocked-user button:hover {
            background-color: #c40000;
        }
        .back-button {
            background-color: blue;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            transition: background-color 0.3s;
            display: inline-block;
            margin-top: 20px;
        }
        .back-button:hover {
            background-color: #0000c4;
        }
    </style>
    <script>
        function unblockUser(userId) {
            var xhttp = new XMLHttpRequest();
            xhttp.open("POST", "unblock_user.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    alert(this.responseText);
                    location.reload();
                } else if (this.readyState == 4) {
                    alert("Erreur lors du déblocage de l'utilisateur.");
                }
            };
            xhttp.send("user_id=" + userId);
        }
    </script>
</head>
<body>
    <div class="bhead">
        <h1 class="header-title">Liste des Utilisateurs Bloqués</h1>
    </div>
    <div class="content">
        <div class="blocked-users">
            <h2>Liste des Utilisateurs Bloqués</h2>
            <?php
            if (empty($blocked_users)) {
                echo "<p>Aucun utilisateur bloqué.</p>";
            } else {
                foreach ($blocked_users as $user_id) {
                    echo "<div class='blocked-user'>";
                    echo "<span>User ID: " . htmlspecialchars($user_id) . "</span>";
                    echo "<button onclick='unblockUser(\"" . htmlspecialchars($user_id) . "\")'>Débloquer</button>";
                    echo "</div>";
                }
            }
            ?>
            <a href="dern_prof.php" class="back-button">Retour</a>
        </div>
    </div>
</body>
</html>