<?php
require_once("../inc/connectDB.php");
require_once("../inc/sql.php");
require_once("../inc/connectSession.php");

if (isset($_POST["envoi"])) {
    AjouterUtilisateur($conn, $_POST);
    header("Location: index.php");
}

?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Ajouter un utilisateur</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <header>
        <h1>Ajout d'un utilisateur</h1>
        <h2>
            <pre><?= $_SESSION['utilisateur']["utilisateur_nom"] . ", " . $_SESSION['utilisateur']["utilisateur_prenom"] . " : " . $_SESSION['utilisateur']["utilisateur_type"] ?></pre>
        </h2>
    </header>

    <nav id="main_menu">
        <fieldset>
            <legend>Navigation</legend>
            <fieldset>
                <legend>Vendeur</legend>
                <a href="../clients/index.php">Clients</a><a href="../commandes/index.php">Commandes</a>
            </fieldset>
            <?php if ($_SESSION['utilisateur']["utilisateur_type"] == "gestionnaire" || $_SESSION['utilisateur']["utilisateur_type"] == "administrateur") : ?>
                <fieldset>
                    <legend>Gestionnaire</legend>
                    <a href="../produits/index.php">Produits</a><a href="../categories/index.php">Catégories</a>
                </fieldset>
            <?php endif; ?>
            <?php if ($_SESSION['utilisateur']["utilisateur_type"] == "administrateur") : ?>
                <fieldset>
                    <legend>Administrateur</legend>
                    <a href="../utilisateurs/index.php">Utilisateurs</a>
                </fieldset>
            <?php endif; ?>
            <button><a href="../deconnexion.php">Déconnexion</a></button>
        </fieldset>
    </nav>

    <?php if ($_SESSION["utilisateur"]["utilisateur_type"] !== "administrateur") : ?>
        <p class='erreur'>Accès refusé, vous devez être administrateur pour gérer les utilisateurs.</p><br>
    <?php exit;
    endif; ?>

    <main>
        <form action="" method="post">
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" required><br>
            <label for="prenom">Prenom :</label>
            <input type="text" id="prenom" name="prenom" required><br>
            <label for="email">Email :</label>
            <input type="text" id="email" name="email" required><br>
            <label for="mdp">Mot de passe :</label>
            <input type="password" id="mdp" name="mdp" required><br>
            <label for="confirmation_mdp">Confirmer mot de passe :</label><!-- À valider -->
            <input type="password" id="confirmation_mdp" name="confirmation_mdp" required><br> <!-- À valider -->
            <label for="type">Type :</label>
            <select type="text" id="type" name="type" required>
                <option value="vendeur">Vendeur</option>
                <option value="gestionnaire">Gestionnaire</option>
                <option value="administrateur">Administrateur</option>
            </select><br>
            <input type="submit" id="envoi" value="Ajouter !">
        </form>
    </main>
</body>

</html>