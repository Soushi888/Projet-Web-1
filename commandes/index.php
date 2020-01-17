<!-- Afficher Commandes -->
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
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1>Liste des commandes</h1>
    <!-- <h2>Utilisateur : <?= $_SESSION["identifiant_utilisateur"] ?></h2> -->

    <form id="recherche" action="" method="post">
        <label>ID, client, date ou produit</label>
        <input type="text" name="recherche" value="<?= $recherche ?>" placeholder="nom ou prénom du client">
        <input type="submit" value="Recherchez">
    </form>

    <table>
        <tr>
            <th>Numéro de commande</th>
            <th>Nom du client</th>
            <th>Date</th>
            <th>Produits commandés</th>
            <th>Prix</th>
            <th>Quantités</th>
            <th>Total HT</th>
            <th>Total TTC</th>
            <th>Adresse</th>
            <th>Commentaires</th>
        </tr>
        <?php foreach ($liste as $row) :
        ?>
            <tr>
                <td style="text-align: center;"><?= $row["commande_id"] ?></td>
                <td><?= $row["commande_client"] ?></td>
                <td><?= $row["commande_date"] ?></td>
                <td><?= implode("<br>", $row["commande_produit"]) ?></td>
                <td style="text-align: center;"><?= implode("<br>", $row["commande_produit_quantite"]) ?></td>
                <td style="text-align: center;"><?= implode("<br>", $row["commande_produit_prix"]) ?></td>
                <td></td>
                <td></td>
                <td><?= $row["commande_adresse"] ?></td>
                <td><?= $row["commande_commentaires"] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>