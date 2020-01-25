<?php
require_once("../inc/connectDB.php");
require_once("../inc/sql.php");
require_once("../inc/connectSession.php");

if (isset($_POST["envoi"])) {
    ModifierUtilisateur($conn, $_POST);
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
        <form action="" method="post">
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" value="<?= $_SESSION["modification"]["utilisateur_nom"] ?>" required><br>
            <label for="prenom">Prenom :</label>
            <input type="text" id="prenom" name="prenom" value="<?= $_SESSION["modification"]["utilisateur_prenom"] ?>" required><br>
            <label for="email">Email :</label>
            <input type="text" id="email" name="email" value="<?= $_SESSION["modification"]["utilisateur_email"] ?>" required><br>
            <label for="mdp">Mot de passe :</label>
            <input type="password" id="mdp" name="mdp" required><br>
            <label for="confirmation_mdp">Confirmer mot de passe :</label><!-- À valider -->
            <input type="password" id="confirmation_mdp" name="confirmation_mdp" required><br> <!-- À valider -->
            <label for="type">Type :</label>
            <select type="text" id="type" name="type" required>
                <option value="vendeur" <?= $_SESSION["modification"]["utilisateur_type"] == "vendeur" ? "selected" : "" ?>>Vendeur</option>
                <option value="gestionnaire" <?= $_SESSION["modification"]["utilisateur_type"] == "gestionnaire" ? "selected" : "" ?>>Gestionnaire</option>
                <option value="administrateur" <?= $_SESSION["modification"]["utilisateur_type"] == "administrateur" ? "selected" : "" ?>>Administrateur</option>
            </select><br>
            <input type="submit" id="envoi" value="Modifier !">
        </form>
    </main>

</body>

</html>