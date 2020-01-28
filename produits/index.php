<?php
require_once("../inc/connectDB.php");
require_once("../inc/sql.php");
require_once("../inc/connectSession.php");

$recherche = isset($_POST['recherche']) ? trim($_POST['recherche']) : "";

$liste = listerProduits($conn, $recherche);

if (isset($_POST["confirme"])) :
    if ($_POST["confirme"] == "OUI") :
        SupprimerProduit($conn, $_SESSION["suppression"]["produit_id"]);
        unset($_SESSION["suppression"]);
        header("Location: index.php");
    elseif ($_POST["confirme"] == "NON") :
        echo "<p class='erreur'>Suppression non effectuée !</p>";
        unset($_SESSION["suppression"]);
    endif;
endif;

if (isset($_POST["modifier"])) :
    $_SESSION["modification"] = LireProduit($conn, $_POST["modifier"]);
    header("Location: modification.php");
endif;
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
        <p class='erreur'>Accès refusé, vous devez être gestionnaire ou administrateur pour gérer les produits.</p><br>
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

    <p><i>* Seuls les clients qui n'ont pas encore commandé peuvent être supprimé.<br><span class="margin_left">Veuillez supprimer les commandes associées à un produit pour pouvoir le supprimer.</span></i></p>

    <table>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Descriprion</th>
            <th>Prix</th>
            <th>Quant.</th>
            <th>Catégorie</th>
            <th>Nbr de fois commandé</th>
            <th>Actions</th>
        </tr>

        <?php foreach ($liste as $row) : ?>
            <tr>
                <td class="txtcenter"><?= $row["produit_id"] ?></td>
                <td><?= $row["produit_nom"] ?></td>
                <td><?= $row["produit_description"] ?></td>
                <td><?= $row["produit_prix"] ?> $</td>
                <td class="txtcenter"><?= $row["produit_quantite"] ?></td>
                <td><?= $row["categorie_nom"] ?></td>
                <td class="txtcenter"><?= $row["nbr_commandes"] ?></td>
                <td><?php if ($row["nbr_commandes"] == 0) : ?>
                        <form action="" method="post">
                            <input type="hidden" name="supprimer" value="<?= $row["produit_id"] ?>">
                            <input type="submit" value="Supprimer">
                        </form>
                    <?php endif; ?>

                    <form action="" method="post">
                        <input type="hidden" name="modifier" value="<?= $row["produit_id"] ?>">
                        <input type="submit" value="Modifier">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <?php if (isset($_POST["supprimer"])) :
        $_SESSION["suppression"] = LireProduit($conn, $_POST["supprimer"]); ?>
        <form action="" method="post">
            <h2>Confirmer la suppression du produit numéro <?= $_SESSION["suppression"]["produit_id"] . " - " . $_SESSION["suppression"]["produit_nom"] ?> ?</h2>
            <input type="submit" name="confirme" value="OUI">
            <input type="submit" name="confirme" value="NON">
        </form>
    <?php endif; ?>
</body>

</html>