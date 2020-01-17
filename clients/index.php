<?php
require_once("../inc/connectDB.php");
require_once("../inc/sql.php");

$recherche = isset($_POST['recherche']) ? trim($_POST['recherche']) : "";

$liste = listerClients($conn, $recherche);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Listes des clients</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <h1>Liste des clients</h1>

    <form id="recherche" action="" method="post">
        <fieldset>
            <label>Recherche nom client ou prénom du client : </label>
            <input type="text" name="recherche" value="<?= $recherche ?>" placeholder="ID, nom ou état">
            <input type="submit" value="Recherchez">
        </fieldset>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Nom du client</th>
            <th>Email</th>
            <th>Telephone</th>
            <th>Adresse</th>
            <th>Ville</th>
            <th>Code Postal</th>
            <th>Pays</th>
            <th>Actions</th>
        </tr>

        <?php foreach ($liste as $row) :
        ?>
            <tr>
                <td style="text-align: center;"><?= $row["client_id"] ?></td>
                <td><?= $row["client_nom"] . ", " . $row["client_prenom"] ?></td>
                <td><?= $row["client_email"] ?></td>
                <td><?= $row["client_telephone"] ?></td>
                <td><?= $row["client_adresse"] ?></td>
                <td><?= $row["client_ville"] ?></td>
                <td><?= $row["client_cp"] ?></td>
                <td><?= $row["client_pays"] ?></td>
                <td><a href="#">modifier</a> <a href="#">supprimer</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>