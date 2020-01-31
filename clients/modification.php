<?php
require_once("../inc/connectDB.php");
require_once("../inc/sql.php");
require_once("../inc/connectSession.php");

$titre = "Modifier un client";

$liste = ListerClients($conn);

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
    if (!preg_match("/^[a-zA-ZsàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð-]+$/", $ville)) {
        $erreurs['ville'] = "<p class='erreur margin_left'>La ville ne doit contenir que des lettres et des traits d'union.</p>";
    }

    // validation code postal
    $cp = trim($_POST['cp']);
    if (!preg_match("/^[a-zA-Z][\d][a-zA-z] [\d][a-zA-z][\d]$/", $cp)) {
        $erreurs['cp'] = "<p class='erreur margin_left'>Le code postal doit être au format 'X1Y 2Z3'.</p>";
    }

    // insertion dans la table clients si aucune erreur
    if (count($erreurs) === 0) {
        ModifierClient($conn, $_POST);
        unset($_SESSION["modification"]);
        header("Location: index.php");
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Modifier un utilisateur</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <script src="../assets/js/validation/validation_clients.js"></script>
</head>

<body>
    
    <?= isset($_SESSION["message"]) ? $_SESSION["message"] : "";

    include("../header.php");
    include("../menu.php");
    ?>

    <main>
        <form action="" method="post">
            <input type="hidden" name="id" value="<?= $_SESSION["modification"]["client_id"] ?>">
            <label for="nom">Nom : </label>


            <input type="text" id="nom" name="nom" value="<?= $_SESSION["modification"]["client_nom"] ?>" required><?= isset($erreurs['nom']) ? $erreurs['nom'] : ""  ?>
            <span class="erreur" id="errNom"></span><br>

            <label for="prenom">Prénom : </label>
            <input type="text" id="prenom" name="prenom" value="<?= $_SESSION["modification"]["client_prenom"] ?>" required><?= isset($erreurs['prenom']) ? $erreurs['prenom'] : ""  ?>
            <span class="erreur" id="errPrenom"></span><br>
        
            <label for="email">Email : </label>
            <input type="text" id="email" name="email" value="<?= $_SESSION["modification"]["client_email"] ?>" required><?= isset($erreurs['email']) ? $erreurs['email'] : ""  ?>
            <span class="erreur" id="errEmail"></span><br>

            <label for="telephone">Téléphone : </label>
            <input type="text" id="telephone" name="telephone" value="<?= $_SESSION["modification"]["client_telephone"] ?>" required><?= isset($erreurs['telephone']) ? $erreurs['telephone'] : ""  ?>
            <span class="erreur" id="errTelephone"></span><br>

            <label for="adresse">Adresse : </label>
            <input type="text" id="adresse" name="adresse" value="<?= $_SESSION["modification"]["client_adresse"] ?>" required><?= isset($erreurs['adresse']) ? $erreurs['adresse'] : ""  ?>
            <span class="erreur" id="errAdresse"></span><br>

            <label for="adresse2">Adresse 2 (optionnel) :</label>
            <input type="text" id="adresse2" name="adresse2" value="<?= $_SESSION["modification"]["client_adresse2"] ?>"><br>

            <label for="ville">Ville : </label>
            <input type="text" id="ville" name="ville" value="<?= $_SESSION["modification"]["client_ville"] ?>" required><?= isset($erreurs['ville']) ? $erreurs['ville'] : ""  ?>
            <span class="erreur" id="errVille"></span><br>

            <label for="cp">Code Postal : </label>
            <input type="text" id="cp" name="cp" value="<?= $_SESSION["modification"]["client_cp"] ?>" required><?= isset($erreurs['cp']) ? $erreurs['cp'] : ""  ?>
            <span class="erreur" id="errCP"></span><br>

            <input type="submit" name="envoi" id="envoi" value="Modifier !">
        </form>
    </main>

</body>

</html>