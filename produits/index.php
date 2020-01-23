<?php
require_once("../inc/connectDB.php");
require_once("../inc/sql.php");

session_start();

$recherche = isset($_POST['recherche']) ? trim($_POST['recherche']) : "";

$liste = listerProduits($conn, $recherche);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Catalogue de ventes</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <h1>Catalogue du vendeur</h1>
    <h2>Utilisateur : <pre><?= $_SESSION['utilisateur']["utilisateur_nom"] . ", " . $_SESSION['utilisateur']["utilisateur_prenom"] ?></pre></h2>

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

    <form id="recherche" action="" method="post">
        <fieldset>
            <label>Recherche produit : </label>
            <input type="text" name="recherche" value="<?= $recherche ?>">
            <input type="submit" value="Recherchez">
            <button class="ajout"><a href="ajout.php">Ajouter</a></button>
        </fieldset>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Descriprion</th>
            <th>Prix</th>
            <th>Quant.</th>
            <th>Catégorie</th>
            <th>Actions</th>
        </tr>

        <?php foreach ($liste as $row) :
        ?>
            <tr>
                <td style="text-align: center;"><?= $row["produit_id"] ?></td>
                <td><?= $row["produit_nom"] ?></td>
                <td><?= $row["produit_description"] ?></td>
                <td><?= $row["produit_prix"] ?> $</td>
                <td><?= $row["produit_quantite"] ?></td>
                <td><?= $row["categorie_nom"] ?></td>
                <td><a href="#">modifier</a> <a href="#">supprimer</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>