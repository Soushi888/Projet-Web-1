<?php
require_once("../inc/connectDB.php");
require_once("../inc/sql.php");
require_once("../inc/connectSession.php");

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
    <h2>
        <pre><?= $_SESSION['utilisateur']["utilisateur_nom"] . ", " . $_SESSION['utilisateur']["utilisateur_prenom"] . " : " . $_SESSION['utilisateur']["utilisateur_type"] ?></pre>
    </h2>

    <?php include("../menu.php");

    if (($_SESSION["utilisateur"]["utilisateur_type"] !== "administrateur") && ($_SESSION["utilisateur"]["utilisateur_type"] !== "gestionnaire")) : ?>
        <p class='erreur'>Accès refusé, vous devez être administrateur pour gérer les utilisateurs.</p><br>
    <?php exit;
    endif; ?>

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