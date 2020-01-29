<?php
require_once("../inc/connectDB.php");
require_once("../inc/sql.php");
require_once("../inc/connectSession.php");

$categories = ListerCategories($conn);

if (isset($_POST["envoi"])) {

    // contrôles des champs saisis

    $erreurs = array();

    // validation nom
    $nom = trim($_POST['nom']);
    if (!preg_match("/^^[ 0-9a-zA-Z\sàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð-]+$/u", $nom)) {
        $erreurs['nom'] = "<p class='erreur margin_left'>Nom incorrect. Veuillez utiliser seulement des lettre et traits d'union.</p>";
    }

    // validation prix
    $prix = trim($_POST['prix']);
    if (!preg_match('/^[\d]*\.?[\d]{0,2}$/', $prix)) {
        $erreurs['prix'] = "<p class='erreur margin_left'>prix incorrect.</p>";
    }

    // validation quantité
    $quantite = trim($_POST['quantite']);
    if ($quantite > 99999) {
        $erreurs['quantite'] = "<p class='erreur margin_left'>La quantité doit un être un nombre entre 0 et 99 999.</p>";
    }

    // insertion dans la table produits si aucune erreur
    // -----------------------------------------------

    if (count($erreurs) == 0) {
        ModifierProduit($conn, $_POST);
        unset($_SESSION["modification"]);
        header("Location: index.php");
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Modifier un produit</title>
    <link rel="stylesheet" href="../assets/css/main.css">
</head>

<body>
    <h1>Modifier un produit</h1>
    <h2>
        <pre><?= $_SESSION['utilisateur']["utilisateur_nom"] . ", " . $_SESSION['utilisateur']["utilisateur_prenom"] . " : " . $_SESSION['utilisateur']["utilisateur_type"] ?></pre>
    </h2>
    <?= isset($_SESSION["message"]) ? $_SESSION["message"] : "";

    include("../menu.php");

    if ($_SESSION["utilisateur"]["utilisateur_type"] !== "administrateur" && ($_SESSION["utilisateur"]["utilisateur_type"] !== "gestionnaire")) : ?>
        <p class='erreur'>Accès refusé, vous devez être administrateur pour gérer les utilisateurs.</p><br>
    <?php exit;
    endif; ?>

    <main>
        <form action="" method="post">
            <input type="hidden" name="id" value="<?= $_SESSION["modification"]["produit_id"] ?>">
            <label for="nom">Nom : </label>
            <input type="text" name="nom" value="<?= $_SESSION["modification"]["produit_nom"] ?>" required><?= isset($erreurs['nom']) ? $erreurs['nom'] : "" ?><br>
            <label for="description">Description : </label>
            <textarea name="description" id="description" cols="50" rows="3"><?= $_SESSION["modification"]["produit_description"] ?></textarea><br>
            <label for="prix">Prix : </label>
            <input type="text" name="prix" value="<?= $_SESSION["modification"]["produit_prix"] ?>" required><?= isset($erreurs['prix']) ? $erreurs['prix'] : "" ?><br>
            <label for="quantite">Quantité : </label>
            <input type="number" name="quantite" max="99999" value="<?= $_SESSION["modification"]["produit_quantite"] ?>" required><?= isset($erreurs['quantite']) ? $erreurs['quantite'] : "" ?><br>
            <table>
                <?php if (count($categories) > 0) : ?>
                    <label>Categorie du produit</label>
                    <select name="categorie">
                        <?php foreach ($categories as $row) : ?>
                            <option value="<?= $row["categorie_id"] ?>" <?= $row["categorie_id"] ==  $_SESSION["modification"]["fk_categorie_id"] ? "selected" : "" ?>><?= $row["categorie_nom"] ?></option>
                        <?php endforeach; ?>
                    </select>
            </table>
        <?php else : ?>
            <p class="erreur">Aucune categorie trouvé.</p>
        <?php endif; ?>

        <input type="submit" name="envoi" value="Modifier !">
        </form>
    </main>

</body>

</html>