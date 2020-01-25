<?php
require_once("../inc/connectDB.php");
require_once("../inc/sql.php");
require_once("../inc/connectSession.php");

$categories = ListerCategories($conn);

if (isset($_POST["envoi"])) {
    ModifierCommande($conn, $_POST);
    unset($_SESSION["modification"]);
    header("Location: index.php");
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Modifier un utilisateur</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <pre><?php print_r($_SESSION) ?></pre>

    <h1>Modifier un utilisateur</h1>
    <h2>
        <pre><?= $_SESSION['utilisateur']["utilisateur_nom"] . ", " . $_SESSION['utilisateur']["utilisateur_prenom"] . " : " . $_SESSION['utilisateur']["utilisateur_type"] ?></pre>
    </h2>
    <?= isset($_SESSION["message"]) ? $_SESSION["message"] : "";

    include("../menu.php");

    if ($_SESSION["utilisateur"]["utilisateur_type"] !== "administrateur") : ?>
        <p class='erreur'>Accès refusé, vous devez être administrateur pour gérer les utilisateurs.</p><br>
    <?php exit;
    endif; ?>

    <main>
        <form id="commande" action="" method="post">
            <fieldset>
                <legend>Informations livraison</legend>
                <label for="client_id">ID client :
                    <input name="client_id" id="client_id" type="number" placeholder="ID" value="<?= $_SESSION["modification"]["fk_client_id"] ?>" required></label>
                <label for="client_adresse">Adresse :
                    <input name="client_adresse" id="client_adresse" type="text" value="<?= $_SESSION["modification"]["commande_adresse"] ?>" required></label>
                <label for="client_adresse2">Adresse2 :
                    <input name="client_adresse2" id="client_adresse2" type="text" value="<?= $_SESSION["modification"]["commande_adresse2"] ?>"></label>
                <label for="client_ville">Ville :
                    <input name="client_ville" id="client_ville" type="text" value="<?= $_SESSION["modification"]["commande_adresse_ville"] ?>" required></label>
                <label for="client_cp">Code postal :
                    <input name="client_cp" id="client_cp" type="text" value="<?= $_SESSION["modification"]["commande_adresse_cp"]?>" required></label><br>
            </fieldset>
            <fieldset>
                <legend>Information commande</legend>
                <label for="commande_date">Date (au format "YYYY-MM-DD hh:mm:ss")</label>
                <input type="text" value="<?= $_SESSION["modification"]["commande_date"]?>">
                <label for="commande_etat">État de la commande</label>
                <select name="etat" id="commande_etat">
                    <option value="en cours">En cours</option>
                    <option value="complète">Complète</option>
                    <option value="annulée">Annulée</option>
                </select><br><br>
                <label for="commande_commentaire">Commentaires : </label>
                <textarea name="commande_commentaires" id="commande_commentaires" cols="100" rows="10"><?= $_SESSION["modification"]["commande_commentaires"]?></textarea>
            </fieldset>
            <input type="submit" name="envoi" value="Modifier !">
        </form>
    </main>

</body>

</html>