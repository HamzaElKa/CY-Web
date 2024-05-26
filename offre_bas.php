<?php
session_start();

//fonction pour modifier notre base de données qui est un fichier texte.
function chercherEtModifierTxt($nomFichier, $chaineRecherchee, $chaineAjoutee)
{
    // creation d'un fichier temporaire.
    $nomFichierTemporaire = 'temp_' . $nomFichier;
    $fichierEntree = fopen($nomFichier, 'r');
    $fichierSortie = fopen($nomFichierTemporaire, 'w');

    if ($fichierEntree && $fichierSortie) {
        while (($ligne = fgets($fichierEntree)) !== FALSE) {
            //verification de l'emplacement de la chaine de caractère et recuperation de la ligne où elle se trouve.
            if (strpos($ligne, $chaineRecherchee) !== FALSE) {
                //conversion de la ligne en tableau 
                $sous_chaines = explode(',', $ligne);

                // suppression du dernier elements du tableau.
                array_splice($sous_chaines, -1);

                // conversion du nouveau tableau en chain de caractère séparée par des virgules.
                $ligne = implode(',', $sous_chaines) . ',' . $chaineAjoutee . PHP_EOL;
            }
            fwrite($fichierSortie, $ligne);
        }

        fclose($fichierEntree);
        fclose($fichierSortie);
        if (rename($nomFichierTemporaire, $nomFichier)) {
            header("Location: page_profil.php");
        } else {
            echo "Erreur lors du renommage du fichier.";
        }
    } else {
        echo "Erreur lors de l'ouverture du fichier.";
    }
}

if (isset($_SESSION['email'])) {
    $prenom = $_SESSION['firstname'] ?? '';
    $nom = $_SESSION['name'] ?? '';
    $dateNaissance = $_SESSION['birthdate'] ?? '';
    $genre = $_SESSION['gender'] ?? '';
    $descriptionPhysique = $_SESSION['physical_description'] ?? '';
    $statutRelation = $_SESSION['relationship_status'] ?? '';
    $ville = $_SESSION['city'] ?? '';
    $email = $_SESSION['email'] ?? '';
    $motDePasse = $_SESSION['password'] ?? '';
    $nouveauNomFichier = $_SESSION['newFileName'] ?? '';
    $typeAbonnement = "basique";
    $ligne = $prenom . ',' . $nom . ',' . $dateNaissance . ',' . $genre . ',' . $descriptionPhysique . ',' . $statutRelation . ',' . $ville . ',' . $email . ',' . $motDePasse . ',' . $nouveauNomFichier . ',' . $typeAbonnement . PHP_EOL;
    $nomFichierTxt = "utilisateurs.txt";
    $chaineRecherchee = $email;
    $chaineAjoutee = $typeAbonnement;
    chercherEtModifierTxt($nomFichierTxt, $chaineRecherchee, $chaineAjoutee);
} else {
    echo "Certaines informations de l'utilisateur sont manquantes dans la session.";
}