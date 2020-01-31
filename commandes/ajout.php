<?php
require_once("../inc/connectDB.php");
require_once("../inc/sql.php");
require_once("../inc/connectSession.php");

$titre = "Enregistrer une commande";

$recherche = isset($_GET['recherche']) ? trim($_GET['recherche']) : "";

// Pagination
$nombreProduits = NombreProduits($conn);
$nombrePages = ceil($nombreProduits / 10);

$pageActuelle = 1;

if (isset($_GET['page'])) {
    $pageActuelle = $_GET['page'];
} elseif ($pageActuelle > $nombrePages) {
    $pageActuelle = $nombrePages;
} else {
    $pageActuelle = 1;
}

$offset = ($pageActuelle - 1) * 10;

$liste = listerProduits($conn, $recherche, $offset, 10);

$recapitulatif = false;

$adresse2 = NULL;

if (isset($_POST["envoi"])) :

    // contrôles des champs saisis

    $erreurs = array();


    // Validation ID client
    $client_id = trim($_POST['client_id']);
    if (!is_numeric($client_id)) {
        $erreurs['client_id'] = "<p class='erreur margin_left'>Le ID du client doit être un numéro.</p>";
    }

    // Validation adresse de livraison 1 et 2
    $adresse = trim($_POST['adresse_livraison']);
    if (!preg_match("/^[\d]{1,5} [a-zA-ZsàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð-]+ [a-zA-ZsàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð -]+$/", $adresse)) {
        $erreurs['adresse_livraison'] = "<p class='erreur margin_left'>L'adresse doit être au format '[numero civique] [rue/avenue/boulevard] [nom de rue/avenue/boulevard]'.</p>";
    }

    $ville = trim($_POST['ville_livraison']);
    if (!preg_match("/^[a-zA-ZsàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð-]*$/", $ville)) {
        $erreurs['ville_livraison'] = "<p class='erreur margin_left'>La ville ne doit contenir que des lettres et des traits d'union.</p>";
    }

    $cp = trim($_POST['cp_livraison']);
    if (!preg_match("/^[a-zA-Z][\d][a-zA-z] [\d][a-zA-z][\d]$/", $cp)) {
        $erreurs['cp_livraison'] = "<p class='erreur margin_left'>Le code postal doit être au format 'X1Y 2Z3'.</p>";
    }

    $commentaires =  trim($_POST['commande_commentaires']);

    $_SESSION["commande"]["info_client"] = array(
        "client_id" => $client_id,
        "adresse_livraison" => $adresse,
        "adresse2_livraison" => $adresse2,
        "ville_livraison" => $ville,
        "cp_livraison" => $cp,
        "commande_commentaires" => $commentaires
    );



    foreach ($_POST as $produit => $quantite) {
        if ($quantite != "" && $quantite != 0 &&  is_numeric($produit)) {
            $_SESSION["commande"]["info_commande"][] = ["produit" => $produit, "quantité" => $quantite];
        }
    }

    if (count($erreurs) == 0) :
        $recapitulatif = true;
    endif;
endif;

if (isset($_POST["confirme"])) : ?>
    <?php if ($_POST["confirme"] == "OUI") :
        if ($adresse2 == "")  $_POST['adresse2'] = NULL;
        EnregistrerCommande($conn, $_SESSION["commande"]); ?>
        <p class="succes">Commande effectuée avec succès !</p>
    <?php unset($_SESSION["commande"]);

    elseif ($_POST["confirme"] == "NON") : ?>
        <p class="erreur">Commande non effectuée !</p>
<?php unset($_SESSION["commande"]);
    endif;
endif; ?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Catalogue de ventes</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <script src="../assets/js/validation/validation_commandes.js"></script>
</head>

<body>

    <pre><?= isset($_SESSION['commande']) ? var_dump($_SESSION['commande']) : "" ?></pre>

    <?php 
    include("../header.php");
    include("../menu.php"); ?>

    <!-- Tableau récapitulatif de la commande -->
    <?php if ($recapitulatif && isset($_SESSION['commande']['info_commande'])) : ?>
        <section id="tableau-commande">
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
    <?php
    elseif (isset($_SESSION["commande"]) && !isset($_SESSION["commande"]["info_commande"])) :
        unset($_SESSION["commande"]); ?>
        <p class="erreur">Veuillez selectionner au moins un produit.</p>
    <?php else :
        unset($_SESSION["commande"]);
    endif;
    ?>

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
            <legend>Informations livraison</legend>
            <label for="client_id">ID client :
                <input name="client_id" id="client_id" type="number" placeholder="ID" value="<?= isset($_SESSION["commande"]["info_client"]) ? $_SESSION["commande"]["info_client"]["client_id"] : "" ?>" required><?= isset($erreurs['client_id']) ? $erreurs['client_id'] : "" ?></label><span class="erreur" id="errID"></span>
            <label for="adresse_livraison">Adresse :
                <input name="adresse_livraison" id="adresse_livraison" type="text" placeholder="123 rue Masson" value="<?= isset($_SESSION["commande"]["info_client"]) ? $_SESSION["commande"]["info_client"]["adresse_livraison"] : "" ?>" required><?= isset($erreurs['adresse_livraison']) ? $erreurs['adresse_livraison'] : "" ?></label><span class="erreur" id="errAdresse"></span>
            <label for="adresse2_livraison">Adresse2 :
                <input name="adresse2_livraison" id="adresse2_livraison" type="text" placeholder="app. 12" value="<?= isset($_SESSION["commande"]["info_client"]) ? $_SESSION["commande"]["info_client"]["adresse2_livraison"] : "" ?>"></label>
            <label for="ville_livraison">Ville :
                <input name="ville_livraison" id="ville_livraison" type="text" placeholder="Montréal" value="<?= isset($_SESSION["commande"]["info_client"]) ? $_SESSION["commande"]["info_client"]["ville_livraison"] : "" ?>" required><?= isset($erreurs['ville_livraison']) ? $erreurs['ville_livraison'] : "" ?></label><span class="erreur" id="errVille"></span>
            <label for="cp_livraison">Code postal :
                <input name="cp_livraison" id="cp_livraison" type="text" placeholder="X1Y 2Z3" value="<?= isset($_SESSION["commande"]["info_client"]) ? $_SESSION["commande"]["info_client"]["cp_livraison"] : "" ?>" required><?= isset($erreurs['cp_livraison']) ? $erreurs['cp_livraison'] : "" ?></label><span class="erreur" id="errCP"></span>
        </fieldset>

        <p>[<?= $offset + 1 ?>-<?= (($offset + 1) + 9) > $nombreProduits ? $nombreProduits : (($offset + 1) + 9) ?>] / <?= $nombreProduits ?> produits affichés</p>

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

        <h3 class="pagination">Nombre de page :
        <?php
        for ($i = 1; $i <= $nombrePages; ++$i) {
            if ($i == $pageActuelle) {
                echo "[" . $i . "] ";
            } else {
                echo "<a href=ajout.php?page=" . $i . ">" . $i . "</a> ";
            }
        }
        ?>
    </h3>

        <fieldset>
            <legend>Information commande</legend>
            <label for="commande_commentaire">Commentaires : </label>
            <textarea name="commande_commentaires" id="commande_commentaires" cols="100" rows="10"><?= isset($_SESSION["commande"]) ? $_SESSION["commande"]["info_client"]["commande_commentaires"] : "" ?></textarea>
        </fieldset><br>
        <button form="commande" type="submit" id="envoi" name="envoi">Commander</button>
    </form>

</body>

</html>