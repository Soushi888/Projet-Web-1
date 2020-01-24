<?php
require_once("../inc/connectDB.php");
require_once("../inc/sql.php");
require_once("../inc/connectSession.php");


$recherche = isset($_POST['recherche']) ? trim($_POST['recherche']) : "";

$liste = ListerClients($conn, $recherche);

if (isset($_POST["confirme"])) :
    if ($_POST["confirme"] == "OUI") :
        SupprimerClient($conn, $_SESSION["suppression"]["client_id"]);
        unset($_SESSION["suppression"]);
        header("Location: index.php");
    elseif ($_POST["confirme"] == "NON") :
        echo "<p class='erreur'>Suppression non effectuée !</p>";
        unset($_SESSION["suppression"]);
    endif;
endif;
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
    <h2>
        <pre><?= $_SESSION['utilisateur']["utilisateur_nom"] . ", " . $_SESSION['utilisateur']["utilisateur_prenom"] . " : " . $_SESSION['utilisateur']["utilisateur_type"] ?></pre>
    </h2>

    <?php include("../menu.php"); ?>

    <form id="recherche" action="" method="post">
        <fieldset>
            <label>Recherche nom client ou prénom du client : </label>
            <input type="text" name="recherche" value="<?= $recherche ?>" placeholder="nom ou prenom">
            <input type="submit" value="Recherchez">
            <button class="ajout"><a href="ajout.php">Ajouter</a></button>
        </fieldset>
    </form>

    <p><i>* Seuls les clients qui n'ont pas encore commandé peuvent être supprimé.<br><span class="margin_left">Veuillez supprimer les commandes associées à un client pour pouvoir le supprimer.</span></i></p>

    <table>
        <tr>
            <th>ID</th>
            <th>Nom du client</th>
            <th>Email</th>
            <th>Telephone</th>
            <th>Adresse</th>
            <th>Nbr. commandes</th>
            <th>Actions</th>
        </tr>

        <?php foreach ($liste as $row) :
        ?>
            <tr>
                <td class="txtcenter"><?= $row["client_id"] ?></td>
                <td><?= $row["client_nom"] . ", " . $row["client_prenom"] ?></td>
                <td><?= $row["client_email"] ?></td>
                <td><?= $row["client_telephone"] ?></td>
                <td><?php echo $row["client_adresse"];
                    echo isset($row["client_adresse2"]) ? "<br>" . $row["client_adresse2"] : "";
                    echo "<br>" . $row["client_ville"] . ", " . $row["client_cp"] .
                        "<br>Québec, Canada" ?></td>
                <td class="txtcenter"><?= $row["nbr_commandes"] ?></td>
                <td><?php if ($row["nbr_commandes"] == 0) : ?>
                        <form action="" method="post">
                            <input type="hidden" name="supprimer" value="<?= $row["client_id"] ?>">
                            <input type="submit" value="Supprimer">
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <?php if (isset($_POST["supprimer"])) :
        $_SESSION["suppression"] = LireClient($conn, $_POST["supprimer"]); ?>
        <form action="" method="post">
            <h2>Confirmer la suppression du client numéro <?= $_SESSION["suppression"]["client_id"] . " - " . $_SESSION["suppression"]["client_nom"] ?> ?</h2>
            <input type="submit" name="confirme" value="OUI">
            <input type="submit" name="confirme" value="NON">
        </form>
    <?php endif; ?>
</body>

</html>