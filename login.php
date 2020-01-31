<?php
require_once("inc/connectDB.php");
require_once("inc/sql.php");

if (isset($_POST['envoi'])) {

    $email = trim(strtolower($_POST['email'])); // Trim et met tout les caractères en minuscule
    $mot_de_passe = trim($_POST['mdp']);


    if (controlerUtilisateur($conn, $email, $mot_de_passe) === 1) {
        session_start();
        $_SESSION['utilisateur'] = LireUtilisateur($conn, $email);
        header("Location: commandes/index.php");
    } else {
        $erreur = "<p class='erreur'>email ou mot de passe incorrect.</p>";
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Authentification">
    <title>Music Store&trade;</title>
    <link rel="stylesheet" href="assets/css/main.css">
</head>

<body id="login">
    <header>
        <img src="assets/img/logo.png" alt="Logo de MusicStore" class="logo">
        <h1 class="titre">Music Store&trade;</h1>
    </header>
    <main>
        <p>Veuillez vous connecter pour pouvoir accéder à la plateforme : </p>
        <form id="authentification" action="" method="post">
            <fieldset id="Authentification">
                <legend>Authentification</legend>
                <div><label for="email">Adresse courriel : </label>
                    <input type="text" id="email" name="email" value="" required></div><br>
                <div><label>Mot de passe : </label>
                    <input type="password" id="mpd" name="mdp" value="" required></div><br>
                <input type="submit" name="envoi" value="Se connecter">
            </fieldset>
        </form>
        <?= isset($erreur) ? $erreur : "&nbsp;" ?>
    </main>
</body>

</html>