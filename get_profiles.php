<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Engine</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: url('voiture2.jpg') repeat;
        }

        .bhead {
            background: #000;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-title {
            color: #fff;
            margin: 0;
            font-size: 24px;
            text-align: left;
            flex: 1; /* Pour pousser le titre à gauche */
        }

        .header-title a {
            color: red;
            text-decoration: none;
            font-size: 32px;
        }

        .header-title a:hover {
            text-decoration: underline;
        }

        .header-buttons {
            flex: 1; /* Pour pousser les boutons à droite */
            display: flex;
            justify-content: flex-end;
        }

        .bhead button {
            background: red;
            color: #fff;
            padding: 15px 30px; /* Agrandi le bouton */
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
            margin-left: 10px; /* Vous pouvez ajuster ceci pour plus d'espace entre les boutons si nécessaire */
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
            width: 45%; /* 45% pour deux profils par ligne */
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

        .profile-button:hover {
            background-color: #c40000;
        }
    </style>
</head>
<body>
    <div class="bhead">
        <h1 class="header-title"><a href="index.html">Cardate</a></h1>
        <div class="header-buttons">
            <button onclick="window.location.href='rech_ajax.html'">Retour au moteur de recherche</button>
        </div>
    </div>

    <?php
    $filename = 'utilisateurs.txt';
    $lines = file($filename, FILE_IGNORE_NEW_LINES);
    $lines = array_reverse($lines);
    echo "<div id='profiles'>";
    $count = 0;
    foreach ($lines as $line) {
        $user_data = explode(',', $line);
        echo "<div class='profile'>";
        echo "<img src='images/" . $user_data[9] . "' alt='Photo de profil'>";
        echo "<h2>" . $user_data[0] . " " . $user_data[1] . "</h2>";
        echo "<p><strong>Date de naissance:</strong> " . $user_data[2] . "</p>";
        echo "<p><strong>Sexe:</strong> " . $user_data[3] . "</p>";
        echo "<p><strong>Description physique:</strong> " . $user_data[4] . "</p>";
        echo "<p><strong>Statut relationnel:</strong> " . $user_data[5] . "</p>";
        echo "<p><strong>Ville:</strong> " . $user_data[6] . "</p>";
        echo "<a href='detail.php?user_id=" . $user_data[7] . "' class='profile-button'>Voir le détail</a>";
        echo "</div>";
        $count++;
        if ($count >= 10) {
            break;
        }
    }
    echo "</div>";
    ?>
</body>
</html>
