<?php
require_once("../inc/connectDB.php");
require_once("../inc/sql.php");
require_once("../inc/connectSession.php");

$titre = "Catalogue des produits";

$recherche = isset($_GET['recherche']) ? trim($_GET['recherche']) : "";

// Pagination
$nombreProduits = NombreProduits($conn);
$nombrePages = ceil($nombreProduits / 10);

$pageActuelle = 1;

if (isset($_GET['page'])) {
    $pageActuelle = $_GET['page'];
} elseif ($pageActuelle > $nombrePages) {
    $pageActuelle = $nombrePages;
} else {
    $pageActuelle = 1;
}

$offset = ($pageActuelle - 1) * 10;

$liste = listerProduits($conn, $recherche, $offset, 10);


// Supression produit
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
    <title>Catalogue des produits</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <script src="https://code.iconify.design/1/1.0.3/iconify.min.js"></script>
</head>

<body>

    <?php include("../header.php");
    include("../menu.php");

    if (($_SESSION["utilisateur"]["utilisateur_type"] !== "administrateur") && ($_SESSION["utilisateur"]["utilisateur_type"] !== "gestionnaire")) : ?>
        <p class='erreur'>Accès refusé, vous devez être gestionnaire ou administrateur pour gérer les produits.</p><br>
    <?php exit;
    endif; ?>

    <section>
        <form id="recherche" action="" method="get">
            <fieldset>
                <label>Recherche produit : </label>
                <input type="text" name="recherche" value="<?= $recherche ?>" placeholder="Nom ou catégorie">
                <input type="submit" value="Recherchez">
                <button class="ajout"><a href="ajout.php">Ajouter</a></button>
            </fieldset>
        </form>
    </section>

    <main>
        <p><i>* Seuls les clients qui n'ont pas encore commandé peuvent être supprimé.<br><span class="margin_left">Veuillez supprimer les commandes associées à un produit pour pouvoir le supprimer.</span></i></p>

        <p>[<?= $offset + 1 ?>-<?= (($offset + 1) + 9) > $nombreProduits ? $nombreProduits : (($offset + 1) + 9) ?>] / <?= $nombreProduits ?> produits affichés</p>

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
            <section>
                <form action="" method="post">
                    <h2>Confirmer la suppression du produit numéro <?= $_SESSION["suppression"]["produit_id"] . " - " . $_SESSION["suppression"]["produit_nom"] ?> ?</h2>
                    <section>
                        <input type="submit" name="confirme" value="OUI">
                        <input type="submit" name="confirme" value="NON">
                    </section>
                </form>
            </section>
        <?php endif; ?>
    </main>

    <h3 class="pagination">Nombre de page :
        <?php
        for ($i = 1; $i <= $nombrePages; ++$i) {
            if ($i == $pageActuelle) {
                echo "[" . $i . "] ";
            } else {
                echo "<a href=index.php?page=" . $i . ">" . $i . "</a> ";
            }
        }
        ?>
    </h3>

    <?php include('../footer.php'); ?>

</body>

</html>