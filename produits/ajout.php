<?php
require_once("../inc/connectDB.php");
require_once("../inc/sql.php");

$categories = ListerCategories($conn);

if (isset($_POST["envoi"])) {
    AjouterProduit($conn, $_POST);
    header("Location: index.php");
} ?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Ajouter un produit</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <h1>Ajout d'un produit</h1>

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
        <label for="description">Description : </label>
        <textarea name="description" id="description" cols="50" rows="3"></textarea><br>
        <label for="prix">Prix : </label>
        <input type="text" name="prix" required><br>
        <label for="quantite">Quantité : </label>
        <input type="number" name="quantite" required><br>
        <table>
            <?php if (count($categories) > 0) : ?>
                <label>Categorie du produit</label>
                <select name="categorie">
                    <?php foreach ($categories as $row) : ?>
                        <option value="<?= $row["categorie_id"] ?>"><?= $row["categorie_nom"] ?></option>
                    <?php endforeach; ?>
                </select>
        </table>
    <?php else : ?>
        <p class="erreur">Aucune categorie trouvé.</p>
    <?php endif; ?>

    <input type="submit" name="envoi" value="Ajouter !">
    </form>
</body>

</html>