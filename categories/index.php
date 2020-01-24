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

if (isset($_POST["id"])) {
    SupprimerCategorie($conn, $_POST["id"]);
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

    <nav id="main_menu">
        <fieldset>
            <legend>Navigation</legend>
            <fieldset>
                <legend>Vendeur</legend>
                <a href="../clients/index.php">Clients</a><a href="../commandes/index.php">Commandes</a>
            </fieldset>
            <?php if ($_SESSION['utilisateur']["utilisateur_type"] == "gestionnaire" || $_SESSION['utilisateur']["utilisateur_type"] == "administrateur") : ?>
                <fieldset>
                    <legend>Gestionnaire</legend>
                    <a href="../produits/index.php">Produits</a><a href="../categories/index.php">Catégories</a>
                </fieldset>
            <?php endif;
            if ($_SESSION['utilisateur']["utilisateur_type"] == "administrateur") : ?>
                <fieldset>
                    <legend>Administrateur</legend>
                    <a href="../utilisateurs/index.php">Utilisateurs</a>
                </fieldset>
            <?php endif; ?>
            <button><a href="../deconnexion.php">Déconnexion</a></button>
        </fieldset>
    </nav>

    <?php if (($_SESSION["utilisateur"]["utilisateur_type"] !== "administrateur") && ($_SESSION["utilisateur"]["utilisateur_type"] !== "gestionnaire")) : ?>
        <p class='erreur'>Accès refusé, vous devez être administrateur pour gérer les utilisateurs.</p><br>
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
                        <input type="hidden" name="id" value="<?= $row["categorie_id"] ?>">
                        <input type="submit" value="supprimer">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

</body>

</html>