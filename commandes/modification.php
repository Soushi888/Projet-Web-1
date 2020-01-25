<?php
require_once("../inc/connectDB.php");
require_once("../inc/sql.php");
require_once("../inc/connectSession.php");

$categories = ListerCategories($conn);

if (isset($_POST["envoi"])) :
    ModifierCommande($conn, $_POST);
    unset($_SESSION["modification"]);
    header("Location: index.php");
endif;
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Modifier un utilisateur</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <h1>Modifier un utilisateur</h1>
    <h2>
        <pre><?= $_SESSION['utilisateur']["utilisateur_nom"] . ", " . $_SESSION['utilisateur']["utilisateur_prenom"] . " : " . $_SESSION['utilisateur']["utilisateur_type"] ?></pre>
    </h2>
    <?= isset($_SESSION["message"]) ? $_SESSION["message"] : "";

    include("../menu.php");
    ?>


    <main>
        <form id="commande" action="" method="post">
            <fieldset>
                <legend>Informations livraison</legend>
                <input type="hidden" name="id" value="<?= $_SESSION["modification"]["commande_id"] ?>">
                <label for="client_id">ID client :
                    <input name="client_id" id="client_id" type="number" placeholder="ID" value="<?= $_SESSION["modification"]["fk_client_id"] ?>" required></label>
                <label for="adresse">Adresse :
                    <input name="adresse" id="adresse" type="text" value="<?= $_SESSION["modification"]["commande_adresse"] ?>" required></label>
                <label for="adresse2">Adresse2 :
                    <input name="adresse2" id="adresse2" type="text" value="<?= $_SESSION["modification"]["commande_adresse2"] ?>"></label>
                <label for="ville">Ville :
                    <input name="ville" id="ville" type="text" value="<?= $_SESSION["modification"]["commande_adresse_ville"] ?>" required></label>
                <label for="cp">Code postal :
                    <input name="cp" id="cp" type="text" value="<?= $_SESSION["modification"]["commande_adresse_cp"] ?>" required></label><br>
            </fieldset>
            <fieldset>
                <legend>Information commande</legend>
                <label for="commande_date">Date (au format "YYYY-MM-DD hh:mm:ss")</label>
                <input type="text" id="commande_date" name="date" value="<?= $_SESSION["modification"]["commande_date"] ?>">
                <label for="commande_etat">État de la commande</label>
                <select name="etat" id="commande_etat">
                    <option value="en cours">En cours</option>
                    <option value="complète">Complète</option>
                    <option value="annulée">Annulée</option>
                </select><br><br>
                <label for="commande_commentaire">Commentaires : </label>
                <textarea name="commentaires" id="commande_commentaires" cols="100" rows="10"><?= $_SESSION["modification"]["commande_commentaires"] ?></textarea>
            </fieldset>
            <input type="submit" name="envoi" value="Modifier !">
        </form>
    </main>

</body>

</html>