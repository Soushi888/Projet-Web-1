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
 * Fonction listerCategories,
 * Auteur   : soushi888,
 * Date     : 17-01-2020,
 * But      : Récupérer la liste des categories,
 * Input    : $conn = contexte de connexion,
 * Output   : $liste = tableau des lignes de la commande SELECT.
 */
function listerCategories($conn)
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
 * Fonction listerProduits,
 * Auteur   : soushi888,
 * Date     : 17-01-2020,
 * But      : Récupérer la liste des produits,
 * Input    : $conn = contexte de connexion,
 *            $recherche = chaîne de caractères pour la recherche de produits (optionnel),
 *            $tri  = champ critère de tri (optionnel),
 *            $sens = sens du tri, ASC ou DESC (optionnel),
 * Output   : $liste = tableau des lignes de la commande SELECT.
 */
function listerProduits($conn, $recherche = "")
{
    $req = "SELECT 
                P.*,
                C.categorie_nom,
                M.marque_nom
            FROM
                produits AS P
            INNER JOIN
                categories AS C ON C.categorie_id = P.produit_categorie_id
            INNER JOIN
                marques AS M ON M.marque_id = P.produit_marque_id
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
 * Fonction controlerUtilisateur,
 * Auteur   : Samuel,
 * Date   : 3-12-2019,
 * But      : contrôler l'authentification de l'utilisateur dans la table clients,
 * Input    : $conn = contexte de connexion,
 *                       $client_courriel,
 *                       $client_mot_de_passe,
 * Output   : 1 si utilisateur avec $client_courriel et $client_mot_de_passe trouvé sinon 0.
 */
function controlerUtilisateur($conn, $client_courriel, $client_mot_de_passe)
{
    $req = "SELECT * FROM clients
            WHERE client_courriel=? AND client_mot_de_passe = SHA2(?, 256)";
    $stmt = mysqli_prepare($conn, $req);
    mysqli_stmt_bind_param($stmt, "ss", $client_courriel, $client_mot_de_passe);
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_num_rows($result);
    } else {
        errSQL($conn);
        exit;
    }
}
// Gérer les produits (ajout, modification et suppression)
/** 
 * Fonction ajouterMarque
 * Auteur : Sacha
 * Date   : 2019-12-19
 * But    : ajouter une ligne dans la table marque  
 * Arguments en entrée : $conn = contexte de connexion
 *                       $marque = marque à ajouter à la table
 * Valeurs de retour   : 1    si ajout effectuée
 *                       0    si aucun ajout
 */
function ajouterMarque($conn, $marque)
{
    $req = "INSERT INTO marques (marque_nom)
    VALUES (?)";
    $stmt = mysqli_prepare($conn, $req);
    mysqli_stmt_bind_param($stmt, "s", $marque["nom"]);
    if (mysqli_stmt_execute($stmt)) {
        return mysqli_stmt_affected_rows($stmt);
    } else {
        errSQL($conn);
        exit;
    }
}

/** 
 * Fonction ajouterCategorie
 * Auteur : Sacha
 * Date   : 2019-12-19
 * But    : ajouter une ligne dans la table categories  
 * Arguments en entrée : $conn = contexte de connexion
 *                       $categorie = Catégorie à ajouter à la table
 * Valeurs de retour   : 1    si ajout effectuée
 *                       0    si aucun ajout
 */
function ajouterCategorie($conn, $categorie)
{
    $req = "INSERT INTO categories (categorie_nom)
    VALUES (?)";
    $stmt = mysqli_prepare($conn, $req);
    mysqli_stmt_bind_param($stmt, "s", $categorie["nom"]);
    if (mysqli_stmt_execute($stmt)) {
        return mysqli_stmt_affected_rows($stmt);
    } else {
        errSQL($conn);
        exit;
    }
}

/** 
 * Fonction ajouterProduit
 * Auteur : Samuel
 * Date   : 2019-12-04
 * But    : ajouter une ligne dans la table produit  
 * Arguments en entrée : $conn = contexte de connexion
 *                       $produit
 * Valeurs de retour   : 1    si ajout effectuée
 *                       0    si aucun ajout
 */
