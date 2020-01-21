<?php
require_once("../inc/connectDB.php");
require_once("../inc/sql.php");

$recherche = isset($_GET['recherche']) ? trim($_GET['recherche']) : "";

$liste = listerProduits($conn, $recherche);

$client_id = "";

if (isset($_POST["envoi"])) :
    $i = 0;
    $_SESSION["commande"]["info_client"] = array(
        "client_id" => $_POST["client_id"],
        "client_adresse" => $_POST["client_adresse"],
        "client_adresse2" => $_POST["client_adresse2"],
        "client_ville" => $_POST["client_ville"],
        "client_cp" => $_POST["client_cp"]
    );

    foreach ($_POST as $produit => $quantite) {
        // ++$i;
        if ($quantite != "" && $quantite != 0 &&  is_numeric($produit)) {
            $_SESSION["commande"][] = ["produit" => $produit, "quantité" => $quantite];
        }
    }
    // EnregistrerCommande($conn, $_SESSION["commande"], $client_id);
?>
    <pre><?= print_r($_SESSION) ?></pre>
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
                            <input type="number" min="0" max="<?= $row["produit_quantite"] ?>" name="<?= $row['produit_id'] ?>" placeholder="Quantité" form="commande">
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </fieldset>

        <fieldset>
            <legend>Informations client</legend>
            <label for="client_id">ID :
                <input name="client_id" id="client_id" type="number" placeholder="ID" required></label>
            <label for="client_adresse">Adresse :
                <input name="client_adresse" id="client_adresse" type="text" required></label>
            <label for="client_adresse2">Adresse2 :
                <input name="client_adresse2" id="client_adresse2" type="text" required></label>
            <label for="client_ville">Ville :
                <input name="client_ville" id="client_ville" type="text" required></label>
            <label for="client_cp">Code postal :
                <input name="client_cp" id="client_cp" type="text" required></label>
        </fieldset>

        <button form="commande" type="submit" name="envoi">Commander</button>
    </form>

</body>

</html>