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

if (isset($_POST["supprimer"])) {
    SupprimerCategorie($conn, $_POST["supprimer"]);
    header("Location: index.php");
}
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
                <td><?php if ($row["Nombre de produits"] == 0) : ?>
                        <form action="" method="post">
                            <input type="hidden" name="supprimer" value="<?= $row["categorie_id"] ?>">
                            <input type="submit" value="Supprimer">
                        </form>
                    <?php endif; ?>
                    <form action="" method="post">
                        <input type="hidden" name="modifier" value="<?= $row["categorie_id"] ?>">
                        <input type="submit" value="Modifier">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

</body>

</html>