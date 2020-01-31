<?php

/**
 * Fonction ErrSQL,
 * Auteur   : soushi888,
 * Date     : 17-01-2020,
 * But      : afficher le message d'erreur de la dernière requête SQL,
 * Input    : $conn = contexte de connexion,
 * Output   : aucun.
 */
function ErrSQL($conn)
{
?>
    <p>Erreur de requête : <?php echo mysqli_errno($conn) . " – " . mysqli_error($conn) ?></p>
    <?php
}

/**
 * Fonction ControlerUtilisateur,
 * Auteur   : Soushi888,
 * Date     : 23-01-2020,
 * But      : Contrôler l'authentification de l'utilisateur dans la table utilisateurs,
 * Input    : $conn = contexte de connexion,
 *                       $utilisateur_courriel,
 *                       $utilisateur_mot_de_passe,
 * Output   : 1 si utilisateur avec $utilisateur_courriel et $utilisateur_mot_de_passe trouvé sinon 0.
 */
function controlerUtilisateur($conn, $utilisateur_email, $utilisateur_mdp)
{
    $req = "SELECT * FROM utilisateurs
            WHERE utilisateur_email=? AND utilisateur_mdp = SHA2(?, 256)";
    $stmt = mysqli_prepare($conn, $req);
    mysqli_stmt_bind_param($stmt, "ss", $utilisateur_email, $utilisateur_mdp);
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_num_rows($result);
    } else {
        errSQL($conn);
        exit;
    }
}

/**
 * Fonction LireUtilisateur
 * Auteur : Soushi888
 * Date   : 2020-01-23
 * But    : Récupérer l'utilisateur par son identifiant email unique
 * Arguments en entrée : $conn = contexte de connexion
 *                       $id   = email unique
 * Valeurs de retour   : $row  = tableau de la ligne correspondant à l'email unique,
 *                               tableau vide si non trouvée.
 */
function LireUtilisateur($conn, $email)
{

    $req = "SELECT * FROM utilisateurs WHERE utilisateur_email='" . $email . "'";
    // die($req);
    if ($result = mysqli_query($conn, $req)) {
        $nbResult = mysqli_num_rows($result);
        $row = array();
        if ($nbResult) {
            mysqli_data_seek($result, 0);
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        }
        mysqli_free_result($result);
        return $row;
    } else {
        errSQL($conn);
        exit;
    }
}

/**
 * Fonction LireCategorie
 * Auteur : Soushi888
 * Date   : 2020-01-23
 * But    : Récupérer la categorie par son identifiant clé primaire
 * Arguments en entrée : $conn = contexte de connexion
 *                       $id   = clé primaire
 * Valeurs de retour   : $row  = tableau de la ligne correspondant à l'clé primaire,
 *                               tableau vide si non trouvée.
 */
function LireCategorie($conn, $id)
{

    $req = "SELECT * FROM categories WHERE categorie_id='" . $id . "'";
    // die($req);
    if ($result = mysqli_query($conn, $req)) {
        $nbResult = mysqli_num_rows($result);
        $row = array();
        if ($nbResult) {
            mysqli_data_seek($result, 0);
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        }
        mysqli_free_result($result);
        return $row;
    } else {
        errSQL($conn);
        exit;
    }
}

/**
 * Fonction LireClient
 * Auteur : Soushi888
 * Date   : 2020-01-24
 * But    : Récupérer la client par son identifiant clé primaire
 * Arguments en entrée : $conn = contexte de connexion
 *                       $id   = clé primaire
 * Valeurs de retour   : $row  = tableau de la ligne correspondant à l'clé primaire,
 *                               tableau vide si non trouvée.
 */
function LireClient($conn, $id)
{

    $req = "SELECT * FROM clients WHERE client_id='" . $id . "'";
    // die($req);
    if ($result = mysqli_query($conn, $req)) {
        $nbResult = mysqli_num_rows($result);
        $row = array();
        if ($nbResult) {
            mysqli_data_seek($result, 0);
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        }
        mysqli_free_result($result);
        return $row;
    } else {
        errSQL($conn);
        exit;
    }
}

/**
 * Fonction LireProduit
 * Auteur : Soushi888
 * Date   : 2020-01-21
 * But    : Récupérer le produit par son identifiant clé primaire
 * Arguments en entrée : $conn = contexte de connexion
 *                       $id   = clé primaire
 * Valeurs de retour   : $row  = ligne correspondant à la clé primaire,
 *                               tableau vide si non trouvée.
 */
