<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
//déterminer les fichiers qui sont utilisés
$filename = 'utilisateurs.txt';
$visits_file = 'visites.txt';
//verifier si les fichiers existent
if (!file_exists($filename)) {
    die("Le fichier des utilisateurs n'existe pas.");
}
if (!file_exists($visits_file)) {
    die("Le fichier des visites n'existe pas.");
}
//définir les variables
$user_email = $_SESSION['email'];
$users = file($filename, FILE_IGNORE_NEW_LINES);
$visits = file($visits_file, FILE_IGNORE_NEW_LINES);

$user_details = [];
//récuperer les informations de l'utilisateur
foreach ($users as $line) {
    $user_data = explode(',', $line);
    $user_details[$user_data[7]] = [
        'name' => $user_data[0] . ' ' . $user_data[1],
        'email' => $user_data[7],
        'photo' => $user_data[9]
    ];
}

$profile_visits = [];
//récuperer les informations des visites
foreach ($visits as $visit) {
    list($visitor_email, $visited_email, $timestamp) = explode(',', $visit);
    if ($visited_email == $user_email) {
        $profile_visits[] = [
            'visitor_email' => $visitor_email,
            'timestamp' => $timestamp,
            'visitor_name' => $user_details[$visitor_email]['name'] ?? 'Utilisateur inconnu',
            'visitor_photo' => $user_details[$visitor_email]['photo'] ?? 'default.jpg'
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visites de Profil</title>
    <style>
        /*styles CSS*/
        body {
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
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
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

        .visit {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .visit img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 15px;
        }

        .visit-details {
            flex: 1;
            text-align: left;
        }

        .visit-details p {
            margin: 0;
            color: #666;
        }

        .visit-details p.name {
            color: #333;
            font-weight: bold;
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
            margin-left: 10px;
        }

        .profile-button:hover {
            background-color: #0000c4;
        }
    </style>
</head>
<body>
    <div class="bhead">
        <h1 class="header-title"><a href="index.html" style="color: white; text-decoration: none;">Cardate</a></h1>
        <!--déterminer les boutons de redirections-->
        <div class="header-buttons">
            <button onclick="window.location.href='modifier_profil.php'">Modifier le profil</button>
            <button onclick="window.location.href='dern_prof.php'">Consulter les profils</button>
            <!--si l'utilisateur a un abonnement, des boutons supplementaires sont affichés-->
            <?php if (isset($_SESSION['abonnement']) && ($_SESSION['abonnement'] == 'premium' || $_SESSION['abonnement'] == 'essai')) { ?>
                <button onclick="window.location.href='boite_messagerie.php'">Messagerie</button>
            <?php } ?>
            <button onclick="window.location.href='offre_abo.html'">Offres d'abonnement</button>
            <?php
            //si l'utilisateur est un admin
            if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']) {
                echo '<button onclick="window.location.href=\'admin_dashboard.php\'">Admin</button>';
            }
            ?>
        </div>
    </div>
    <div class="content">
        <div class="white-block">
            <h2 class="page-title">Visites de votre profil</h2>
            <?php if (empty($profile_visits)): ?>
                <!-- Si les données de visites du profil de l'utilisateur sont vides-->
                <p>Personne n'a encore visité votre profil.</p>
            <?php else: ?>
                <?php foreach ($profile_visits as $visit): ?>
                    <!--Afficher par qui il a été visité sinon -->
                    <div class="visit">
                        <!-- Affichage des détails du visiteur -->
                        <img src="images/<?= htmlspecialchars($visit['visitor_photo']) ?>" alt="Photo de profil">
                        <div class="visit-details">
                            <p class="name"><?= htmlspecialchars($visit['visitor_name']) ?></p>
                            <p><?= htmlspecialchars($visit['timestamp']) ?></p>
                        </div>
                        <a href="detail.php?user_id=<?= htmlspecialchars($visit['visitor_email']) ?>" class="profile-button">Voir le profil</a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
