<?php
require_once("../inc/connectDB.php");
require_once("../inc/sql.php");
require_once("../inc/connectSession.php");

$categories = ListerCategories($conn);

if (isset($_POST["envoi"])) {

    // contrôles des champs saisis
    // ---------------------------

    $erreurs = array();


    //-----------------Validation---Nom---------------
    $nom = trim($_POST['nom']);
    if (!preg_match("/^[a-zA-Z1-9sàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð- ]+$/u", $nom)) {
        $erreurs['nom'] = "<p class='erreur margin_left'>Nom incorrect. Veuillez utiliser seulement des lettre et traits d'union.</p>";
    }

    //-----------------Validation---prix---------------
    $prix = trim($_POST['prix']);
    if (!preg_match('/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/', $prix)) {
        $erreurs['prix'] = "<p class='erreur margin_left'>prix incorrect.</p>";
    }

    //-----------------Validation---quantité---------------
    $quantite = trim($_POST['quantite']);
    if (!preg_match('/^0-9{1-5}$/', $quantite)) {
        $erreurs['quantite'] = "<p class='erreur margin_left'>La quantité doit un être un nombre entre 0 et 10 000.</p>";
    }

    // insertion dans la table produits si aucune erreur
    // -----------------------------------------------

    if (count($erreurs) === 0) {
        AjouterProduit($conn, $_POST);
        header("Location: index.php");
    }
    else {
        $retSQL = "<p class='erreur'>Ajout non effectué.</p>";
    }
} ?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Ajouter un produit</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <?= isset($retSQL) ? $retSQL : "" ?>
    <h1>Ajout d'un produit</h1>
    <h2>
        <pre><?= $_SESSION['utilisateur']["utilisateur_nom"] . ", " . $_SESSION['utilisateur']["utilisateur_prenom"] . " : " . $_SESSION['utilisateur']["utilisateur_type"] ?></pre>
    </h2>

    <?php include("../menu.php");

    if (($_SESSION["utilisateur"]["utilisateur_type"] !== "administrateur") && ($_SESSION["utilisateur"]["utilisateur_type"] !== "gestionnaire")) : ?>
        <p class='erreur'>Accès refusé, vous devez être gestionnaire ou administrateur pour gérer les produits.</p><br>
    <?php exit;
    endif; ?>

    <form action="" method="post">
        <label for="nom">Nom : </label>
        <input type="text" name="nom" required><?= isset($erreurs['nom']) ? $erreurs['nom'] : "" ?><br>
        <label for="description">Description : </label>
        <textarea name="description" id="description" cols="50" rows="3"></textarea><?= isset($erreurs['description']) ? $erreurs['description'] : "" ?><br>
        <label for="prix">Prix : </label>
        <input type="text" name="prix" required><?= isset($erreurs['prix']) ? $erreurs['prix'] : "" ?><br>
        <label for="quantite">Quantité : </label>
        <input type="number" name="quantite" required><?= isset($erreurs['quantite']) ? $erreurs['quantite'] : "" ?><br>
        <table>
            <?php if (count($categories) > 0) : ?>
                <label>Categorie du produit</label>
                <select name="categorie">
                    <?php foreach ($categories as $row) : ?>
                        <option value="<?= $row["categorie_id"] ?>"><?= $row["categorie_nom"] ?></option>
                    <?php endforeach; ?>
                </select>
        </table>
    <?php else : ?>
        <p class="erreur">Aucune categorie trouvé.</p>
    <?php endif; ?>

    <input type="submit" name="envoi" value="Ajouter !">
    </form>
</body>

</html>