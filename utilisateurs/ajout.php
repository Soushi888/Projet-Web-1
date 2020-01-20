<?php
require_once("../inc/connectDB.php");
require_once("../inc/sql.php");

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
    </header>

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