<?php
require_once("../inc/connectDB.php");
require_once("../inc/sql.php");

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
    <h1>Catalogue de ventes</h1>

    <form id="recherche" action="" method="post">
        <fieldset>
            <label>Recherche produit : </label>
            <input type="text" name="recherche" value="<?= $recherche ?>">
            <input type="submit" value="Recherchez">
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
            <th>Commander</th>
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
                <td>
                    <input type="number" max="<?= $row["produit_quantite"] ?>">
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>