function LireProduit($conn, $id)
{

    $req = "SELECT * FROM produits WHERE produit_id=" . $id;

    if ($result = mysqli_query($conn, $req)) {
        $nbResult = mysqli_num_rows($result);
        $row = array();
        if ($nbResult) {
            mysqli_data_seek($result, 0);
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        }
        mysqli_free_result($result);
        return $row;
    } else {
        errSQL($conn);
        exit;
    }
}

/**
 * Fonction LireCommande
 * Auteur : Soushi888
 * Date   : 2020-01-21
 * But    : Récupérer le commande par son identifiant clé primaire
 * Arguments en entrée : $conn = contexte de connexion
 *                       $id   = clé primaire
 * Valeurs de retour   : $row  = ligne correspondant à la clé primaire,
 *                               tableau vide si non trouvée.
 */
function LireCommande($conn, $id)
{

    $req = "SELECT * FROM commandes WHERE commande_id=" . $id;

    if ($result = mysqli_query($conn, $req)) {
        $nbResult = mysqli_num_rows($result);
        $row = array();
        if ($nbResult) {
            mysqli_data_seek($result, 0);
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        }
        mysqli_free_result($result);
        return $row;
    } else {
        errSQL($conn);
        exit;
    }
}

/**
 * Fonction ProduitLastId,
 * Auteur   : Soushi888,
 * Date     : 2020-01-24,
 * But      : Récupérer le dernier id de la table produit,
 * Input    : $conn = contexte de connexion,
 * Output   : $last_id = dernier id de la table.
 */
function ProduitLastId($conn)
{
    $req = "SELECT MAX(produit_id) FROM produits";
    if ($result = mysqli_query($conn, $req)) {
        $nbResult = mysqli_num_rows($result);
        $last_id = "";
        if ($nbResult) {
            mysqli_data_seek($result, 0);
            $last_id = mysqli_fetch_row($result);
            $last_id = $last_id[0];
        }
        mysqli_free_result($result);
        return $last_id;
    }
}

/**
 * Fonction CategorieLastId,
 * Auteur   : Soushi888,
 * Date     : 2020-01-24,
 * But      : Récupérer le dernier id de la table categorie,
 * Input    : $conn = contexte de connexion,
 * Output   : $last_id = dernier id de la table.
 */
function CategorieLastId($conn)
{
    $req = "SELECT MAX(categorie_id) FROM categories";
    if ($result = mysqli_query($conn, $req)) {
        $nbResult = mysqli_num_rows($result);
        $last_id = "";
        if ($nbResult) {
            mysqli_data_seek($result, 0);
            $last_id = mysqli_fetch_row($result);
            $last_id = $last_id[0];
        }
        mysqli_free_result($result);
        return $last_id;
    }
}

/**
 * Fonction NombreProduitsParCommandes,
 * Auteur   : Soushi888,
 * Date     : 30-01-2020,
 * But      : Récupérer le nombre total de lignes de la table commandes,
 * Input    : $conn = contexte de connexion,
 * Output   : $nombre = nombre total de lignes de la table commandes.
 */
