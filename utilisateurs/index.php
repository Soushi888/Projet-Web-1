<?php
require_once("../inc/connectDB.php");
require_once("../inc/sql.php");
require_once("../inc/connectSession.php");


$recherche = isset($_POST['recherche']) ? trim($_POST['recherche']) : "";

$liste = ListerUtilisateurs($conn, $recherche);

if (isset($_POST["confirme"])) :
    if ($_POST["confirme"] == "OUI") :
        SupprimerUtilisateur($conn, $_SESSION["id_suppression"]);
        header("Location: index.php");
    elseif ($_POST["confirme"] == "NON") :
        echo "<p class='erreur'>Suppression non effectuée !</p>";
        unset($_SESSION["id_suppression"]);
    endif;
endif; ?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Listes des utilisateurs</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <h1>Liste des utilisateurs</h1>
    <h2>
        <pre><?= $_SESSION['utilisateur']["utilisateur_nom"] . ", " . $_SESSION['utilisateur']["utilisateur_prenom"] . " : " . $_SESSION['utilisateur']["utilisateur_type"] ?></pre>
    </h2>
    <?= isset($_SESSION["message"]) ? $_SESSION["message"] : ""; ?>


    <nav id="main_menu">
        <fieldset>
            <legend>Navigation</legend>
            <fieldset>
                <legend>Vendeur</legend>
                <a href="../clients/index.php">Clients</a><a href="../commandes/index.php">Commandes</a>
            </fieldset>
            <?php if ($_SESSION['utilisateur']["utilisateur_type"] == "gestionnaire" || $_SESSION['utilisateur']["utilisateur_type"] == "administrateur") : ?>
                <fieldset>
                    <legend>Gestionnaire</legend>
                    <a href="../produits/index.php">Produits</a><a href="../categories/index.php">Catégories</a>
                </fieldset>
            <?php endif; ?>
            <?php if ($_SESSION['utilisateur']["utilisateur_type"] == "administrateur") : ?>
                <fieldset>
                    <legend>Administrateur</legend>
                    <a href="../utilisateurs/index.php">Utilisateurs</a>
                </fieldset>
            <?php endif; ?>
            <button><a href="../deconnexion.php">Déconnexion</a></button>
        </fieldset>
    </nav>

    <?php if ($_SESSION["utilisateur"]["utilisateur_type"] !== "administrateur") : ?>
        <p class='erreur'>Accès refusé, vous devez être administrateur pour gérer les utilisateurs.</p><br>
    <?php exit;
    endif; ?>

    <form id="recherche" action="" method="post">
        <fieldset>
            <label>Recherche utilisateur : </label>
            <input type="text" name="recherche" value="<?= $recherche ?>">
            <input type="submit" value="Recherchez">
            <button class="ajout"><a href="ajout.php">Ajouter</a></button>
        </fieldset>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Email</th>
            <th>Type</th>
            <th>Actions</th>
        </tr>

        <?php foreach ($liste as $row) :
        ?>
            <tr>
                <td style="text-align: center;"><?= $row["utilisateur_id"] ?></td>
                <td><?= $row["utilisateur_nom"] ?></td>
                <td><?= $row["utilisateur_prenom"] ?></td>
                <td><?= $row["utilisateur_email"] ?></td>
                <td><?= $row["utilisateur_type"] ?></td>
                <td>
                    <form action="" method="post">
                        <input type="hidden" name="id" value="<?= $row["utilisateur_id"] ?>">
                        <input type="hidden" name="email" value="<?= $row["utilisateur_email"] ?>">
                        <input type="submit" value="Supprimer">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <?php if (isset($_POST["id"])) :
        $_SESSION["id_suppression"] = $_POST["id"];
        $utilisateur_supression = LireUtilisateur($conn, $_POST["email"]);
    ?>

        <form action="" method="post">
            <h2>Confirmer suppression de l'utilisateur <?= $utilisateur_supression["utilisateur_nom"] . " " . $utilisateur_supression["utilisateur_prenom"] . " de type " . $utilisateur_supression["utilisateur_type"] ?> ?</h2>
            <input type="submit" name="confirme" value="OUI">
            <input type="submit" name="confirme" value="NON">
        </form>

    <?php endif; ?>

</body>

</html>