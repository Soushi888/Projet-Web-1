<?php
require_once("../inc/connectDB.php");
require_once("../inc/sql.php");
require_once("../inc/connectSession.php");

$recherche = isset($_POST['recherche']) ? trim($_POST['recherche']) : "";

$liste = ListerCommandes($conn, $recherche);

if (isset($_POST["confirme"])) :
    if ($_POST["confirme"] == "OUI") :
        SupprimerCommande($conn, $_SESSION["suppression"]["commande_id"]);
        unset($_SESSION["suppression"]);
        header("Location: index.php");
    elseif ($_POST["confirme"] == "NON") :
        echo "<p class='erreur'>Suppression non effectuée !</p>";
        unset($_SESSION["suppression"]);
    endif;
endif;

if (isset($_POST["modifier"])) :
    $_SESSION["modification"] = LireCommande($conn, $_POST["modifier"]);
    header("Location: modification.php");
endif;

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Listes de commandes</title>
    <link rel="stylesheet" href="../assets/css/main.css">
</head>

<body>
    <h1>Liste des commandes</h1>
    <h2>
        <pre><?= $_SESSION['utilisateur']["utilisateur_nom"] . ", " . $_SESSION['utilisateur']["utilisateur_prenom"] . " : " . $_SESSION['utilisateur']["utilisateur_type"] ?></pre>
    </h2>

    <?php include("../menu.php"); ?>

    <form id="recherche" action="" method="post">
        <fieldset>
            <label>Recherche par ID commande, nom client ou état commande : </label>
            <input type="text" name="recherche" value="<?= $recherche ?>" placeholder="ID, nom ou état">
            <input type="submit" value="Recherchez">
            <button class="ajout"><a href="ajout.php">Ajouter</a></button>
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
                <td><?= $row["commande_date"] . "<br>" . $row["commande_heure"] ?></td>
                <td><?= implode("<br>", $row["commande_produit"]) ?></td>
                <td style="text-align: center;"><?= implode("<br>", $row["commande_produit_prix"]) ?></td>
                <td style="text-align: center;"><?= implode("<br>", $row["commande_produit_quantite"]) ?></td>
                <td><?= $row["commande_total_ht"] ?></td>
                <td><?= $row["commande_total_ttc"] ?></td>
                <td><?php echo $row["commande_adresse"];
                    echo isset($row["commande_adresse2"]) ? "<br>" . $row["commande_adresse2"] : "";
                    echo "<br>" . $row["commande_adresse_ville"] . ", " . $row["commande_adresse_cp"] .
                        "<br>Québec, Canada" ?></td>
                <td><?= $row["commande_commentaires"] ?></td>
                <td><?= $row["commande_etat"] ?></td>
                <td>
                    <form action="" method="post">
                        <input type="hidden" name="supprimer" value="<?= $row["commande_id"] ?>">
                        <input type="submit" value="Supprimer">
                    </form>
                    <form action="" method="post">
                        <input type="hidden" name="modifier" value="<?= $row["commande_id"] ?>">
                        <input type="submit" value="Modifier">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <?php if (isset($_POST["supprimer"])) :
        $_SESSION["suppression"] = LireCommande($conn, $_POST["supprimer"]); ?>
        <form action="" method="post">
            <h2>Confirmer la suppression de la commande numéro <?= $_SESSION["suppression"]["commande_id"] ?> ?</h2>
            <input type="submit" name="confirme" value="OUI">
            <input type="submit" name="confirme" value="NON">
        </form>
    <?php endif; ?>
</body>

</html>