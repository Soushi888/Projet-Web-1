<?php
require_once("../inc/connectDB.php");
require_once("../inc/sql.php");
require_once("../inc/connectSession.php");

$titre = "Modifier un utilisateur";

if (isset($_POST["envoi"])) {

    // contrôles des champs saisis
    // ---------------------------

    $erreurs = array();


    //-----------------Validation---Nom---------------
    $nom = trim($_POST['nom']);
    if (!preg_match("/^[a-zA-Z\sàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð-]+$/u", $nom)) {
        $erreurs['nom'] = "<p class='erreur margin_left'>Nom incorrect. Veuillez utiliser seulement des lettre et traits d'union.</p>";
    }

    //-----------------Validation---Prenom---------------
    $prenom = trim($_POST['prenom']);
    if (!preg_match("/^[a-zA-Z\sàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð-]+$/u", $prenom)) {
        $erreurs['prenom'] = "<p class='erreur margin_left'>Prénom incorrect. Veuillez utiliser seulement des lettre et traits d'union.</p>";
    }

    //-----------------Validation---email---------------
    $email = trim($_POST['email']);
    if (!preg_match('/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/', $email)) {
        $erreurs['email'] = "<p class='erreur margin_left'>email incorrect.</p>";
    }

    //-----------------Validation---mot de passe---------------
    $mdp = trim($_POST['mdp']);
    if (!preg_match('/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/', $mdp)) {
        $erreurs['mdp'] = "<p class='erreur margin_left'>Le mot de passe doit inclure une lettre majuscule, une minuscule, un chiffre, un caractère special et faire au moins 8 caractères de long.</p>";
    }

    //-----------------Validation---mot de passe---------------
    $mdp_confirm = trim($_POST['mdp_confirm']);
    if ($mdp_confirm !== $mdp) {
        $erreurs['mdp_confirm'] = "<p class='erreur margin_left'>Vos deux mots de passes sont différents.</p>";
    }

    // insertion dans la table clients si aucune erreur
    // -----------------------------------------------

    if (count($erreurs) === 0) {
        ModifierUtilisateur($conn, $_POST);
        unset($_SESSION["modification"]);
        header("Location: index.php");
    } else {
        $retSQL = "Modification non effectuée.";
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Modifier un utilisateur</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <script src="../assets/js/validation/validation_utilisateurs.js"></script>
</head>

<body>
    <?= isset($_SESSION["message"]) ? $_SESSION["message"] : "";

    include("../header.php"); // Entête
    include("../menu.php"); // Menu de navigation

    // Bloquer l'accès si l'utilisateur n'a pas les bons privilèges
    if ($_SESSION["utilisateur"]["utilisateur_type"] !== "administrateur") : ?>
        <p class='erreur'>Accès refusé, vous devez être administrateur pour gérer les utilisateurs.</p><br>
    <?php exit;
    endif; ?>

    <main>
        <form action="" method="post">
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" value="<?= $_SESSION["modification"]["utilisateur_nom"] ?>" required><?= isset($erreurs['nom']) ? $erreurs['nom'] : "" ?>
            <span class="erreur" id="errNom"></span><br>

            <label for="prenom">Prenom :</label>
            <input type="text" id="prenom" name="prenom" value="<?= $_SESSION["modification"]["utilisateur_prenom"] ?>" required><?= isset($erreurs['prenom']) ? $erreurs['prenom'] : "" ?>
            <span class="erreur" id="errPrenom"></span><br>

            <label for="email">Email :</label>
            <input type="text" id="email" name="email" value="<?= $_SESSION["modification"]["utilisateur_email"] ?>"><?= isset($erreurs['email']) ? $erreurs['email'] : "" ?>
            <span class="erreur" id="errEmail"></span><br>

            <label for="mdp">Mot de passe :</label>
            <input type="password" id="mdp" name="mdp" value="<?= $_SESSION["modification"]["utilisateur_mdp"] ?>" required><?= isset($erreurs['mdp']) ? $erreurs['mdp'] : "" ?>
            <span class="erreur" id="errMdp"></span><br>

            <label for="mdp_confirm">Confirmer mot de passe :</label>
            <input type="password" id="mdp_confirm" name="mdp_confirm" value="<?= $_SESSION["modification"]["utilisateur_mdp"] ?>" required><?= isset($erreurs['mdp_confirm']) ? $erreurs['mdp_confirm'] : "" ?>
            <span class="erreur" id="errConfMdp"></span><br>

            <label for="type">Type :</label>
            <select type="text" id="type" name="type" required>
                <option value="vendeur" <?= $_SESSION["modification"]["utilisateur_type"] == "vendeur" ? "selected" : "" ?>>Vendeur</option>
                <option value="gestionnaire" <?= $_SESSION["modification"]["utilisateur_type"] == "gestionnaire" ? "selected" : "" ?>>Gestionnaire</option>
                <option value="administrateur" <?= $_SESSION["modification"]["utilisateur_type"] == "administrateur" ? "selected" : "" ?>>Administrateur</option>
            </select><br>
            <input type="submit" id="envoi" name="envoi" value="Modifier !">
        </form>
    </main>

</body>

</html>