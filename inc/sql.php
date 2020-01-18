<?php

/**
 * Fonction errSQL,
 * Auteur   : soushi888,
 * Date     : 17-01-2020,
 * But      : afficher le message d'erreur de la dernière requête SQL,
 * Input    : $conn = contexte de connexion,
 * Output   : aucun.
 */
function errSQL($conn)
{
?>
    <p>Erreur de requête : <?php echo mysqli_errno($conn) . " – " . mysqli_error($conn) ?></p>
<?php
}

/** 
 * Fonction listerCommandes,
 * Auteur   : Soushi888,
 * Date     : 2020-01-17,
 * But      : Récupérer les commandes avec les données associées,
 * Input    : $conn = contexte de connexion,
 *            $recherche = chaîne de caractères pour la recherche de commande par nom ou prénom de client, id de commande, nom ded produit ou date (optionnel),
 * Output   : $liste = tableau des lignes de la commande SELECT.
 */
function listerCommandes($conn, $recherche = "")
{
    $recherche = "%" . $recherche . "%";

    $req = "SELECT
    C.commande_id as 'Numéro de commande',
    CONCAT(CL.client_prenom, ' ', CL.client_nom) as 'Nom du client',
    SUBSTRING(C.commande_date, 1, 10) as 'Date',
    P.produit_nom as 'Produit',
    CP.commande_produit_quantite as 'Quantité',
    P.produit_prix as 'Prix',
    C.commande_adresse_livraison as 'Adresse',
    C.commande_commentaires as 'Commentaires',
    C.commande_etat as 'État'
    FROM
        commandes as C
    INNER JOIN
        commandes_produits as CP on CP.fk_commande_id = C.commande_id
    INNER JOIN
        produits as P on P.produit_id = CP.fk_produit_id
    INNER JOIN
        clients as CL on CL.client_id = C.fk_client_id 
    WHERE (CL.client_prenom LIKE '$recherche') OR (CL.client_nom LIKE '$recherche') OR (C.commande_id LIKE '$recherche') OR (C.commande_etat LIKE '$recherche')
    ORDER BY `Numéro de commande` ASC";

    if ($result = mysqli_query($conn, $req, MYSQLI_STORE_RESULT)) {
        $nbResult = mysqli_num_rows($result);
        $liste = array();
        if ($nbResult) {
            mysqli_data_seek($result, 0);
            $commande_id = "";
            $commande_client = "";
            $commande_date = "";
            $commande_produit = "";
            $commande_produit_quantite = "";
            $commande_produit_prix = "";
            $commande_total_HT = "";
            $commande_total_TTC = "";
            $commande_adresse = "";
            $commande_commentaires = "";
            $commande_etat = "";
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                if ($commande_id !== $row['Numéro de commande']) {
                    if ($commande_id !== "") {
                        $liste[] = array(
                            'commande_id' => $commande_id,
                            'commande_client' => $commande_client,
                            'commande_date' => $commande_date,
                            'commande_produit' => $commande_produit,
                            'commande_produit_quantite' => $commande_produit_quantite,
                            'commande_produit_prix' => $commande_produit_prix,
                            'commande_total_ht' => $commande_total_HT,
                            'commande_total_ttc' => $commande_total_TTC,
                            'commande_adresse' => $commande_adresse,
                            'commande_commentaires' => $commande_commentaires,
                            'commande_etat' => $commande_etat
                        );
                    }
                    $commande_id = $row['Numéro de commande'];
                    $commande_client = $row['Nom du client'];
                    $commande_date = $row['Date'];
                    $commande_produit = [];
                    $commande_produit_prix = [];
                    $commande_produit_quantite = [];
                    $commande_adresse = $row['Adresse'];
                    $commande_commentaires = $row['Commentaires'];
                    $commande_etat = $row['État'];
                }
                $commande_produit[] = $row['Produit'];
                $commande_produit_prix[] = $row['Prix'] . " $";
                $commande_produit_quantite[] = $row['Quantité'];

                $commande_total_HT = 0;

                for ($i = 0; $i < count($commande_produit_prix); ++$i) {
                    $commande_total_HT = $commande_total_HT + intval($commande_produit_prix[$i]);
                }

                $commande_total_TTC = $commande_total_HT * 1.15;

                $commande_total_HT = $commande_total_HT . " $";
                $commande_total_TTC = $commande_total_TTC . " $";
            }
            $liste[] = array(
                'commande_id' => $commande_id,
                'commande_client' => $commande_client,
                'commande_date' => $commande_date,
                'commande_produit' => $commande_produit,
                'commande_produit_prix' => $commande_produit_prix,
                'commande_produit_quantite' => $commande_produit_quantite,
                'commande_total_ht' => $commande_total_HT,
                'commande_total_ttc' => $commande_total_TTC,
                'commande_adresse' => $commande_adresse,
                'commande_commentaires' => $commande_commentaires,
                'commande_etat' => $commande_etat
            );
            mysqli_free_result($result);
            return $liste;
        } else {
            errSQL($conn);
            exit;
        }
    }
}

