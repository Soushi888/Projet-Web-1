<?php
require_once("../inc/connectDB.php");
require_once("../inc/sql.php");
require_once("../inc/connectSession.php");

$titre = "Modifier une commande";

$adresse2 = NULL;

if (isset($_POST["envoi"])) :

    // contrôles des champs saisis

    $erreurs = array();


    // Validation ID client
    $client_id = trim($_POST['client_id']);
    if (!is_numeric($client_id)) {
        $erreurs['client_id'] = "<p class='erreur margin_left'>Le ID du client doit être un numéro.</p>";
    }

    // Validation adresse de livraison 1 et 2
    $adresse = trim($_POST['adresse']);
    if (!preg_match("/^[\d]{1,5} [a-zA-ZsàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð-]+ [a-zA-ZsàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð -]+$/", $adresse)) {
        $erreurs['adresse'] = "<p class='erreur margin_left'>L'adresse doit être au format '[numero civique] [rue/avenue/boulevard] [nom de rue/avenue/boulevard]'.</p>";
    }

    $ville = trim($_POST['ville']);
    if (!preg_match("/^[a-zA-ZsàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð-]*$/", $ville)) {
        $erreurs['ville'] = "<p class='erreur margin_left'>La ville ne doit contenir que des lettres et des traits d'union.</p>";
    }

    $cp = trim($_POST['cp']);
    if (!preg_match("/^[a-zA-Z][\d][a-zA-z] [\d][a-zA-z][\d]$/", $cp)) {
        $erreurs['cp'] = "<p class='erreur margin_left'>Le code postal doit être au format 'X1Y 2Z3'.</p>";
    }

    $date = trim($_POST['date']);
    if (!preg_match("/(20[\d]{2})-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]) ([01][\d]|2[1-3]):([0-6]\d):([0-6]\d)/", $date)) {
        $erreurs['date'] = "<p class='erreur margin_left'>La date doit être valide.</p>";
    }

    $commentaires =  isset($_POST['commande_commentaires']) ? trim($_POST['commande_commentaires']) : NULL;

    if (count($erreurs) == 0) :
        if ($adresse2 == "")  $_POST['adresse2'] = NULL;
        ModifierCommande($conn, $_POST);
        unset($_SESSION["modification"]);
        header("Location: index.php");
    endif;
endif;
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Modifier une commande</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <script src="../assets/js/validation/validation_commandes.js"></script>
</head>

<body>
    <?= isset($_SESSION["message"]) ? $_SESSION["message"] : "";
    include("../header.php");
    include("../menu.php");
    ?>


    <main>
        <form id="commande" action="" method="post">
            <fieldset>
                <legend>Informations livraison</legend>
                <input type="hidden" name="id" value="<?= $_SESSION["modification"]["commande_id"] ?>">
                <label for="client_id">ID client :
                    <input name="client_id" id="client_id" type="number" placeholder="ID" value="<?= $_SESSION["modification"]["fk_client_id"] ?>" required><?= isset($erreurs['client_id']) ? $erreurs['client_id'] : "" ?></label>
                <span class="erreur" id="errID"></span>

                <label for="adresse">Adresse :
                    <input name="adresse" id="adresse_livraison" type="text" value="<?= $_SESSION["modification"]["commande_adresse"] ?>" required><?= isset($erreurs['adresse']) ? $erreurs['adresse'] : "" ?></label>
                <span class="erreur" id="errAdresse"></span>

                <label for="adresse2">Adresse2 :
                    <input name="adresse2" id="adresse2" type="text" value="<?= $_SESSION["modification"]["commande_adresse2"] ?>"><?= isset($erreurs['adresse2']) ? $erreurs['adresse2'] : "" ?></label>

                <label for="ville">Ville :
                    <input name="ville" id="ville_livraison" type="text" value="<?= $_SESSION["modification"]["commande_adresse_ville"] ?>" required><?= isset($erreurs['ville_livraison']) ? $erreurs['ville'] : "" ?></label>
                <span class="erreur" id="errVille"></span>

                <label for="cp">Code postal :
                    <input name="cp" id="cp_livraison" type="text" value="<?= $_SESSION["modification"]["commande_adresse_cp"] ?>" required></label><?= isset($erreurs['cp']) ? $erreurs['cp'] : "" ?>
                <span class="erreur" id="errCP"></span>
            </fieldset>
            <fieldset>
                <legend>Information commande</legend>

                <label for="commande_date">Date (au format "YYYY-MM-DD hh:mm:ss")</label>
                <input type="text" id="commande_date" name="date" value="<?= $_SESSION["modification"]["commande_date"] ?>"><?= isset($erreurs['date']) ? $erreurs['date'] : "" ?>
                <span class="erreur" id="errDate"></span>

                <label for="commande_etat">État de la commande</label>
                <select name="etat" id="commande_etat">
                    <option value="en cours">En cours</option>
                    <option value="complète">Complète</option>
                    <option value="annulée">Annulée</option>
                </select><br>

                <label for="commande_commentaire">Commentaires : </label>
                <textarea name="commentaires" id="commande_commentaires" cols="100" rows="10"><?= $_SESSION["modification"]["commande_commentaires"] ?></textarea>
            </fieldset>
            <input type="submit" name="envoi" id="envoi" value="Modifier !">
        </form>
    </main>

</body>

</html>