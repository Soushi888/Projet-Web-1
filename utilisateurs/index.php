<?php
require_once("../inc/connectDB.php");
require_once("../inc/sql.php");

$recherche = isset($_POST['recherche']) ? trim($_POST['recherche']) : "";

$liste = listerUtilisateurs($conn, $recherche);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Listes des utilisateurs</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <h1>Liste des utilisateurs</h1>

    <form id="recherche" action="" method="post">
        <fieldset>
            <label>Recherche utilisateur : </label>
            <input type="text" name="recherche" value="<?= $recherche ?>">
            <input type="submit" value="Recherchez">
        </fieldset>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Email</th>
            <th>Type</th>
        </tr>

        <?php foreach ($liste as $row) :
        ?>
            <tr>
                <td style="text-align: center;"><?= $row["utilisateur_id"] ?></td>
                <td><?= $row["utilisateur_email"] ?></td>
                <td><?= $row["utilisateur_type"] ?></td>
                <td><a href="#">modifier</a> <a href="#">supprimer</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>