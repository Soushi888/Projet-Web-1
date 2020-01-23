<?php
require_once("inc/connectDB.php");
require_once("inc/sql.php");

if (isset($_POST['envoi'])) {

    $identifiant = trim(strtolower($_POST['identifiant'])); // Trim et met tout les caractères en minuscule
    $mot_de_passe = trim($_POST['mot_de_passe']);


    if (controlerUtilisateur($conn, $identifiant, $mot_de_passe) === 1) {
        session_start();
        $_SESSION['identifiant_utilisateur'] = $identifiant;
        $_SESSION['id_utilisateur'] = lireClientID($conn, $identifiant);
        // Si l'utilisateur est l'admin, redirige vers le dashboard
        if ($_SESSION['identifiant_utilisateur'] == "admin@magasin.com")
            header('Location: admin/index.php');
        // Sinon, redirige vers le catalogue
        else
            header('Location: index.php');
    } else {
        $erreur = "Identifiant ou mot de passe incorrect.";
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Authentification">
    <title>Music Store&trade</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <header>
        <h1>Music Store&trade;</h1>
    </header>
    <main>
        <p>Veuillez vous connecter pour pouvoir accéderà la plateforme : </p>
        <form id="authentification" action="authentification.php" method="post">
            <fieldset>
                <legend>Authentification</legend>
            <label for="identifiant">Adresse courriel : </label>
                        <input type="text" id="identifiant" name="identifiant" value="" required><br>
            <label>Mot de passe : </label>
                        <input type="password" id="mpd" name="mdp" value="" required><br>
                <input type="submit" name="envoi" value="Se connecter">
            </fieldset>
        </form>
        <!-- <p class="erreur"><?= isset($erreur) ? $erreur : "&nbsp;" ?></p> -->
    </main>
</body>

</html>