<?php
require_once("../inc/connectDB.php");
require_once("../inc/sql.php");
require_once("../inc/connectSession.php");

if (isset($_POST["envoi"])) {
    // print_r($_POST);
    ModifierClient($conn, $_POST);
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
    <h1>Modifier un utilisateur</h1>
    <h2>
        <pre><?= $_SESSION['utilisateur']["utilisateur_nom"] . ", " . $_SESSION['utilisateur']["utilisateur_prenom"] . " : " . $_SESSION['utilisateur']["utilisateur_type"] ?></pre>
    </h2>
    <?= isset($_SESSION["message"]) ? $_SESSION["message"] : "";

    include("../menu.php");
    ?>

    <main>
        <form action="" method="post">
            <input type="hidden" name="id" value="<?= $_SESSION["modification"]["client_id"] ?>">
            <label for="nom">Nom : </label>
            <input type="text" name="nom" value="<?= $_SESSION["modification"]["client_nom"] ?>" required><br>
            <label for="prenom">Prénom : </label>
            <input type="text" name="prenom" value="<?= $_SESSION["modification"]["client_prenom"] ?>" required><br>
            <label for="email">Email : </label>
            <input type="text" name="email" value="<?= $_SESSION["modification"]["client_email"] ?>" required><br>
            <label for="telephone">Téléphone : </label>
            <input type="text" name="telephone" value="<?= $_SESSION["modification"]["client_telephone"] ?>" required><br>
            <label for="adresse">Adresse : </label>
            <input type="text" name="adresse" value="<?= $_SESSION["modification"]["client_adresse"] ?>" required><br>
            <label for="adresse2">Adresse 2 (optionnel) :</label>
            <input type="text" name="adresse2" value="<?= $_SESSION["modification"]["client_adresse2"] ?>"><br>
            <label for="ville">Ville : </label>
            <input type="text" name="ville" value="<?= $_SESSION["modification"]["client_ville"] ?>" required><br>
            <label for="cp">Code Postal : </label>
            <input type="text" name="cp" value="<?= $_SESSION["modification"]["client_cp"] ?>" required><br>
            <input type="submit" name="envoi" value="Modifier !">
        </form>
    </main>

</body>

</html>