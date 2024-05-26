<?php
//démarrage de la session.
session_start();
//verifivation de l'état de connexion.
if (isset($_SESSION['email'])) {
    //creation du fichier utilisateur
    $filename = 'utilisateurs.txt';
    //mets les lignes du fichier dans un tableau.
    $users = file($filename, FILE_IGNORE_NEW_LINES);
    //rerche l'utilisateur dans le fichier.
    foreach ($users as $user) {
        $user_data = explode(',', $user);
        //verification de l'eamil.
        if ($user_data[7] == $_SESSION['email']) {
            //recupération du type de l'abonnement.
            $abonnement = $user_data[11];
?>
            <!DOCTYPE html>
            <html lang="fr">

            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Profil</title>
                <style>
                    /*style pour le corps */
                    body {
                        font-family: Arial, sans-serif;
                        margin: 0;
                        padding: 0;
                        background-color: #f2f2f2;
                        background-image: url('voiture2.jpg');
                        background-repeat: repeat;
                    }

                    /*style de l'en-tete */
                    .bhead {
                        background-color: black;
                        padding: 10px 20px;
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        position: fixed;
                        width: 100%;
                        top: 0;
                        z-index: 1000;
                    }

                    /*selecteurs de classe*/
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
                        padding-top: 60px;
                    }

                    .white-block {
                        background-color: white;
                        padding: 20px;
                        border-radius: 10px;
                        box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.2);
                        text-align: center;
                        max-width: 90%;
                        margin: auto;
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
                <!--mise en page et configuration des boutons-->
                <div class="bhead">
                    <h1 class="header-title"><a href="index.html" style="color: white; text-decoration: none;">Cardate</a></h1>
                    <div class="header-buttons">
                        <button onclick="window.location.href='modifier_profil.php'">Modifier le profil</button>
                        <button onclick="window.location.href='dern_prof.php'">Consulter les profils</button>
                        <!--configuration des boutons pouvant s'afficher selon le type d'abonnement-->
                        <?php if ($abonnement == 'premium' || $abonnement == 'essai') { ?>
                            <button onclick="window.location.href='boite_messagerie.php'">Messagerie</button>
                            <button onclick="window.location.href='visites.php'">Visites</button>
                        <?php } ?>
                        <button onclick="window.location.href='offre_abo.html'">Offres d'abonnement</button>
                        <?php
                        //configuration des boutons pouvant s'afficher seulement si vous etes admin.
                        if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']) {
                            echo '<button onclick="window.location.href=\'admin_dashboard.php\'">Admin</button>';
                        }
                        ?>
                    </div>
                </div>
                <div class="content">
                    <!--info de l'utilisateur actuel-->
                    <div class="white-block">
                        <h2 class="page-title">Profil de <?php echo $user_data[0] . " " . $user_data[1]; ?></h2>
                        <h3>Informations de profil</h3>
                        <p><strong>Prénom :</strong> <?php echo $user_data[0]; ?></p>
                        <p><strong>Nom :</strong> <?php echo $user_data[1]; ?></p>
                        <p><strong>Date de naissance :</strong> <?php echo $user_data[2]; ?></p>
                        <p><strong>Sexe :</strong> <?php echo $user_data[3]; ?></p>
                        <p><strong>Interêt en voitures :</strong> <?php echo $user_data[4]; ?></p>
                        <p><strong>Statut relationnel :</strong> <?php echo $user_data[5]; ?></p>
                        <p><strong>Ville :</strong> <?php echo $user_data[6]; ?></p>
                        <p><strong>Email :</strong> <?php echo $user_data[7]; ?></p>
                        <?php
                        $profile_pic_path_png = 'images/' . $user_data[9];
                        $profile_pic_path_jpg = 'images/' . $user_data[9];
                        if (file_exists($profile_pic_path_png)) {
                            echo "<img class='profile-pic' src='$profile_pic_path_png' alt='Photo de profil'>";
                        } elseif (file_exists($profile_pic_path_jpg)) {
                            echo "<img class='profile-pic' src='$profile_pic_path_jpg' alt='Photo de profil'>";
                        } else {
                            echo "<p>Aucune photo de profil trouvée.</p>";
                        }
                        ?>
                        <!--bouton de redirection-->
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