<?php
require_once("../inc/connectDB.php");
require_once("../inc/sql.php");

session_start();

$liste = ListerCategories($conn);
$recherche = isset($_GET['recherche']) ? trim($_GET['recherche']) : "";

if (isset($_POST["categorie"])) {
    $categorie = $_POST["categorie"];
    ajouterCategorie($conn, $categorie);
    $_POST["categorie"] = NULL;
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
    <h2>Utilisateur : <pre><?= $_SESSION['utilisateur']["utilisateur_nom"] . ", " . $_SESSION['utilisateur']["utilisateur_prenom"] ?></pre></h2>

    <nav id="main_menu">
        <fieldset>
            <legend>Navigation</legend>
            <fieldset>
                <legend>Vendeur</legend>
                <a href="../clients/index.php">Clients</a><a href="../commandes/index.php">Commandes</a>
            </fieldset>
            <fieldset>
                <legend>Gestionnaire</legend>
                <a href="../produits/index.php">Produits</a><a href="../categories/index.php">Catégories</a>
            </fieldset>
            <fieldset>
                <legend>Administrateur</legend>
                <a href="../utilisateurs/index.php">Utilisateurs</a>
            </fieldset>
        </fieldset>
    </nav>

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
            <th>Actions</th>
        </tr>

        <?php foreach ($liste as $row) :
        ?>
            <tr>
                <td style="text-align: center;"><?= $row["categorie_id"] ?></td>
                <td><?= $row["categorie_nom"] ?></td>
                <td><a href="#">supprimer</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
    
</body>

</html>