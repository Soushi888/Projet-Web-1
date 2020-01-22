<?php 
    require_once("inc/sql.php");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <fieldset id="main_menu">
        <legend>Navigation</legend>
        <fieldset>
            <legend>Vendeur</legend>
            <a href="clients/index.php">Clients</a><a href="commandes/index.php">Commandes</a>
        </fieldset>
        <fieldset>
            <legend>Gestionnaire</legend>
            <a href="produits/index.php">Produits</a><a href="categories/index.php">Catégories</a>
        </fieldset>
        <fieldset>
            <legend>Administrateur</legend>
            <a href="utilisateurs/index.php">Utilisateurs</a>
        </fieldset>
    </fieldset>
</body>
</html>