function ajouterProduit($conn, $produit)
{
    $req = "INSERT INTO produits (produit_nom, produit_description, produit_prix, produit_quantite, produit_categorie_id, produit_marque_id)
    VALUES (?,?,?,?,?,?)";
    $stmt = mysqli_prepare($conn, $req);
    mysqli_stmt_bind_param($stmt, "ssdiss", $produit["nom"], $produit["description"], $produit["prix"], $produit["quantite"], $produit["categorie"], $produit["marque"]);
    if (mysqli_stmt_execute($stmt)) {
        return mysqli_stmt_affected_rows($stmt);
    } else {
        errSQL($conn);
        exit;
    }
}

/** 
 * Fonction modifierProduit
 * Auteur : Samuel
 * Date   : 2019-12-04
 * But    : modifier une ligne dans la table produit  
 * Arguments en entrée : $conn = contexte de connexion
 *                       $id   = clé primaire du joueur à modifier
 *                       $produit
 *                        
 * Valeurs de retour   : 1    si modification effectuée
 *                       0    si aucune modification
 */
function modifierProduit($conn, $produit)
{
    $req = "UPDATE produits SET produit_nom = ? , produit_description = ?, produit_prix = ?, produit_quantite = ?, produit_categorie_id = ?, produit_marque_id = ?
    WHERE produit_id = ?";
    $stmt = mysqli_prepare($conn, $req);
    var_dump($stmt);
    mysqli_stmt_bind_param($stmt, "ssdissi", $produit["produit_nom"], $produit["produit_description"], $produit["produit_prix"], $produit["produit_quantite"], $produit["produit_categorie_id"], $produit["produit_marque_id"], $produit["produit_id"]);
    if (mysqli_stmt_execute($stmt)) {
        return mysqli_stmt_affected_rows($stmt);
    } else {
        errSQL($conn);
        exit;
    }
}


/**
 * Fonction lireProduit
 * Auteur : Sacha
 * Date   : 2019-12-11
 * But    : Récupérer le produit par son identifiant clé primaire 
 * Arguments en entrée : $conn = contexte de connexion
 *                       $id   = clé primaire
 * Valeurs de retour   : $row  = ligne correspondant à la clé primaire
 *                               tableau vide si non trouvée     
 */
function lireProduit($conn, $id)
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
 * Fonction lireClientID
 * Auteur   : Sacha
 * Date     : 2019-12-16
 * But      : Récupérer le ID d'un client à partir de son identifiant 
 * Input    : $conn = contexte de connexion
 *            $identifiant = adresse email  du client
 * Output   : $row  = ligne correspondant à l'identifiant du client
 *                    tableau vide si non trouvée     
 */
