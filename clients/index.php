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
            <input type="text" name="recherche" value="<?= $recherche ?>" placeholder="nom ou prenom">
            <input type="submit" value="Recherchez">
            <button class="ajout"><a href="ajout.php">Ajouter</a></button>
        </fieldset>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Nom du client</th>
            <th>Email</th>
            <th>Telephone</th>
            <th>Adresse</th>
            <th>Actions</th>
        </tr>

        <?php foreach ($liste as $row) :
        ?>
            <tr>
                <td style="text-align: center;"><?= $row["client_id"] ?></td>
                <td><?= $row["client_nom"] . ", " . $row["client_prenom"] ?></td>
                <td><?= $row["client_email"] ?></td>
                <td><?= $row["client_telephone"] ?></td>
                <td><?php echo $row["client_adresse"];
                    echo isset($row["client_adresse2"]) ? "<br>" . $row["client_adresse2"] : "";
                    echo "<br>" . $row["client_ville"] . ", " . $row["client_cp"] . 
                    "<br>Québec, Canada" ?></td>
                <td><a href="#">modifier</a> <a href="#">supprimer</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>