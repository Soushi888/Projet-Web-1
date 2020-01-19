<?php
  
session_start();

// Si L'utilisateur n'est pas administrateur
if ($_SESSION['identifiant_utilisateur'] !== "admin@magasin.com") {
    // redirection vers la page authentification.php
    // pour la saisie de l'identifiant et du mot de passe 
    header('Location: ../index.php'); }
else {
    $_SESSION['type_utilisateur'] = "Administrateur";
}
?>