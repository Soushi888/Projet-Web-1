<?php
require_once("../inc/connectDB.php");
require_once("../inc/sql.php");
require_once("../inc/connectSession.php");


$liste = ListerCategories($conn);
$recherche = isset($_GET['recherche']) ? trim($_GET['recherche']) : "";

if (isset($_POST["categorie"])) {
    $categorie = $_POST["categorie"];
    ajouterCategorie($conn, $categorie);
    $_POST["categorie"] = NULL;
    header("Location: index.php");
}

if (isset($_POST["confirme"])) :
    if ($_POST["confirme"] == "OUI") :
        SupprimerCategorie($conn, $_SESSION["suppression"]["categorie_id"]);
        unset($_SESSION["suppression"]);
        header("Location: index.php");
    elseif ($_POST["confirme"] == "NON") :
        echo "<p class='erreur'>Suppression non effectuée !</p>";
        unset($_SESSION["suppression"]);
    endif;
endif;

if (isset($_POST["confirmeMod"])) :
    if ($_POST["confirmeMod"] == "OK") : 
        $_POST["id"] = $_SESSION["modification"]["categorie_id"];
        ModifierCategorie($conn, $_POST);
        unset($_SESSION["modification"]);
        header("Location: index.php");
    elseif ($_POST["confirmeMod"] == "NON") :
        echo "<p class='erreur'>modification non effectuée !</p>";
        unset($_SESSION["modification"]);
    endif;
endif;
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Listes des catégories</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <h1>Liste des catégories</h1>
    <h2>
        <pre><?= $_SESSION['utilisateur']["utilisateur_nom"] . ", " . $_SESSION['utilisateur']["utilisateur_prenom"] . " : " . $_SESSION['utilisateur']["utilisateur_type"] ?></pre>
    </h2>

    <?php include("../menu.php");

    if (($_SESSION["utilisateur"]["utilisateur_type"] !== "administrateur") && ($_SESSION["utilisateur"]["utilisateur_type"] !== "gestionnaire")) : ?>
        <p class='erreur'>Accès refusé, vous devez être gestionnaire ou administrateur pour gérer les catégories.</p><br>
    <?php exit;
    endif; ?>

    <form id="ajout_categorie" action="" method="post">
        <fieldset>
            <label>Ajouter une catégorie : </label>
            <input type="text" name="categorie">
            <input type="submit" value="Ajouter">
        </fieldset>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Nom de la catégorie</th>
            <th>Nombre de produits</th>
            <th>Actions</th>
        </tr>

        <?php foreach ($liste as $row) :
        ?>
            <tr>
                <td style="text-align: center;"><?= $row["categorie_id"] ?></td>
                <td><?= $row["categorie_nom"] ?></td>
                <td class="txtcenter"><?= $row["Nombre de produits"] ?></td>
                <td>
                    <form action="" method="post">
                        <input type="hidden" name="supprimer" value="<?= $row["categorie_id"] ?>">
                        <input type="submit" value="Supprimer">
                    </form>

                    <form action="" method="post">
                        <input type="hidden" name="modifier" value="<?= $row["categorie_id"] ?>">
                        <input type="submit" value="Modifier">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <?php if (isset($_POST["supprimer"])) :
        $_SESSION["suppression"] = LireCategorie($conn, $_POST["supprimer"]); ?>
        <form action="" method="post">
            <h2>Confirmer la suppression de la catégorie numéro <?= $_SESSION["suppression"]["categorie_id"] . " - " . $_SESSION["suppression"]["categorie_nom"] ?> ?</h2>
            <input type="submit" name="confirme" value="OUI">
            <input type="submit" name="confirme" value="NON">
        </form>
    <?php endif;
    if (isset($_POST["modifier"])) :
        $_SESSION["modification"] = LireCategorie($conn, $_POST["modifier"]); ?>
        <form action="" method="post">
            <h2>Modification de la catégorie <?= $_SESSION["modification"]["categorie_id"] . " - " . $_SESSION["modification"]["categorie_nom"] ?></h2>
            <p>Nouveau nom : </p><input type="text" name="nouveau_nom"><br>
            <input type="submit" name="confirmeMod" value="OK">
            <input type="submit" name="confirmeMod" value="NON">
        </form>
    <?php endif; ?>

</body>

</html>