<?php
require_once("../inc/connectDB.php");
require_once("../inc/sql.php");
require_once("../inc/connectSession.php");

$titre = "Liste des clients";

$recherche = isset($_POST['recherche']) ? trim($_POST['recherche']) : "";

// Pagination
$nombreClients = NombreClients($conn);
$nombrePages = ceil($nombreClients / 10);

$pageActuelle = 1;

if (isset($_GET['page'])) {
    $pageActuelle = $_GET['page'];
} elseif ($pageActuelle > $nombrePages) {
    $pageActuelle = $nombrePages;
} else {
    $pageActuelle = 1;
}

$offset = ($pageActuelle - 1) * 10;

$liste = ListerClients($conn, $recherche);

if (isset($_POST["confirme"])) :
    if ($_POST["confirme"] == "OUI") :
        SupprimerClient($conn, $_SESSION["suppression"]["client_id"]);
        unset($_SESSION["suppression"]);
        header("Location: index.php");
    elseif ($_POST["confirme"] == "NON") :
        echo "<p class='erreur'>Suppression non effectuée !</p>";
        unset($_SESSION["suppression"]);
    endif;
endif;

if (isset($_POST["modifier"])) :
    $_SESSION["modification"] = LireClient($conn, $_POST["modifier"]);
    header("Location: modification.php");
endif;
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Listes des clients</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <script src="https://code.iconify.design/1/1.0.3/iconify.min.js"></script>
</head>

<body>
    <?php include("../header.php");
    include("../menu.php");
    ?>

    <section>
        <form id="recherche" action="" method="post">
            <fieldset>
                <label>Recherche client : </label>
                <input type="text" name="recherche" value="<?= $recherche ?>" placeholder="nom, prenom ou telephone">
                <input type="submit" value="Recherchez">
                <button class="ajout"><a href="ajout.php">Ajouter</a></button>
            </fieldset>
        </form>
    </section>

    <main>
        <p><i>* Seuls les clients qui n'ont pas encore commandé peuvent être supprimé.<br><span class="margin_left">Veuillez supprimer les commandes associées à un client pour pouvoir le supprimer.</span></i></p>

        <p>[<?= $offset + 1 ?>-<?= (($offset + 1) + 9) > $nombreClients ? $nombreClients : (($offset + 1) + 9) ?>] / <?= $nombreClients ?> catégories affichés</p>

        <table>
            <tr>
                <th>ID</th>
                <th>Nom du client</th>
                <th>Email</th>
                <th>Telephone</th>
                <th>Adresse</th>
                <th>Nbr. commandes</th>
                <th>Actions</th>
            </tr>

            <?php foreach ($liste as $row) :
            ?>
                <tr>
                    <td class="txtcenter"><?= $row["client_id"] ?></td>
                    <td><?= $row["client_nom"] . ", " . $row["client_prenom"] ?></td>
                    <td><?= $row["client_email"] ?></td>
                    <td><?= $row["client_telephone"] ?></td>
                    <td><?php echo $row["client_adresse"];
                        echo isset($row["client_adresse2"]) ? "<br>" . $row["client_adresse2"] : "";
                        echo "<br>" . $row["client_ville"] . ", " . $row["client_cp"] .
                            "<br>Québec, Canada" ?></td>
                    <td class="txtcenter"><?= $row["nbr_commandes"] ?></td>
                    <td><?php if ($row["nbr_commandes"] == 0) : ?>
                            <form action="" method="post">
                                <input type="hidden" name="supprimer" value="<?= $row["client_id"] ?>">
                                <input type="submit" value="Supprimer">
                            </form>
                        <?php endif; ?>
                        <form action="" method="post">
                            <input type="hidden" name="modifier" value="<?= $row["client_id"] ?>">
                            <input type="submit" value="Modifier">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php if (isset($_POST["supprimer"])) :
            $_SESSION["suppression"] = LireClient($conn, $_POST["supprimer"]); ?>
            <section>
                <form action="" method="post">
                    <h2>Confirmer la suppression du client numéro <?= $_SESSION["suppression"]["client_id"] . " - " . $_SESSION["suppression"]["client_nom"] ?> ?</h2>
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