function NombreProduitsParCommandes($conn)
{
    $req = "SELECT fk_commande_id, COUNT(fk_produit_id) AS 'nombre_de_produits' FROM commandes_produits GROUP BY fk_commande_id";

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
 * Fonction NombreCommandes,
 * Auteur   : Soushi888,
 * Date     : 30-01-2020,
 * But      : Récupérer le nombre total de lignes de la table commandes,
 * Input    : $conn = contexte de connexion,
 * Output   : $nombre = nombre total de lignes de la table commandes.
 */
function NombreCommandes($conn)
{
    $req = "SELECT COUNT(commande_id) AS 'nombre' FROM commandes";

    if ($result = mysqli_query($conn, $req)) {
        $nbResult = mysqli_num_rows($result);
        if ($nbResult) {
            mysqli_data_seek($result, 0);
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $nombre = $row["nombre"];
        }
        mysqli_free_result($result);
        return $nombre;
    } else {
        errSQL($conn);
        exit;
    }
}

/** 
 * Fonction ListerCommandes,
 * Auteur   : Soushi888,
 * Date     : 2020-01-17,
 * But      : Récupérer les commandes avec les données associées, calcule le total HT et le total TTC
 * Input    : $conn = contexte de connexion,
 *            $recherche = chaîne de caractères pour la recherche de commande par nom ou prénom de client, id de commande, nom ded produit ou date (optionnel),
 * Output   : $liste = tableau des lignes de la commande SELECT.
 */
function ListerCommandes($conn, $recherche = "", $offset, $nbrParPage)
{
    $recherche = "%" . $recherche . "%";

    $req = "SELECT
    C.commande_id as 'commande_id',
    CONCAT(CL.client_prenom, ' ', CL.client_nom) as 'client_nom',
    SUBSTRING(C.commande_date, 1, 10) as 'date',
    SUBSTRING(C.commande_date, 12, 8) as 'heure',
    P.produit_nom as 'produit',
    CP.commande_produit_quantite as 'quantite',
    P.produit_prix as 'prix',
    C.commande_adresse as 'adresse',
    C.commande_adresse2 as 'adresse2',
    C.commande_adresse_ville as 'adresse_ville',
    C.commande_adresse_cp as 'adresse_cp',
    C.commande_commentaires as 'commentaires',
    C.commande_etat as 'etat'
    FROM
        commandes as C
    INNER JOIN
        commandes_produits as CP on CP.fk_commande_id = C.commande_id
    INNER JOIN
        produits as P on P.produit_id = CP.fk_produit_id
    INNER JOIN
        clients as CL on CL.client_id = C.fk_client_id 
    WHERE (CL.client_prenom LIKE '$recherche') OR (CL.client_nom LIKE '$recherche') OR (C.commande_id LIKE '$recherche') OR (C.commande_etat LIKE '$recherche')
    ORDER BY `commande_id` ASC
    LIMIT $offset, $nbrParPage";
    // die($req);
    if ($result = mysqli_query($conn, $req, MYSQLI_STORE_RESULT)) {
        $nbResult = mysqli_num_rows($result);
        $liste = array();
        if ($nbResult) {
            mysqli_data_seek($result, 0);
            $commande_id = "";
            $commande_client = "";
            $commande_date = "";
            $commande_heure = "";
            $commande_produit = "";
            $commande_produit_quantite = "";
            $commande_produit_prix = "";
            $commande_total_HT = "";
            $commande_total_TTC = "";
            $commande_adresse = "";
            $commande_adresse2 = "";
            $commande_adresse_ville = "";
            $commande_adresse_cp = "";
            $commande_commentaires = "";
            $commande_etat = "";
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                if ($commande_id !== $row['commande_id']) {
                    if ($commande_id !== "") {
                        $liste[] = array(
                            'commande_id' => $commande_id,
                            'commande_client' => $commande_client,
                            'commande_date' => $commande_date,
                            'commande_heure' => $commande_heure,
                            'commande_produit' => $commande_produit,
                            'commande_produit_quantite' => $commande_produit_quantite,
                            'commande_produit_prix' => $commande_produit_prix,
                            'commande_total_ht' => $commande_total_HT,
                            'commande_total_ttc' => $commande_total_TTC,
                            'commande_adresse' => $commande_adresse,
                            'commande_adresse2' => $commande_adresse2,
                            'commande_adresse_ville' => $commande_adresse_ville,
                            'commande_adresse_cp' => $commande_adresse_cp,
                            'commande_commentaires' => $commande_commentaires,
                            'commande_etat' => $commande_etat
                        );
                    }
                    $commande_id = $row['commande_id'];
                    $commande_client = $row['client_nom'];
                    $commande_date = $row['date'];
                    $commande_heure = $row['heure'];
                    $commande_produit = [];
                    $commande_produit_prix = [];
                    $commande_produit_quantite = [];
                    $commande_adresse = $row['adresse'];
                    $commande_adresse2 = $row['adresse2'];
                    $commande_adresse_ville = $row['adresse_ville'];
                    $commande_adresse_cp = $row['adresse_cp'];
                    $commande_commentaires = $row['commentaires'];
                    $commande_etat = $row['etat'];
                }
                $commande_produit[] = $row['produit'];
                $commande_produit_prix[] = $row['prix'] . " $";
                $commande_produit_quantite[] = $row['quantite'];

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
                'commande_heure' => $commande_heure,
                'commande_produit' => $commande_produit,
                'commande_produit_prix' => $commande_produit_prix,
                'commande_produit_quantite' => $commande_produit_quantite,
                'commande_total_ht' => $commande_total_HT,
                'commande_total_ttc' => $commande_total_TTC,
                'commande_adresse' => $commande_adresse,
                'commande_adresse2' => $commande_adresse2,
                'commande_adresse_ville' => $commande_adresse_ville,
                'commande_adresse_cp' => $commande_adresse_cp,
                'commande_commentaires' => $commande_commentaires,
                'commande_etat' => $commande_etat
            );
            mysqli_free_result($result);
            return $liste;
        } else {
            return $liste;
        }
    }
}

/**
 * Fonction NombreClients,
 * Auteur   : Soushi888,
 * Date     : 30-01-2020,
 * But      : Récupérer le nombre total de lignes de la table clients,
 * Input    : $conn = contexte de connexion,
 * Output   : $nombre = nombre total de lignes de la table clients.
 */
