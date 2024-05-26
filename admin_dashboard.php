<?php

session_start();

// Vérifie si l'utilisateur est un administrateur
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    // Redirige l'utilisateur vers la page d'accueil s'il n'est pas administrateur
    header("Location: index.html");
    exit();
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style_admin_dash.css">
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
    <!-- Les 3 boutton pour les redirections-->
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

