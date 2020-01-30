<?php
require_once("../inc/connectDB.php");
require_once("../inc/sql.php");
require_once("../inc/connectSession.php");


$liste = ListerCategories($conn);
$recherche = isset($_GET['recherche']) ? trim($_GET['recherche']) : "";






if (isset($_POST["categorie"])) {

    // contrôles des champs saisis
    // ---------------------------

    $erreurs = array();


    //-----------------Validation---Ajout---Catégorie---------------
    $categorie = trim($_POST['categorie']);
    if (!preg_match("/^[a-zA-Z\sàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð-]+$/u", $categorie)) {
        $erreurs['categorie'] = "<p class='erreur margin_lef'>Categorie incorrect. Veuillez utiliser seulement des lettre et traits d'union.</p>";
    }

    if (!$erreurs['categorie']) {
        ajouterCategorie($conn, $categorie);
        $_POST["categorie"] = NULL;
        header("Location: index.php");
    } else {
        $retSQL = "<p class='erreur'>Ajout non effectué.</p>";
    }
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
        //-----------------Validation---Modification---Catégorie---------------
        $nouveau_nom = trim($_POST['nouveau_nom']);
        if (!preg_match("/^[a-zA-Z\sàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð-]+$/u", $nouveau_nom)) {
            $erreurs['nouveau_nom'] = "<p class='erreur'>Categorie incorrect. Veuillez utiliser seulement des lettre et traits d'union.</p>";
        }

        if (!$erreurs['nouveau_nom']) :
            $_POST["id"] = $_SESSION["modification"]["categorie_id"];
            ModifierCategorie($conn, $_POST);
            unset($_SESSION["modification"]);
            header("Location: index.php");
        else :
            $retSQL = "<p class='erreur'>Modification non effectuée. Veuillez utiliser seulement des lettre et traits d'union.</p>";
        endif;
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
    <link rel="stylesheet" href="../assets/css/main.css">
    <script src="../assets/js/validation/validation_categories"></script>
</head>

<body>
    <?= isset($retSQL) ? $retSQL : ""  ?>
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
            <input type="text" id="categorie" name="categorie">
            <input type="submit" id="envoi" value="Ajouter">
            <span class="erreur" id="errCategorie"></span>
            <?= isset($erreurs['categorie']) ? $erreurs['categorie'] : ""  ?>
        </fieldset>
    </form>

    <?php if (isset($_POST["modifier"])) :
        $_SESSION["modification"] = LireCategorie($conn, $_POST["modifier"]); ?>
        <form action="" method="post">
            <fieldset>
                <h2>Modification de la catégorie <?= $_SESSION["modification"]["categorie_id"] . " - " . $_SESSION["modification"]["categorie_nom"] ?></h2>
                <label for="nouveau_nom">Nouveau nom : </label><input type="text" id="nouveau_nom" name="nouveau_nom">
                <span class="erreur" id="errNouveau_nom"></span>
                <input type="submit" id="envoiMod" name="confirmeMod" value="OK">
                <?= isset($erreurs['nouveau_nom']) ? $erreurs['nouveau_nom'] : "" ?>
            </fieldset>
        </form>
    <?php endif; ?>

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
    <?php endif; ?>

</body>

</html>