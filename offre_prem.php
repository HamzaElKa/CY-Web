<?php
session_start();

// Fonction pour chercher et modifier le fichier texte
function chercherEtModifierTxt($nomFichier, $chaineRecherchee, $chaineAjoutee) {
    $nomFichierTemporaire = 'temp_' . $nomFichier;
    $fichierEntree = fopen($nomFichier, 'r');
    $fichierSortie = fopen($nomFichierTemporaire, 'w');
    
    if ($fichierEntree && $fichierSortie) {
        while (($ligne = fgets($fichierEntree)) !== FALSE) {
            // Vérifie si la chaîne de recherche est dans la ligne
            if (strpos($ligne, $chaineRecherchee) !== FALSE) {
                // Diviser la ligne en un tableau de sous-chaînes séparées par des virgules
$sous_chaines = explode(',', $ligne);

// Supprimer les deux derniers éléments du tableau
array_splice($sous_chaines, -1);

// Rejoindre les sous-chaînes restantes en une seule chaîne, séparées par des virgules
$ligne = implode(',', $sous_chaines). ',' . $chaineAjoutee.PHP_EOL;

            }
            fwrite($fichierSortie, $ligne);
        }
        
        fclose($fichierEntree);
        fclose($fichierSortie);
        
        // Remplace le fichier original par le fichier modifié
        rename($nomFichierTemporaire, $nomFichier);
        
        echo "Le fichier a été modifié avec succès.";
    } else {
        echo "Erreur lors de l'ouverture du fichier.";
    }
}

if (isset($_SESSION['email'])) {
    $prenom = $_SESSION['firstname'];
    $nom = $_SESSION['name'];
    $dateNaissance = $_SESSION['birthdate'];
    $genre = $_SESSION['gender'];
    $descriptionPhysique = $_SESSION['physical_description'];
    $statutRelation = $_SESSION['relationship_status'];
    $ville = $_SESSION['city'];
    $email = $_SESSION['email'];
    $motDePasse = $_SESSION['password'];
    $nouveauNomFichier = $_SESSION['newFileName'];
    $typeAbonnement = "premium"; // Type d'abonnement récupéré de la session

    $ligne = $prenom . ',' . $nom . ',' . $dateNaissance . ',' . $genre . ',' . $descriptionPhysique . ',' . $statutRelation . ',' . $ville . ',' . $email . ',' . $motDePasse . ',' . $nouveauNomFichier . ',' . $typeAbonnement . PHP_EOL;

    $nomFichierTxt = "utilisateurs.txt"; // Changez ceci avec le nom de votre fichier texte

    // Ajoute la nouvelle ligne au fichier texte
    $fichier = fopen($nomFichierTxt, "a+");
   /* if ($fichier) {
        fwrite($fichier, $ligne);
        fclose($fichier);
    } else {
        echo "Erreur lors de l'ouverture du fichier.";
    }*/

    // Cherche et modifie le fichier texte
    $chaineRecherchee = $email; // On suppose que vous cherchez par email
    $chaineAjoutee = $typeAbonnement; // La chaîne que vous souhaitez ajouter

    chercherEtModifierTxt($nomFichierTxt, $chaineRecherchee, $chaineAjoutee);
} else {
    echo "Certaines informations de l'utilisateur sont manquantes dans la session.";
}
?>