function NombreClients($conn)
{
    $req = "SELECT COUNT(client_id) AS 'nombre' FROM clients";

    if ($result = mysqli_query($conn, $req)) {
        $nbResult = mysqli_num_rows($result);
        if ($nbResult) {
            mysqli_data_seek($result, 0);
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $nombre = $row["nombre"];
        }
        mysqli_free_result($result);
        return $nombre;
    } else {
        errSQL($conn);
        exit;
    }
}

/**
 * Fonction ListerClients,
 * Auteur   : Soushi888,,
 * Date     : 17-01-2020,
 * But      : Récupérer les clients avec leurs données associées,
 * Input    : $conn = contexte de connexion,
 *            $recherche = chaîne de caractères pour la recherche de clients (optionnel),
 * Output   : $liste = tableau des lignes de la commande SELECT.
 */
function ListerClients($conn, $recherche = "")
{
    $req = "SELECT CL.*, COUNT(CO.commande_id) AS 'nbr_commandes'
    FROM clients AS CL
    LEFT JOIN
        commandes AS CO ON CL.client_id = CO.fk_client_id
    WHERE (CL.client_nom LIKE ?) OR (CL.client_prenom LIKE ?)
    GROUP BY
        CL.client_id";

    $stmt = mysqli_prepare($conn, $req);
    $recherche = "%" . trim($recherche) . "%";

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
        return $liste;
    }
}

/**
 * Fonction NombreCategories,
 * Auteur   : Soushi888,
 * Date     : 30-01-2020,
 * But      : Récupérer le nombre total de lignes de la table categories,
 * Input    : $conn = contexte de connexion,
 * Output   : $nombre = nombre total de lignes de la table categories.
 */
function NombreCategories($conn)
{
    $req = "SELECT COUNT(categorie_id) AS 'nombre' FROM categories";

    if ($result = mysqli_query($conn, $req)) {
        $nbResult = mysqli_num_rows($result);
        if ($nbResult) {
            mysqli_data_seek($result, 0);
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $nombre = $row["nombre"];
        }
        mysqli_free_result($result);
        return $nombre;
    } else {
        errSQL($conn);
        exit;
    }
}

/**
 * Fonction ListerCategories,
 * Auteur   : Soushi888,,
 * Date     : 17-01-2020,
 * But      : Récupérer la liste des categories,
 * Input    : $conn = contexte de connexion,
 *            $trie = Colonne de référence pour le trie,
 *            $sens = Sens dans lequel sera trier la colonne,
 * Output   : $liste = tableau des lignes de la commande SELECT.
 */
function ListerCategories($conn, $trie = "categorie_id", $sens = "ASC", $offset, $nbrParPage)
{
    $req = "SELECT
            C.*,
            COUNT(P.produit_id) AS 'Nombre_de_produits'
            FROM
                categories AS C
            LEFT JOIN 
                produits AS P
            ON
                P.fk_categorie_id = C.categorie_id
            GROUP BY
                C.categorie_id
            ORDER BY
                $trie $sens
            LIMIT
                $offset, $nbrParPage";

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
        return $liste;
    }
}



/**
 * Fonction NombreProduits,
 * Auteur   : Soushi888,
 * Date     : 30-01-2020,
 * But      : Récupérer le nombre total de lignes de la table produit,
 * Input    : $conn = contexte de connexion,
 * Output   : $nombre = nombre total de lignes de la table produit.
 */
function NombreProduits($conn)
{
    $req = "SELECT COUNT(produit_id) AS 'nombre' FROM produits";

    if ($result = mysqli_query($conn, $req)) {
        $nbResult = mysqli_num_rows($result);
        if ($nbResult) {
            mysqli_data_seek($result, 0);
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $nombre = $row["nombre"];
        }
        mysqli_free_result($result);
        return $nombre;
    } else {
        errSQL($conn);
        exit;
    }
}


/**
 * Fonction ListerProduits,
 * Auteur   : Soushi888,
 * Date     : 17-01-2020,
 * But      : Récupérer les produits et les données associées,
 * Input    : $conn = contexte de connexion,
 *            $recherche = chaîne de caractères pour la recherche de produits (optionnel),
 * Output   : $liste = tableau des lignes de la commande SELECT.
 */
function ListerProduits($conn, $recherche = "", $offset, $nbrParPage)
{
    $req = "SELECT
                P.*,
                C.categorie_nom,
                COUNT(CO.fk_commande_id) AS 'nbr_commandes'
            FROM
                produits AS P
            LEFT JOIN categories AS C
            ON
                C.categorie_id = P.fk_categorie_id
            LEFT JOIN commandes_produits AS CO
            ON
                CO.fk_produit_id = P.produit_id
            WHERE
                (P.produit_nom LIKE ?) OR (C.categorie_nom LIKE ?)
            GROUP BY P.produit_id
            LIMIT ?, ?";

    $stmt = mysqli_prepare($conn, $req);
    $recherche = "%" . $recherche . "%";

    mysqli_stmt_bind_param($stmt, "ssii", $recherche, $recherche,  $offset, $nbrParPage);

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
        return $liste;
    }
}

