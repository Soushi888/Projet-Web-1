<?php
require_once("../inc/connectDB.php");
require_once("../inc/sql.php");

$recherche = isset($_POST['recherche']) ? trim($_POST['recherche']) : "";

$liste = listerCommandes($conn, $recherche);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Listes de commandes</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <h1>Liste des commandes</h1>

    <form id="recherche" action="" method="post">
        <fieldset>
            <label>Recherche par ID commande, nom client ou état commande : </label>
            <input type="text" name="recherche" value="<?= $recherche ?>" placeholder="ID, nom ou état">
            <input type="submit" value="Recherchez">
        </fieldset>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Nom du client</th>
            <th>Date</th>
            <th>Produits</th>
            <th>Prix</th>
            <th>Quant.</th>
            <th>Total HT</th>
            <th>Total TTC</th>
            <th>Adresse</th>
            <th>Commentaires</th>
            <th>État</th>
            <th>Actions</th>
        </tr>

        <?php foreach ($liste as $row) :
        ?>
            <tr>
                <td style="text-align: center;"><?= $row["commande_id"] ?></td>
                <td><?= $row["commande_client"] ?></td>
                <td><?= $row["commande_date"] ?></td>
                <td><?= implode("<br>", $row["commande_produit"]) ?></td>
                <td style="text-align: center;"><?= implode("<br>", $row["commande_produit_prix"]) ?></td>
                <td style="text-align: center;"><?= implode("<br>", $row["commande_produit_quantite"]) ?></td>
                <td><?= $row["commande_total_ht"] ?></td>
                <td><?= $row["commande_total_ttc"] ?></td>
                <td><?= $row["commande_adresse"] ?></td>
                <td><?= $row["commande_commentaires"] ?></td>
                <td><?= $row["commande_etat"] ?></td>
                <td><a href="#">modifier</a> <a href="#">supprimer</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>