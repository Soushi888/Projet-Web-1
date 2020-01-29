<?php
require_once("../inc/connectDB.php");
require_once("../inc/sql.php");
require_once("../inc/connectSession.php");


if (isset($_POST["envoi"])) {

    // contrôles des champs saisis


    $erreurs = array();


    // validation nom
    $nom = trim($_POST['nom']);
    if (!preg_match("/^[a-zA-Z\sàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð-]+$/u", $nom)) {
        $erreurs['nom'] = "<p class='erreur margin_left'>Nom incorrect. Veuillez utiliser seulement des lettre et traits d'union.</p>";
    }

    // validation prénom
    $prenom = trim($_POST['prenom']);
    if (!preg_match("/^[a-zA-Z\sàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð-]+$/u", $prenom)) {
        $erreurs['prenom'] = "<p class='erreur margin_left'>Prénom incorrect. Veuillez utiliser seulement des lettre et traits d'union.</p>";
    }

    // validation email
    $email = trim($_POST['email']);
    if (!preg_match('/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,})+$/', $email)) {
        $erreurs['email'] = "<p class='erreur margin_left'>Email incorrect.</p>";
    }

    // validation telephone
    $telephone = trim($_POST['telephone']);
    if (!preg_match('/^(438|514)-[\d]{3}-[\d]{4}$/', $telephone)) {
        $erreurs['telephone'] = "<p class='erreur margin_left'>Le numéro de téléphone doit commencer par 438 ou 514 et être au format 'xxx-xxx-xxxx'.</p>";
    }

    // validation adresse
    $adresse = trim($_POST['adresse']);
    if (!preg_match("/^[\d]{1,5} [a-zA-ZsàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð-]+ [a-zA-ZsàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð -]+$/", $adresse)) {
        $erreurs['adresse'] = "<p class='erreur margin_left'>L'adresse doit être au format '[numero civique] [rue/avenue/boulevard] [nom de rue/avenue/boulevard]'.</p>";
    }

    $_POST["adresse2"] = trim($_POST["adresse2"]);
    // Si il n'y a pas d'adresse 2, alors adresse 2 est NULL
    if ($_POST["adresse2"] == "") { 
        $_POST["adresse2"] = NULL;
    }

    // validation ville
    $ville = trim($_POST['ville']);
    if (!preg_match("/^[a-zA-ZsàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð-]*$/", $ville)) {
        $erreurs['ville'] = "<p class='erreur margin_left'>La ville ne doit contenir que des lettres et des traits d'union.</p>";
    }

    // validation code postal
    $cp = trim($_POST['cp']);
    if (!preg_match("/^[a-zA-Z][\d][a-zA-z] [\d][a-zA-z][\d]$/", $cp)) {
        $erreurs['cp'] = "<p class='erreur margin_left'>Le code postal doit être au format 'X1Y 2Z3'.</p>";
    }

    // insertion dans la table clients si aucune erreur
    if (count($erreurs) === 0) {
        AjouterClient($conn, $_POST);
        header("Location: index.php");
    }
} ?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Ajouter un client</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <h1>Ajout d'un client</h1>
    <h2>
        <pre><?= $_SESSION['utilisateur']["utilisateur_nom"] . ", " . $_SESSION['utilisateur']["utilisateur_prenom"] . " : " . $_SESSION['utilisateur']["utilisateur_type"] ?></pre>
    </h2>

    <?php include("../menu.php"); ?>

    <form action="" method="post">
        <label for="nom">Nom : </label>
        <input type="text" name="nom" value="<?= isset($_POST['nom']) ? $_POST['nom'] : "" ?>" required><?= isset($erreurs['nom']) ? $erreurs['nom'] : "" ?><br>
        <label for="prenom">Prénom : </label>
        <input type="text" name="prenom" value="<?= isset($_POST['prenom']) ? $_POST['prenom'] : "" ?>" required><?= isset($erreurs['prenom']) ? $erreurs['prenom'] : "" ?><br>
        <label for="email">Email : </label>
        <input type="text" name="email" value="<?= isset($_POST['email']) ? $_POST['email'] : "" ?>" required><?= isset($erreurs['email']) ? $erreurs['email'] : "" ?><br>
        <label for="telephone">Téléphone : </label>
        <input type="text" name="telephone" value="<?= isset($_POST['telephone']) ? $_POST['telephone'] : "" ?>" required><?= isset($erreurs['telephone']) ? $erreurs['telephone'] : "" ?><br>
        <label for="adresse">Adresse : </label>
        <input type="text" name="adresse" value="<?= isset($_POST['adresse']) ? $_POST['adresse'] : "" ?>" required><?= isset($erreurs['adresse']) ? $erreurs['adresse'] : "" ?><br>
        <label for="adresse2">Adresse 2 (optionnel) :</label>
        <input type="text" name="adresse2"><br>
        <label for="ville">Ville : </label>
        <input type="text" name="ville" value="<?= isset($_POST['ville']) ? $_POST['ville'] : "" ?>" required><?= isset($erreurs['ville']) ? $erreurs['ville'] : "" ?><br>
        <label for="cp">Code Postal : </label>
        <input type="text" name="cp" value="<?= isset($_POST['cp']) ? $_POST['cp'] : "" ?>" required><?= isset($erreurs['cp']) ? $erreurs['cp'] : "" ?><br>
        <input type="submit" name="envoi" value="Ajouter !">
    </form>
</body>

</html>