/**
 * Fonction NombreUtilisateurs,
 * Auteur   : Soushi888,
 * Date     : 30-01-2020,
 * But      : Récupérer le nombre total de lignes de la table utilisateurs,
 * Input    : $conn = contexte de connexion,
 * Output   : $nombre = nombre total de lignes de la table utilisateurs.
 */
function NombreUtilisateurs($conn)
{
    $req = "SELECT COUNT(utilisateur_id) AS 'nombre' FROM utilisateurs";

    if ($result = mysqli_query($conn, $req)) {
        $nbResult = mysqli_num_rows($result);
        if ($nbResult) {
            mysqli_data_seek($result, 0);
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $nombre = $row["nombre"];
        }
        mysqli_free_result($result);
        return $nombre;
    } else {
        errSQL($conn);
        exit;
    }
}

/**
 * Fonction ListerUtilisateur,
 * Auteur   : Soushi888,,
 * Date     : 17-01-2020,
 * But      : Récupérer les utilisateur et les données associées,
 * Input    : $conn = contexte de connexion,
 *            $recherche = chaîne de caractères pour la recherche de utilisateur (optionnel),
 * Output   : $liste = tableau des lignes de la commande SELECT.
 */
function ListerUtilisateurs($conn, $recherche = "")
{
    $req = "SELECT
	            U.*
            FROM
                utilisateurs as U
            WHERE (U.utilisateur_nom LIKE ?) OR (U.utilisateur_prenom LIKE ?)
            ORDER BY utilisateur_type";

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
 * Fonction AjouterCategorie
 * Auteur : Soushi888,
 * Date   : 2020-01-19,
 * But    : ajouter une ligne dans la table catégories,
 * Arguments en entrée : $conn = contexte de connexion,
 *                       $categorie = Catégorie à ajouter à la table,
 * Valeurs de retour   : 1    si ajout effectuée,
 *                       0    si aucun ajout.
 */
function AjouterCategorie($conn, $categorie)
{
    $req = "INSERT INTO categories (categorie_nom)
    VALUES (?)";
    $stmt = mysqli_prepare($conn, $req);
    mysqli_stmt_bind_param($stmt, "s", $categorie);
    if (mysqli_stmt_execute($stmt)) {
        return mysqli_stmt_affected_rows($stmt);
    } else {
        errSQL($conn);
        exit;
    }
}

/** 
 * Fonction AjouterClient
 * Auteur   : Soushi888
 * Date     : 2020-01-19
 * But      : ajouter une ligne dans la table client  
 * Input    : $conn = contexte de connexion
 *            $client = tableau contenant les informations sur le client à ajouter à la BDD
 * Output   : 1 si ajout effectuée
 *            0 si aucun ajout
 */
function AjouterClient($conn, $client)
{
    $req = "INSERT INTO clients (client_nom, client_prenom, client_email, client_telephone, client_adresse, client_adresse2, client_ville, client_cp)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $req);
    mysqli_stmt_bind_param($stmt, "ssssssss", $client["nom"], $client["prenom"], $client["email"], $client["telephone"], $client["adresse"], $client["adresse2"], $client["ville"], $client["cp"]);
    if (mysqli_stmt_execute($stmt)) {
        return mysqli_stmt_affected_rows($stmt);
    } else {
        errSQL($conn);
        exit;
    }
}

/** 
 * Fonction AjouterProduit
 * Auteur   : Soushi888
 * Date     : 2020-01-20
 * But      : ajouter une ligne dans la table produits  
 * Input    : $conn = contexte de connexion
 *            $produit = tableau contenant les informations sur le produit à ajouter à la BDD
 * Output   : 1 si ajout effectuée
 *            0 si aucun ajout
 */
function AjouterProduit($conn, $produit)
{
    $req = "INSERT INTO produits (produit_nom, produit_description, produit_prix, produit_quantite, fk_categorie_id)
    VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $req);
    mysqli_stmt_bind_param($stmt, "sssss", $produit["nom"], $produit["description"], $produit["prix"], $produit["quantite"], $produit["categorie"]);
    if (mysqli_stmt_execute($stmt)) {
        return mysqli_stmt_affected_rows($stmt);
    } else {
        errSQL($conn);
        exit;
    }
}

