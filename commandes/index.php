<?php
require_once("../inc/connectDB.php");
require_once("../inc/sql.php");
require_once("../inc/connectSession.php");

$titre = "Liste des commandes";

$recherche = isset($_POST['recherche']) ? trim($_POST['recherche']) : "";

// Pagination
$nombreCommandes = NombreCommandes($conn);
$nombrePages = ceil($nombreCommandes / 10);

$pageActuelle = 1;

if (isset($_GET['page'])) {
    $pageActuelle = $_GET['page'];
} elseif ($pageActuelle > $nombrePages) {
    $pageActuelle = $nombrePages;
} else {
    $pageActuelle = 1;
}


// calcule du nombre de lignes de la tabble commandes_produit pour arriver à 10 commandes complètes à partir d'un offset donné

// $produitsParCommandes = NombreProduitsParCommandes($conn);

// $nombres_de_produits_pour_dix_commandes = 0;

// for ($i = 0; $i < 10; ++$i) {
//     $nombres_de_produits_pour_dix_commandes += $produitsParCommandes[$i]['nombre_de_produits'];
// }

$nbrParPage =  10;

$offset = ($pageActuelle - 1) * $nbrParPage;


$liste = ListerCommandes($conn, $recherche);
?>

<?php
// Confirmation de Suppresion
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

// Redirection vers la page de modification avec les informations du produit à modifier
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
    <script src="https://code.iconify.design/1/1.0.3/iconify.min.js"></script>
</head>

<body>

    <!-- <pre><?= print_r($liste) ?></pre> -->



    <?php
    include("../header.php");
    include("../menu.php"); ?>

    <section>
        <form id="recherche" action="" method="post">
            <fieldset>
                <label>Recherche commande : </label>
                <input type="text" name="recherche" value="<?= $recherche ?>" placeholder="ID, nom ou état">
                <input type="submit" value="Recherchez">
                <button class="ajout"><a href="ajout.php">Ajouter</a></button>
            </fieldset>
        </form>
    </section>

    <main>
        <p>[<?= $offset ?>-<?= ($offset + 10) > $nombreCommandes ? $nombreCommandes : ($offset + 10) ?>] / <?= $nombreCommandes ?> commandes affichés</p>

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

            <?php
            
            // foreach ($liste as $row) :
            for ($i = $offset; $i < ($offset + 9); ++$i) :
                // if ($i == $liste[$i]["commande_id"]) :
            ?>
                    <tr>
                        <td style="text-align: center;"><?= $liste[$i]["commande_id"] ?></td>
                        <td><?= $liste[$i]["commande_client"] ?></td>
                        <td><?= $liste[$i]["commande_date"] . "<br>" . $liste[$i]["commande_heure"] ?></td>
                        <td><?= implode("<br>", $liste[$i]["commande_produit"]) ?></td>
                        <td style="text-align: center;"><?= implode("<br>", $liste[$i]["commande_produit_prix"]) ?></td>
                        <td style="text-align: center;"><?= implode("<br>", $liste[$i]["commande_produit_quantite"]) ?></td>
                        <td><?= $liste[$i]["commande_total_ht"] ?></td>
                        <td><?= $liste[$i]["commande_total_ttc"] ?></td>
                        <td><?php echo $liste[$i]["commande_adresse"];
                            echo isset($liste[$i]["commande_adresse2"]) ? "<br>" . $liste[$i]["commande_adresse2"] : "";
                            echo "<br>" . $liste[$i]["commande_adresse_ville"] . ", " . $liste[$i]["commande_adresse_cp"] .
                                "<br>Québec, Canada" ?></td>

                        <td><?= $liste[$i]["commande_commentaires"] ?></td>
                        <td><?= $liste[$i]["commande_etat"] ?></td>
                        <td>
                            <form action="" method="post">
                                <input type="hidden" name="supprimer" value="<?= $liste[$i]["commande_id"] ?>">
                                <input type="submit" value="Supprimer">
                            </form>
                            <form action="" method="post">
                                <input type="hidden" name="modifier" value="<?= $liste[$i]["commande_id"] ?>">
                                <input type="submit" value="Modifier">
                            </form>
                        </td>
                    </tr>
            <?php // endif;
            endfor; ?>
        </table>

        <?php if (isset($_POST["supprimer"])) :
            $_SESSION["suppression"] = LireCommande($conn, $_POST["supprimer"]); ?>
            <section>
                <form action="" method="post">
                    <h2>Confirmer la suppression de la commande numéro <?= $_SESSION["suppression"]["commande_id"] ?> ?</h2>
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