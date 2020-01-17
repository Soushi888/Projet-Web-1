<?php
require_once("../inc/connectDB.php");
require_once("../inc/sql.php");

$liste = listerCategories($conn);
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

    <form id="recherche" action="" method="post">
        <fieldset>
            <label>Ajouter une catégorie : </label>
            <input type="text" name="recherche" value="">
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