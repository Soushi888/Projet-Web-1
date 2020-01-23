<?php
require_once("../inc/connectDB.php");
require_once("../inc/sql.php");

session_start();

if (isset($_POST["envoi"])) {
    AjouterUtilisateur($conn, $_POST);
    header("Location: index.php");
} ?>

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
        <h2>Utilisateur :
            <pre><?= $_SESSION['utilisateur']["utilisateur_nom"] . ", " . $_SESSION['utilisateur']["utilisateur_prenom"] ?></pre>
        </h2>
    </header>

    <nav id="main_menu">
        <fieldset>
            <legend>Navigation</legend>
            <fieldset>
                <legend>Vendeur</legend>
                <a href="../clients/index.php">Clients</a><a href="../commandes/index.php">Commandes</a>
            </fieldset>
            <fieldset>
                <legend>Gestionnaire</legend>
                <a href="../produits/index.php">Produits</a><a href="../categories/index.php">Catégories</a>
            </fieldset>
            <fieldset>
                <legend>Administrateur</legend>
                <a href="../utilisateurs/index.php">Utilisateurs</a>
            </fieldset>
        </fieldset>
    </nav>

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