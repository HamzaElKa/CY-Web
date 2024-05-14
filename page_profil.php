<?php
session_start();
if(isset($_SESSION['email'])) {
    $filename = 'utilisateurs.txt';
    $users = file($filename, FILE_IGNORE_NEW_LINES); 
    foreach ($users as $user) {
        $user_data = explode(',', $user);
        if ($user_data[7] == $_SESSION['email']) {
            ?>
            <!DOCTYPE html>
            <html lang="fr">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Profil</title>
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
                        background-color: red;
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
                </style>
            </head>
            <body>
                <div class="bhead">
                <h1 class="header-title"><a href="index.html">Cardate</a></h1>
                    <div class="header-buttons">
                        <button onclick="window.location.href='modifier_profil.php'">Modifier le profil</button>
                        <button onclick="window.location.href='dern_prof.html'">Consulter les profils</button>
                    </div>
                </div>
                <div class="content">
                    <div class="white-block">
                        <h2 class="page-title">Profil de <?php echo $user_data[0] . " " . $user_data[1]; ?></h2>
                        <h3>Informations de profil</h3>
                        <p><strong>Prénom :</strong> <?php echo $user_data[0]; ?></p>
                        <p><strong>Nom :</strong> <?php echo $user_data[1]; ?></p>
                        <p><strong>Date de naissance :</strong> <?php echo $user_data[2]; ?></p>
                        <p><strong>Sexe :</strong> <?php echo $user_data[3]; ?></p>
                        <p><strong>Description physique :</strong> <?php echo $user_data[4]; ?></p>
                        <p><strong>Statut relationnel :</strong> <?php echo $user_data[5]; ?></p>
                        <p><strong>Ville :</strong> <?php echo $user_data[6]; ?></p>
                        <p><strong>Email :</strong> <?php echo $user_data[7]; ?></p>
                        <?php
                        $profile_pic_path_png = 'images/' . $user_data[7] . '.png';
                        $profile_pic_path_jpg = 'images/' . $user_data[7] . '.jpg';
                        if (file_exists($profile_pic_path_png)) {
                            echo "<img class='profile-pic' src='$profile_pic_path_png' alt='Photo de profil'>";
                        } elseif (file_exists($profile_pic_path_jpg)) {
                            echo "<img class='profile-pic' src='$profile_pic_path_jpg' alt='Photo de profil'>";
                        } else {
                            echo "<p>Aucune photo de profil trouvée.</p>";
                        }
                        
                        ?>
                        <p><a href='logout.php'>Se déconnecter</a></p>
                    </div>
                </div>
            </body>
            </html>
            <?php
            exit();
        }
    }
    echo "Erreur : Utilisateur non trouvé.";
} else {
    header("Location: page_connexion.html");
    exit();
}
?>