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
    <title>Authentification</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <header>
        <h1>Authentification</h1>
    </header>
    <main>
        <form id="authentification" action="authentification.php" method="post">
            <p><label>Adresse courriel
                    <input type="text" name="identifiant" value="" required></label></p>
            <p><label>Mot de passe
                    <input type="password" name="mot_de_passe" value="" required></label></p>
            <input type="submit" name="envoi" value="Envoyez">
        </form>
        <p class="erreur"><?= isset($erreur) ? $erreur : "&nbsp;" ?></p>
        <p class="menu">
            <a href="creationCompteClient.php">Créer un compte</a><br>
            <a href="index.php">Retourner au catalogue</a>
        </p>
    </main>
</body>

</html>