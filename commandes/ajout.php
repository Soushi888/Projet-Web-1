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
        "client_cp" => $_POST["client_cp"],
        "commande_commentaires" => $_POST["commande_commentaires"]
    );

    foreach ($_POST as $produit => $quantite) {
        // ++$i;
        if ($quantite != "" && $quantite != 0 &&  is_numeric($produit)) {
            $_SESSION["commande"]["info_commande"][] = ["produit" => $produit, "quantité" => $quantite];
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
                <input name="client_adresse2" id="client_adresse2" type="text"></label>
            <label for="client_ville">Ville :
                <input name="client_ville" id="client_ville" type="text" required></label>
            <label for="client_cp">Code postal :
                <input name="client_cp" id="client_cp" type="text" required></label><br>
        </fieldset>
        <fieldset>
            <legend>Information commande</legend>
            <label for="commande_commentaire">Commentaires : </label>
            <textarea name="commande_commentaires" id="commande_commentaires" cols="100" rows="10"></textarea>
        </fieldset>
        <button form="commande" type="submit" name="envoi">Commander</button>
    </form>

    <!-- Tableau récapitulatif de la commande -->
    <?php if (isset($_SESSION["commande"]["info_commande"])) : ?>
        <section class="tableau-commande">
            <h2>Confirmez la commande ?</h2>
            <table>
                <tr>
                    <th>Produit</th>
                    <th>Quantité</th>
                    <th>Prix</th>
                </tr>
                <?php
                $i = 0;
                foreach ($_SESSION["commande"]["info_commande"] as $commande) :
                    $produit = LireProduit($conn, $commande["produit"]);
                ?>
                    <tr>
                        <td><?= $produit["produit_nom"] ?></td>
                        <td><?= $commande["quantité"] ?></td>
                        <?php $prix[] = $commande["quantité"] * $produit["produit_prix"]; ?>
                        <td><?= $prix[$i] ?> $</td>
                    </tr>
                <?php
                    $i++;
                endforeach; ?>
            </table>
            <p class="total">Sous-total =
                <?php
                $sous_total = 0;
                for ($i = 0; $i < count($prix); ++$i) {
                    $sous_total += $prix[$i];
                }
                echo $sous_total . " $"; ?>
                <br>Total =
                <?php
                $total = $sous_total * 1.15;
                echo $total . " $";
                ?>
            </p>
            <section>
                <form action="" method="post">
                    <input type="submit" name="confirme" value="OUI">
                    <input type="submit" name="confirme" value="NON">
                </form>
            </section>
        </section>
    <?php else : ?>
        <p class="erreur">Veuillez selectionner au moins un produit.</p>
    <?php endif;
    ?>

</body>

</html>