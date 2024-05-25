<?php
session_start();
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("Location: index.html");
    exit();
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .header-buttons button, .content .white-block button {
            background-color: red;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
        }

        .content {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 80vh; /* Adjust the height as needed */
        }

        .white-block {
            text-align: center;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .white-block h2 {
            font-size: 32px;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
        }

        .button-group {
            display: flex;
            justify-content: center;
            gap: 10px; /* Adjust the gap as needed */
        }
    </style>
</head>

<body>
    <div class="bhead">
        <a href="index.html">
            <h1 class="header-title">Cardate</h1>
        </a>
        <div class="header-buttons">
            <a href="logout.php"><button type="button">Déconnexion</button></a>
            <a href="page_profil.php"><button type="button">Profil</button></a>
        </div>
    </div>
    <div class="content">
        <div class="white-block">
            <h2>Admin Dashboard</h2>
            <div class="button-group">
                <a href="admin_utilisateurs.php"><button type="button">Gérer les utilisateurs</button></a>
                <a href="admin_messagerie.php"><button type="button">Voir les messages</button></a>
                <a href="admin_signalements.php"><button type="button">Gérer les messages signalés</button></a>
            </div>
        </div>
    </div>
</body>

</html>