/** 
 * Fonction AjouterUtilisateur
 * Auteur   : Soushi888
 * Date     : 2020-01-20
 * But      : ajouter une ligne dans la table utilisateurs  
 * Input    : $conn = contexte de connexion
 *            $utilisateur = tableau contenant les informations sur le utilisateur à ajouter à la BDD
 * Output   : 1 si ajout effectuée
 *            0 si aucun ajout
 */
function AjouterUtilisateur($conn, $utilisateur)
{
    $req = "INSERT INTO utilisateurs (utilisateur_nom, utilisateur_prenom, utilisateur_email, utilisateur_mdp, utilisateur_type)
    VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $req);
    $utilisateur["mdp"] = hash("sha256", $utilisateur["mdp"]);
    mysqli_stmt_bind_param($stmt, "sssss", $utilisateur["nom"], $utilisateur["prenom"], $utilisateur["email"], $utilisateur["mdp"], $utilisateur["type"]);
    if (mysqli_stmt_execute($stmt)) {
        return mysqli_stmt_affected_rows($stmt);
    } else {
        errSQL($conn);
        exit;
    }
}

/**
 * Fonction EnregistrerCommande
 * Auteur : Soushi888
 * Date   : 2020-01-20
 * But    : ajout de ligne dans les tables commande et produit_commande 
 * Arguments en entrée : $conn = contexte de connexion
 *                       $commande = tableau contenant les id et les quantités des produits commandés
 * Valeurs de retour   : aucune
 */
function EnregistrerCommande($conn, array $commande)
{
    mysqli_begin_transaction($conn); // Début de la transaction

    // Création de la commande
    $req = "INSERT INTO commandes (fk_client_id, commande_adresse, commande_adresse2, commande_adresse_ville, commande_adresse_cp, commande_commentaires)
    VALUES (?, ?, ?, ?, ?, ?);";

    $stmt = mysqli_prepare($conn, $req);

    mysqli_stmt_bind_param($stmt, "isssss", $commande["info_client"]["client_id"], $commande["info_client"]["adresse_livraison"], $commande["info_client"]["adresse2_livraison"], $commande["info_client"]["ville_livraison"], $commande["info_client"]["cp_livraison"], $commande["info_client"]["commande_commentaires"]);

    if ($result = mysqli_stmt_execute($stmt)) {
        $row = mysqli_stmt_affected_rows($stmt);
    } else {
        errSQL($conn);
        mysqli_rollback($conn);
        exit;
    }

    $commande_id = mysqli_insert_id($conn);

    foreach ($commande["info_commande"] as $c) { // Pour chaque prodruit commandé
        // Récupération de la quantité actuelle des produits commandés
        $req = "SELECT produit_quantite FROM produits WHERE produit_id = " . $c["produit"];

        if ($result = mysqli_query($conn, $req)) {
            $row = mysqli_fetch_row($result);
            $quantite = $row[0];
        } else {
            errSQL($conn);
            mysqli_rollback($conn);
            exit;
        }

        $nouvelleQuantite = $quantite - $c["quantité"]; // La quantié commandée est soustraite à la quantité actuelle du même produit

        // Insert les produits commandés dans la table produit_commande
        $req = "INSERT INTO commandes_produits (fk_produit_id, fk_commande_id, commande_produit_quantite) VALUES (" . $c["produit"] . ", $commande_id," . $c["quantité"] . ");";

        if ($result = mysqli_query($conn, $req)) {
            $row = mysqli_affected_rows($conn);
        } else {
            errSQL($conn);
            mysqli_rollback($conn);
            exit;
        }

        // Si il y a suffisament de stock, mise à jours à jours de la quantité des produits commandés
        if ($c["quantité"] <= $quantite) {
            $req = "UPDATE produits SET produit_quantite = $nouvelleQuantite WHERE produit_id = " . $c["produit"];

            if ($result = mysqli_query($conn, $req)) {
                $row = mysqli_affected_rows($conn);
            } else {
                errSQL($conn);
                mysqli_rollback($conn);
                exit;
            }
        } else {
            mysqli_rollback($conn); ?>
            <p class="erreur">Erreure : Plus assez de stock pour le produit numéro <?= $c["produit"] ?>.</p>
<?php exit;
        }
    }
    mysqli_commit($conn);
}

/**
 * Fonction SupprimerCategorie
 * Auteur : Soushi888
 * Date   : 2020-01-23
 * But    : supprimer une ligne de la table categorie  
 * Arguments en entrée : $conn = contexte de connexion
 *                       $categorie_id   = valeur de la clé primaire 
 * Valeurs de retour   : 1    si suppression effectuée
 *                       0    si aucune suppression
 */
