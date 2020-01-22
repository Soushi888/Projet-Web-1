<?php
require_once("../inc/connectDB.php");
require_once("../inc/sql.php");

if (isset($_POST["envoi"])) {
    $_POST["adresse2"] = trim($_POST["adresse2"]);
    if ($_POST["adresse2"] == "") { // Si il n'y a pas d'adresse 2, alors adresse 2 est NULL
        $_POST["adresse2"] = NULL;
    }

    AjouterClient($conn, $_POST);
    header("Location: index.php");
} ?>
   
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Ajouter un client</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <h1>Ajout d'un client</h1>

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

    <form action="" method="post">
        <label for="nom">Nom : </label>
        <input type="text" name="nom" required><br>
        <label for="prenom">Prénom : </label>
        <input type="text" name="prenom" required><br>
        <label for="email">Email : </label>
        <input type="text" name="email" required><br>
        <label for="telephone">Téléphone : </label>
        <input type="text" name="telephone" required><br>
        <label for="adresse">Adresse : </label>
        <input type="text" name="adresse" required><br>        
        <label for="adresse2">Adresse 2 (optionnel) :</label>
        <input type="text" name="adresse2"><br>
        <label for="ville">Ville : </label>
        <input type="text" name="ville" required><br>
        <label for="cp">Code Postal : </label>
        <input type="text" name="cp" required><br>
        <input type="submit" name="envoi" value="Ajouter !">
    </form>
</body>

</html>