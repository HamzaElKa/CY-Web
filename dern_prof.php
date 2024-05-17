<?php
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['email'])) {
    // Redirige l'utilisateur vers la page de connexion s'il n'est pas connecté
    header("Location: page_de_connexion.php");
    exit; // Assure que le script s'arrête après la redirection
}

// Si l'utilisateur est connecté, affiche la boîte de messagerie
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Derniers Profils</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
        }
        .header {
            background-color: transparent;
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
        .header-buttons button {
            background-color: red;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-left: 10px;
        }
        .header-buttons button:hover {
            background-color: #c40000;
        }
        .profile-container {
            display: flex;
            margin-bottom: 20px;
        }
        .profile-details {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.2);
            flex: 1; 
            margin-right: 20px;
        }
        .profile-details h2 {
            font-size: 20px;
            color: #333;
            margin: 20px;
            margin-bottom: 10px;
        }
        .profile-details p {
            color: #666;
            margin: 0;
            line-height: 1.5;
            padding: 0 20px;
        }
        .profile-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-right: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="header-title"><a href="index.html">Cardate</a></h1>
        <div class="header-buttons">
            <button onclick="window.location.href='logout.php'">Se déconnecter</button>
            <button onclick="window.location.href='page_profil.php'">Mon profil</button>
        </div>
    </div>
    <div id="profiles"></div>
    <script>
       function loadProfiles() {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("profiles").innerHTML = this.responseText;
                }
            };
            xhttp.open("GET", "get_profiles.php", true);
            xhttp.send();
        }
        window.onload = loadProfiles;
    </script>
</body>
</html>
