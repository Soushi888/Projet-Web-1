<?php
require_once("../inc/connectDB.php");
require_once("../inc/sql.php");

$recherche = isset($_GET['recherche']) ? trim($_GET['recherche']) : "";

$liste = listerProduits($conn, $recherche);

$client_id = "";

if (isset($_POST["envoi"])) :
    $i = 1;
    $_SESSION["commande"] = array("client_id" => $_POST["client_id"]);

    foreach ($_POST as $produit => $quantite) {
        if ($quantite != "" && $quantite != 0) {
            $_SESSION["commande"][] = ["produit" => substr($produit, 1), "quantité" => $quantite];
        }
        ++$i;
    }
    // EnregistrerCommande($conn, $_SESSION["commande"], $client_id);
?>
    <pre><?= var_dump($_SESSION) ?></pre>
<?php endif; ?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Catalogue de ventes</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <h1>Catalogue de ventes</h1>

    <form id="recherche_form" action="" method="get">
        <fieldset>
            <legend>Recherche</legend>
            <label for="recherche">Recherche produit : </label>
            <input type="text" name="recherche" id="recherche" value="<?= $recherche ?>">
            <input type="submit" value="Recherchez">
        </fieldset>
    </form>

    <form id="commande" action="" method="post">
        <fieldset>
            <legend>Informations client</legend>
            <label for="client_id">Client ID : </label>
                <input id="client_id" name="client_id" type="number" placeholder="ID"> 
        </fieldset>
        <fieldset>
            <legend>Produits à commander</legend>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Descriprion</th>
                    <th>Prix</th>
                    <th>Quant.</th>
                    <th>Catégorie</th>
                    <th>Commander</th>
                </tr>

                <?php foreach ($liste as $row) :
                ?>
                    <tr>
                        <td style="text-align: center;"><?= $row["produit_id"] ?></td>
                        <td><?= $row["produit_nom"] ?></td>
                        <td><?= $row["produit_description"] ?></td>
                        <td><?= $row["produit_prix"] ?> $</td>
                        <td><?= $row["produit_quantite"] ?></td>
                        <td><?= $row["categorie_nom"] ?></td>
                        <td>
                            <input type="number" min="0" max="<?= $row["produit_quantite"] ?>" name="q<?= $row['produit_id'] ?>" placeholder="Quantité" form="commande">
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <button form="commande" type="submit" name="envoi">Commander</button>
    </form>
    </fieldset>

</body>

</html>