/**
 * Fonction listerClients,
 * Auteur   : soushi888,
 * Date     : 17-01-2020,
 * But      : Récupérer les clients avec leurs données associées,
 * Input    : $conn = contexte de connexion,
 *            $recherche = chaîne de caractères pour la recherche de clients (optionnel),
 * Output   : $liste = tableau des lignes de la commande SELECT.
 */
function listerClients($conn, $recherche = "")
{
    $req = "SELECT * FROM clients AS C
            WHERE (C.client_nom LIKE ?) OR (C.client_prenom LIKE ?)";

    $stmt = mysqli_prepare($conn, $req);
    $recherche = "%" . $recherche . "%";

    mysqli_stmt_bind_param($stmt, "ss", $recherche, $recherche);

    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $nbResult = mysqli_num_rows($result);
        $liste = array();
        if ($nbResult) {
            mysqli_data_seek($result, 0);
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $liste[] = $row;
            }
        }
        mysqli_free_result($result);
        return $liste;
    } else {
        errSQL($conn);
        exit;
    }
}

/**
 * Fonction listerCategories,
 * Auteur   : soushi888,
 * Date     : 17-01-2020,
 * But      : Récupérer la liste des categories,
 * Input    : $conn = contexte de connexion,
 * Output   : $liste = tableau des lignes de la commande SELECT.
 */
function listerCategories($conn)
{
    $req = "SELECT * FROM catégories";
    if ($result = mysqli_query($conn, $req)) {
        $nbResult = mysqli_num_rows($result);
        $liste = array();
        if ($nbResult) {
            mysqli_data_seek($result, 0);
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $liste[] = $row;
            }
        }
        mysqli_free_result($result);
        return $liste;
    } else {
        errSQL($conn);
        exit;
    }
}

/**
 * Fonction listerProduits,
 * Auteur   : soushi888,
 * Date     : 17-01-2020,
 * But      : Récupérer les produits et les données associées,
 * Input    : $conn = contexte de connexion,
 *            $recherche = chaîne de caractères pour la recherche de produits (optionnel),
 * Output   : $liste = tableau des lignes de la commande SELECT.
 */
function listerProduits($conn, $recherche = "")
{
    $req = "SELECT 
                P.*,
                C.categorie_nom
            FROM
                produits AS P
            INNER JOIN
                catégories AS C ON C.categorie_id = P.fk_categorie_id
            WHERE P.produit_nom LIKE ?";

    $stmt = mysqli_prepare($conn, $req);
    $recherche = "%" . $recherche . "%";

    mysqli_stmt_bind_param($stmt, "s", $recherche);

    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $nbResult = mysqli_num_rows($result);
        $liste = array();
        if ($nbResult) {
            mysqli_data_seek($result, 0);
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $liste[] = $row;
            }
        }
        mysqli_free_result($result);
        return $liste;
    } else {
        errSQL($conn);
        exit;
    }
}

/**
 * Fonction listerProduits,
 * Auteur   : soushi888,
 * Date     : 17-01-2020,
 * But      : Récupérer les produits et les données associées,
 * Input    : $conn = contexte de connexion,
 *            $recherche = chaîne de caractères pour la recherche de produits (optionnel),
 * Output   : $liste = tableau des lignes de la commande SELECT.
 */
function listerUtilisateurs($conn, $recherche = "")
{
    $req = "SELECT
	            U.*
            FROM
                utilisateurs as U
            WHERE U.utilisateur_email LIKE ?";

    $stmt = mysqli_prepare($conn, $req);
    $recherche = "%" . $recherche . "%";

    mysqli_stmt_bind_param($stmt, "s", $recherche);

    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $nbResult = mysqli_num_rows($result);
        $liste = array();
        if ($nbResult) {
            mysqli_data_seek($result, 0);
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $liste[] = $row;
            }
        }
        mysqli_free_result($result);
        return $liste;
    } else {
        errSQL($conn);
        exit;
    }
}