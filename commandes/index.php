<?php
require_once("../inc/connectDB.php");
require_once("../inc/sql.php");

session_start();

if (empty($_SESSION)) 
    header("Location: ../login.php");

$recherche = isset($_POST['recherche']) ? trim($_POST['recherche']) : "";

$liste = ListerCommandes($conn, $recherche);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Listes de commandes</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <h1>Liste des commandes</h1>
    <h2>
        <pre><?= $_SESSION['utilisateur']["utilisateur_nom"] . ", " . $_SESSION['utilisateur']["utilisateur_prenom"] . " : " . $_SESSION['utilisateur']["utilisateur_type"] ?></pre>
    </h2>
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

    <form id="recherche" action="" method="post">
        <fieldset>
            <label>Recherche par ID commande, nom client ou état commande : </label>
            <input type="text" name="recherche" value="<?= $recherche ?>" placeholder="ID, nom ou état">
            <input type="submit" value="Recherchez">
            <button class="ajout"><a href="ajout.php">Ajouter</a></button>
        </fieldset>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Nom du client</th>
            <th>Date</th>
            <th>Produits</th>
            <th>Prix</th>
            <th>Quant.</th>
            <th>Total HT</th>
            <th>Total TTC</th>
            <th>Adresse</th>
            <th>Commentaires</th>
            <th>État</th>
            <th>Actions</th>
        </tr>

        <?php foreach ($liste as $row) :
        ?>
            <tr>
                <td style="text-align: center;"><?= $row["commande_id"] ?></td>
                <td><?= $row["commande_client"] ?></td>
                <td><?= $row["commande_date"] ?></td>
                <td><?= implode("<br>", $row["commande_produit"]) ?></td>
                <td style="text-align: center;"><?= implode("<br>", $row["commande_produit_prix"]) ?></td>
                <td style="text-align: center;"><?= implode("<br>", $row["commande_produit_quantite"]) ?></td>
                <td><?= $row["commande_total_ht"] ?></td>
                <td><?= $row["commande_total_ttc"] ?></td>
                <td><?php echo $row["commande_adresse"];
                    echo isset($row["commande_adresse2"]) ? "<br>" . $row["commande_adresse2"] : "";
                    echo "<br>" . $row["commande_adresse_ville"] . ", " . $row["commande_adresse_cp"] .
                        "<br>Québec, Canada" ?></td>
                <td><?= $row["commande_commentaires"] ?></td>
                <td><?= $row["commande_etat"] ?></td>
                <td><a href="#">modifier</a> <a href="#">supprimer</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>