function SupprimerCategorie($conn, $id)
{
    $liste = ListerProduits($conn);

    foreach ($liste as $p) {
        if ($p["fk_categorie_id"] == $id) {
            $req = " UPDATE produits 
                SET fk_categorie_id = NULL 
                WHERE fk_categorie_id=" . $id;
            if (!mysqli_query($conn, $req)) {
                errSQL($conn);
                exit;
            }
        }
    }

    $req = "DELETE FROM categories WHERE categorie_id=" . $id;
    if (mysqli_query($conn, $req)) {
        return mysqli_affected_rows($conn);
    } else {
        errSQL($conn);
        exit;
    }
}

/**
 * Fonction SupprimerUtilisateur
 * Auteur : Soushi888
 * Date   : 2020-01-23
 * But    : supprimer une ligne de la table utilisateur  
 * Arguments en entrée : $conn = contexte de connexion
 *                       $utilisateur_id   = valeur de la clé primaire 
 * Valeurs de retour   : 1    si suppression effectuée
 *                       0    si aucune suppression
 */
function SupprimerUtilisateur($conn, $id)
{
    $req = "DELETE FROM utilisateurs WHERE utilisateur_id=" . $id;
    if (mysqli_query($conn, $req)) {
        return mysqli_affected_rows($conn);
    } else {
        errSQL($conn);
        exit;
    }
}

/**
 * Fonction SupprimerProduit
 * Auteur : Soushi888
 * Date   : 2020-01-24
 * But    : supprimer une ligne de la table produits
 * Arguments en entrée : $conn = contexte de connexion
 *                       $produit_id = valeur de la clé primaire 
 * Valeurs de retour   : 1    si suppression effectuée
 *                       0    si aucune suppression
 */
function SupprimerProduit($conn, $id)
{
    $req = "DELETE FROM produits WHERE produit_id=" . $id;
    if (mysqli_query($conn, $req)) {
        return mysqli_affected_rows($conn);
    } else {
        errSQL($conn);
        exit;
    }
}

/**
 * Fonction SupprimerCommande
 * Auteur : Soushi888
 * Date   : 2020-01-24
 * But    : supprimer une ligne de la table commandes et toutes les lignes associées dans la table commandes_produits
 * Arguments en entrée : $conn = contexte de connexion
 *                       $commande_id   = valeur de la clé primaire 
 * Valeurs de retour   : 1    si suppression effectuée
 *                       0    si aucune suppression
 */
function SupprimerCommande($conn, $id)
{
    $liste = ListerCommandes($conn);

    foreach ($liste as $c) {
        if ($c["commande_id"] == $id) {
            $req = "DELETE FROM commandes_produits WHERE fk_commande_id=" . $id;
            if (!mysqli_query($conn, $req)) {
                errSQL($conn);
                exit;
            }
        }
    }

    $req = "DELETE FROM commandes WHERE commande_id=" . $id;
    if (mysqli_query($conn, $req)) {
        return mysqli_affected_rows($conn);
    } else {
        errSQL($conn);
        exit;
    }
}

/**
 * Fonction SupprimerClient
 * Auteur : Soushi888
 * Date   : 2020-01-23
 * But    : supprimer une ligne de la table client  
 * Arguments en entrée : $conn = contexte de connexion
 *                       $client_id   = valeur de la clé primaire 
 * Valeurs de retour   : 1    si suppression effectuée
 *                       0    si aucune suppression
 */
function SupprimerClient($conn, $id)
{
    $req = "DELETE FROM clients WHERE client_id=" . $id;
    if (mysqli_query($conn, $req)) {
        return mysqli_affected_rows($conn);
    } else {
        errSQL($conn);
        exit;
    }
}

/** 
 * Fonction ModifierUtilisateur
 * Auteur   : Soushi888
 * Date     : 2020-01-24
 * But      : modifier une ligne dans la table utilisateurs  
 * Input    : $conn = contexte de connexion
 *            $utilisateur = tableau contenant les informations sur le utilisateur à modifier à la BDD
 * Output   : 1 si ajout effectuée
 *            0 si aucun ajout
 */
function ModifierUtilisateur($conn, $utilisateur)
{
    $req = "UPDATE 
                utilisateurs 
            SET 
                utilisateur_nom= ?, utilisateur_prenom = ?, utilisateur_email = ?, utilisateur_mdp = ?, utilisateur_type = ?
            WHERE utilisateur_email = ?";
    $stmt = mysqli_prepare($conn, $req);
    $utilisateur["mdp"] = hash("sha256", $utilisateur["mdp"]);
    mysqli_stmt_bind_param($stmt, "ssssss", $utilisateur["nom"], $utilisateur["prenom"], $utilisateur["email"], $utilisateur["mdp"], $utilisateur["type"], $utilisateur["email"]);
    if (mysqli_stmt_execute($stmt)) {
        return mysqli_stmt_affected_rows($stmt);
    } else {
        errSQL($conn);
        exit;
    }
}