function lireClientID($conn, $identifiant)
{

    $req = "SELECT * FROM clients WHERE client_courriel ='$identifiant'";

    if ($result = mysqli_query($conn, $req)) {
        $nbResult = mysqli_num_rows($result);
        $row = array();
        if ($nbResult) {
            mysqli_data_seek($result, 0);
            $row = mysqli_fetch_array($result);
        }
        mysqli_free_result($result);
        $id = $row['client_id'];
        return $id;
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
 * Fonction supprimerProduit
 * Auteur : Samuel
 * Date   : 2019-12-04
 * But    : supprimer une ligne de la table produit  
 * Arguments en entrée : $conn = contexte de connexion
 *                       $produit_id   = valeur de la clé primaire 
 * Valeurs de retour   : 1    si suppression effectuée
 *                       0    si aucune suppression
 */
function supprimerProduit($conn, $produit_id)
{
    $req = "DELETE FROM produits WHERE produit_id=" . $produit_id;
    if (mysqli_query($conn, $req)) {
        return mysqli_affected_rows($conn);
    } else {
        errSQL($conn);
        exit;
    }
}

/**
 * Fonction enregistrerCommande
 * Auteur : Sacha
 * Date   : 2019-12-18
 * But    : ajout de ligne dans les tables commande et produit_commande 
 * Arguments en entrée : $conn = contexte de connexion
 *                       $commande = tableau contenant les id et les quantités des produits commandés
 * Valeurs de retour   : aucune
 */
function enregistrerCommande($conn, array $commande, $client_id) {
    mysqli_begin_transaction($conn); // Début de la transaction

    // Création de la commande
    $req = "INSERT INTO commandes (commande_client_id) VALUES ($client_id);";
        
    if ($result = mysqli_query($conn, $req)) {
        $row = mysqli_affected_rows($conn);
    } else {
        errSQL($conn);
        mysqli_rollback($conn);
        exit;
    }

    $commande_id = mysqli_insert_id($conn);

    foreach($commande as $c) { // Pour chaque prodruit commandé
        // Récupération de la quantité actuelle des produits commandés
        $req = "SELECT produit_quantite FROM produits WHERE produit_id = ". $c["produit"];

        if ($result = mysqli_query($conn, $req)) {
            $row = mysqli_fetch_row($result);
            $quantite = $row[0]; 
        } else {
            errSQL($conn);
            mysqli_rollback($conn);
            exit;
        }

        $nouvelleQuantite = $quantite - $c["quantité"];

        // Insert les produits commandés dans la table produit_commande
        $req = "INSERT INTO produits_commandes (produits_produit_id, commandes_commande_id, produit_commande_quantite) VALUES (" . $c["produit"] . ", $commande_id," . $c["quantité"] . ");";
        
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
        }
        else {
            mysqli_rollback($conn); ?>
                <p class="erreur">Erreure : Plus assez de stock pour le produit numéro <?= $c["produit"] ?>.</p>
        <?php  exit;
        }
    }
    mysqli_commit($conn);
}

/** 
 * Fonction listerCommandes,
 * Auteur   : Soushi888,
 * Date     : 2019-12-16,
 * But      : Récupérer les commandes avec les données associées,
 * Input    : $conn = contexte de connexion,
 *            $recherche = chaîne de caractères pour la recherche de commande par nom de client (optionnel),
 * Output   : $liste = tableau des lignes de la commande SELECT.
 */
function listerCommandes($conn, $recherche = "")
{
    $recherche = "%" . $recherche . "%";
 
    $req = "SELECT
    C.commande_id as 'Numéro de commande',
    CONCAT(CL.client_prenom, ' ', CL.client_nom) as 'Nom du client',
    P.produit_nom as 'Produit',
    PC.produit_commande_quantite as 'Quantité'
    FROM
        commandes as C
    INNER JOIN
        produits_commandes as PC on PC.commandes_commande_id = C.commande_id
    INNER JOIN
        produits as P on P.produit_id = PC.produits_produit_id
    INNER JOIN
        clients as CL on CL.client_id = C.commande_client_id 
    WHERE (CL.client_prenom LIKE '$recherche') OR (CL.client_nom LIKE '$recherche')
    ORDER BY `Numéro de commande` ASC";
    
    if ($result = mysqli_query($conn, $req, MYSQLI_STORE_RESULT)) {
        $nbResult = mysqli_num_rows($result);
        $liste = array();
        if ($nbResult) {
            mysqli_data_seek($result, 0);
            $commande_id = "";
            $commande_client = "";
            $commande_produit = "";
            $commande_quantite = "";
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                if ($commande_id !== $row['Numéro de commande']) {
                    if ($commande_id !== "") {
                        $liste [] = array(
									'commande_id' => $commande_id,
                                    'commande_client' => $commande_client,
                                    'commande_produit' => $commande_produit,
                                    'commande_quantite' => $commande_quantite,
                                    );
                    }
                    $commande_id = $row['Numéro de commande'];
                    $commande_client = $row['Nom du client'];
                    $commande_produit = [];
                    $commande_quantite = [];
                }
                $commande_produit[] = $row['Produit'];
                $commande_quantite[] = $row['Quantité'];
            }
            $liste [] = array(
                'commande_id' => $commande_id,
                'commande_client' => $commande_client,
                'commande_produit' => $commande_produit,
                'commande_quantite' =>  $commande_quantite);
        mysqli_free_result($result);
        return $liste;
    } else {
        errSQL($conn);
        exit;
    }
    }
}



/** 
 * Fonction creation d'un compte
 * Auteur : Samuel
 * Date   : 2019-12-04
 * But    : ajouter une ligne dans la table clients  
 * Input : $conn = contexte de connexion
 *                       $client_nom,
 *                       $client_prenom,
 *                       $client_mot_de_passe,
 *                       $client_courriel
 * Output   : 1    si ajout effectuée
 *                       0    si aucun ajout
 */
function creationDeCompte($conn, $client_nom, $client_prenom, $client_mot_de_passe, $client_courriel)
{

    $req = "INSERT INTO clients (client_nom, client_prenom, client_mot_de_passe, client_courriel )
    VALUES ('$client_nom', '$client_prenom', '$client_mot_de_passe', '$client_courriel' )";


    if (mysqli_query($conn, $req)) {
        return mysqli_affected_rows($conn);
    } else {
        errSQL($conn);
        exit;
    }
}