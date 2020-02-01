<?php
require_once("../inc/connectDB.php");
require_once("../inc/sql.php");
require_once("../inc/connectSession.php");

$titre = "Liste des utilisateurs";

$recherche = isset($_POST['recherche']) ? trim($_POST['recherche']) : "";

// Pagination
$nombreUtilisateurs = NombreUtilisateurs($conn);
$nombrePages = ceil($nombreUtilisateurs / 10);

$pageActuelle = 1;

if (isset($_GET['page'])) {
    $pageActuelle = $_GET['page'];
} elseif ($pageActuelle > $nombrePages) {
    $pageActuelle = $nombrePages;
} else {
    $pageActuelle = 1;
}

$offset = ($pageActuelle - 1) * 10;

$liste = ListerUtilisateurs($conn, $recherche, $offset, 10);


if (isset($_POST["confirme"])) :
    if ($_POST["confirme"] == "OUI") :
        SupprimerUtilisateur($conn, $_SESSION["suppression"]);
        header("Location: index.php");
    elseif ($_POST["confirme"] == "NON") :
        echo "<p class='erreur'>Suppression non effectuée !</p>";
        unset($_SESSION["id_suppression"]);
    endif;
endif;

if (isset($_POST["modifier"])) :
    $_SESSION["modification"] = LireUtilisateur($conn, $_POST["email"]);
    header("Location: modification.php");
endif;

?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Listes des utilisateurs</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <script src="https://code.iconify.design/1/1.0.3/iconify.min.js"></script>
</head>

<body>
    <?= isset($_SESSION["message"]) ? $_SESSION["message"] : "";

    include("../header.php"); // Entête
    include("../menu.php"); // Menu de navigation

    if ($_SESSION["utilisateur"]["utilisateur_type"] !== "administrateur") : ?>
        <section>
            <p class='erreur'>Accès refusé, vous devez être administrateur pour gérer les utilisateurs.</p>
        </section>
    <?php exit;
    endif; ?>

    <section>
        <form id="recherche" action="" method="post">
            <fieldset>
                <label>Recherche utilisateur : </label>
                <input type="text" name="recherche" value="<?= $recherche ?>">
                <input type="submit" value="rechercher">
                <button class="ajout"><a href="ajout.php">Ajouter</a></button>
            </fieldset>
        </form>
    </section>

    <main>
        <p>[<?= $offset + 1 ?>-<?= (($offset + 1) + 9) > $nombreUtilisateurs ? $nombreUtilisateurs : (($offset + 1) + 9) ?>] / <?= $nombreUtilisateurs ?> catégories affichés</p>

        <table>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Type</th>
                <th>Actions</th>
            </tr>

            <?php foreach ($liste as $row) :
            ?>
                <tr>
                    <td style="text-align: center;"><?= $row["utilisateur_id"] ?></td>
                    <td><?= $row["utilisateur_nom"] . ", " . $row["utilisateur_prenom"] ?></td>
                    <td><?= $row["utilisateur_email"] ?></td>
                    <td><?= $row["utilisateur_type"] ?></td>
                    <td class="actions">
                        <form action="" method="post">
                            <input type="hidden" name="supprimer" value="<?= $row["utilisateur_id"] ?>">
                            <input type="hidden" name="email" value="<?= $row["utilisateur_email"] ?>">
                            <input type="submit" value="supprimer">
                        </form>
                        <form action="" method="post">
                            <input type="hidden" name="modifier" value="<?= $row["utilisateur_id"] ?>">
                            <input type="hidden" name="email" value="<?= $row["utilisateur_email"] ?>">
                            <input type="submit" value="modifier">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </main>
    <?php if (isset($_POST["supprimer"])) :
        $_SESSION["suppression"] = $_POST["supprimer"];
        $utilisateur_supression = LireUtilisateur($conn, $_POST["email"]);
    ?>
        <section>
            <form action="" method="post">
                <h2>Confirmer suppression de l'utilisateur <?= $utilisateur_supression["utilisateur_nom"] . " " . $utilisateur_supression["utilisateur_prenom"] . " de type " . $utilisateur_supression["utilisateur_type"] ?> ?</h2>
                <section>
                    <input type="submit" name="confirme" value="OUI">
                    <input type="submit" name="confirme" value="NON">
                </section>
            </form>
        </section>
    <?php endif; ?>

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