/** 
 * Fonction ModifierCategorie
 * Auteur   : Soushi888
 * Date     : 2020-01-25
 * But      : modifier une ligne dans la table categories  
 * Input    : $conn = contexte de connexion
 *            $categorie = tableau contenant les informations sur le categorie à modifier à la BDD
 * Output   : 1 si ajout effectuée
 *            0 si aucun ajout
 */
function ModifierCategorie($conn, $categorie)
{
    $req = "UPDATE 
                categories 
            SET 
                categorie_nom = ?
            WHERE categorie_id = ?";
    $stmt = mysqli_prepare($conn, $req);
    mysqli_stmt_bind_param($stmt, "si", $categorie["nouveau_nom"], $categorie["id"]);
    if (mysqli_stmt_execute($stmt)) {
        return mysqli_stmt_affected_rows($stmt);
    } else {
        errSQL($conn);
        exit;
    }
}

/** 
 * Fonction ModifierProduit
 * Auteur   : Soushi888
 * Date     : 2020-01-25
 * But      : modifier une ligne dans la table produits  
 * Input    : $conn = contexte de connexion
 *            $produit = tableau contenant les informations sur le produit à modifier à la BDD
 * Output   : 1 si ajout effectuée
 *            0 si aucun ajout
 */
function ModifierProduit($conn, $produit)
{
    $req = "UPDATE 
                produits 
            SET 
                produit_nom = ?,
                produit_description = ?,
                produit_prix = ?,
                produit_quantite = ?,
                fk_categorie_id = ?
            WHERE produit_id = ?";
    $stmt = mysqli_prepare($conn, $req);
    mysqli_stmt_bind_param($stmt, "sssssi", $produit["nom"], $produit["description"], $produit["prix"], $produit["quantite"], $produit["categorie"], $produit["id"]);
    if (mysqli_stmt_execute($stmt)) {
        return mysqli_stmt_affected_rows($stmt);
    } else {
        errSQL($conn);
        exit;
    }
}

/** 
 * Fonction ModifierClient
 * Auteur   : Soushi888
 * Date     : 2020-01-25
 * But      : modifier une ligne dans la table clients  
 * Input    : $conn = contexte de connexion
 *            $client = tableau contenant les informations sur le client à modifier à la BDD
 * Output   : 1 si ajout effectuée
 *            0 si aucun ajout
 */
function ModifierClient($conn, $client)
{
    $req = "UPDATE 
                clients 
            SET 
                client_nom = ?,
                client_prenom = ?,
                client_email = ?,
                client_telephone = ?,
                client_adresse = ?,
                client_adresse2 = ?,
                client_ville = ?,
                client_cp = ?
            WHERE client_id = ?";

    $stmt = mysqli_prepare($conn, $req);
    mysqli_stmt_bind_param($stmt, "ssssssssi", $client["nom"], $client["prenom"], $client["email"], $client["telephone"], $client["adresse"], $client["adresse2"], $client["ville"], $client["cp"], $client["id"]);

    if (mysqli_stmt_execute($stmt)) {
        return mysqli_stmt_affected_rows($stmt);
    } else {
        errSQL($conn);
        exit;
    }
}

/** 
 * Fonction ModifierCommande
 * Auteur   : Soushi888
 * Date     : 2020-01-25
 * But      : modifier une ligne dans la table commandes  
 * Input    : $conn = contexte de connexion
 *            $commande = tableau contenant les informations sur le commande à modifier à la BDD
 * Output   : 1 si ajout effectuée
 *            0 si aucun ajout
 */
function ModifierCommande($conn, $commande)
{
    $req = "UPDATE 
                commandes
            SET 
                commande_date = ?,
                commande_adresse = ?,
                commande_adresse2 = ?,
                commande_adresse_ville = ?,
                commande_adresse_cp = ?,
                commande_etat = ?,
                commande_commentaires = ?,
                fk_client_id = ?
            WHERE commande_id = ?";

    $stmt = mysqli_prepare($conn, $req);
    mysqli_stmt_bind_param($stmt, "sssssssii", $commande["date"], $commande["adresse"], $commande["adresse2"], $commande["ville"], $commande["cp"], $commande["etat"], $commande["commentaires"], $commande["client_id"], $commande["id"]);

    if (mysqli_stmt_execute($stmt)) {
        return mysqli_stmt_affected_rows($stmt);
    } else {
        errSQL($conn);
        exit;
    }
}
