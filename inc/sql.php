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
 * Auteur : Sacha
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
 * Fonction LireProduit
 * Auteur : Sacha
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
 * Fonction produitLastId,
 * Auteur   : Soushi888,
 * Date     : 2019-11-26,
 * But      : Récupérer le dernier id de la table produit,
 * Input    : $conn = contexte de connexion,
 * Output   : $last_id = dernier id de la table.
 */
function produitLastId($conn)
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
 * Fonction ListerCommandes,
 * Auteur   : Soushi888,
 * Date     : 2020-01-17,
 * But      : Récupérer les commandes avec les données associées, calcule le total HT et le total TTC
 * Input    : $conn = contexte de connexion,
 *            $recherche = chaîne de caractères pour la recherche de commande par nom ou prénom de client, id de commande, nom ded produit ou date (optionnel),
 * Output   : $liste = tableau des lignes de la commande SELECT.
 */
function ListerCommandes($conn, $recherche = "")
{
    $recherche = "%" . $recherche . "%";

    $req = "SELECT
    C.commande_id as 'commande_id',
    CONCAT(CL.client_prenom, ' ', CL.client_nom) as 'client_nom',
    SUBSTRING(C.commande_date, 1, 10) as 'date',
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
    ORDER BY `commande_id` ASC";
    // die($req); 

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
                'commande_produit' => $commande_produit,
                'commande_produit_prix' => $commande_produit_prix,
                'commande_produit_quantite' => $commande_produit_quantite,
                'commande_total_ht' => $commande_total_HT,
                'commande_total_ttc' => $commande_total_TTC,
                'commande_adresse' => $commande_adresse,
                'commande_adresse2' => $commande_adresse,
                'commande_adresse_ville' => $commande_adresse,
                'commande_adresse_cp' => $commande_adresse,
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
 * Fonction ListerClients,
 * Auteur   : soushi888,
 * Date     : 17-01-2020,
 * But      : Récupérer les clients avec leurs données associées,
 * Input    : $conn = contexte de connexion,
 *            $recherche = chaîne de caractères pour la recherche de clients (optionnel),
 * Output   : $liste = tableau des lignes de la commande SELECT.
 */
function ListerClients($conn, $recherche = "")
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
 * Fonction ListerCategories,
 * Auteur   : soushi888,
 * Date     : 17-01-2020,
 * But      : Récupérer la liste des categories,
 * Input    : $conn = contexte de connexion,
 * Output   : $liste = tableau des lignes de la commande SELECT.
 */
function ListerCategories($conn)
{
    $req = "SELECT * FROM categories";
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
 * Fonction ListerProduits,
 * Auteur   : soushi888,
 * Date     : 17-01-2020,
 * But      : Récupérer les produits et les données associées,
 * Input    : $conn = contexte de connexion,
 *            $recherche = chaîne de caractères pour la recherche de produits (optionnel),
 * Output   : $liste = tableau des lignes de la commande SELECT.
 */
function ListerProduits($conn, $recherche = "")
{
    $req = "SELECT 
                P.*,
                C.categorie_nom
            FROM
                produits AS P
            INNER JOIN
                categories AS C ON C.categorie_id = P.fk_categorie_id
            WHERE (P.produit_nom LIKE ?) OR (C.categorie_nom LIKE ?)";

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
 * Fonction ListerProduits,
 * Auteur   : soushi888,
 * Date     : 17-01-2020,
 * But      : Récupérer les produits et les données associées,
 * Input    : $conn = contexte de connexion,
 *            $recherche = chaîne de caractères pour la recherche de produits (optionnel),
 * Output   : $liste = tableau des lignes de la commande SELECT.
 */
function ListerUtilisateurs($conn, $recherche = "")
{
    $req = "SELECT
	            U.*
            FROM
                utilisateurs as U
            WHERE (U.utilisateur_nom LIKE ?) OR (U.utilisateur_prenom LIKE ?)";

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
 * Auteur : Soushi888
 * Date   : 2020-01-19
 * But    : ajouter une ligne dans la table catégories  
 * Arguments en entrée : $conn = contexte de connexion
 *                       $categorie = Catégorie à ajouter à la table
 * Valeurs de retour   : 1    si ajout effectuée
 *                       0    si aucun ajout
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

    mysqli_stmt_bind_param($stmt, "isssss", $commande["info_client"]["client_id"], $commande["info_client"]["client_adresse"], $commande["info_client"]["client_adresse2"], $commande["info_client"]["client_ville"], $commande["info_client"]["client_cp"], $commande["info_client"]["commande_commentaires"]);

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
    $req = "DELETE FROM categories WHERE categorie_id=" . $id;
    if (mysqli_query($conn, $req)) {
        return mysqli_affected_rows($conn);
    } else {
        errSQL($conn);
        exit;
    }
}