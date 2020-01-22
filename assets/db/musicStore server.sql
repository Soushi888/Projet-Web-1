USE `e1995655` ;

-- -----------------------------------------------------
-- Table `e1995655`.`Utilisateurs`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `e1995655`.`utilisateurs` (
  `utilisateur_id` INT NOT NULL AUTO_INCREMENT,
  `utilisateur_nom` VARCHAR(255) NOT NULL,
  `utilisateur_prenom` VARCHAR(255) NOT NULL,
  `utilisateur_email` VARCHAR(255) NOT NULL,
  `utilisateur_mdp` VARCHAR(255) NOT NULL,
  `utilisateur_type` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`utilisateur_id`),
  UNIQUE INDEX `utilisateur_email_UNIQUE` (`utilisateur_email` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `e1995655`.`Clients`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `e1995655`.`clients` (
  `client_id` INT NOT NULL AUTO_INCREMENT,
  `client_nom` VARCHAR(255) NOT NULL,
  `client_prenom` VARCHAR(255) NOT NULL,
  `client_email` VARCHAR(255) NOT NULL,
  `client_telephone` VARCHAR(255) NOT NULL,
  `client_adresse` VARCHAR(255) NOT NULL,
  `client_adresse2` VARCHAR(255) NULL,
  `client_ville` VARCHAR(255) NOT NULL,
  `client_cp` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`client_id`),
  UNIQUE INDEX `client_email_UNIQUE` (`client_email` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `e1995655`.`Categories`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `e1995655`.`categories` (
  `categorie_id` INT NOT NULL AUTO_INCREMENT,
  `categorie_nom` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`categorie_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `e1995655`.`Produits`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `e1995655`.`produits` (
  `produit_id` INT NOT NULL AUTO_INCREMENT,
  `produit_nom` VARCHAR(255) NOT NULL,
  `produit_description` VARCHAR(255) NOT NULL,
  `produit_prix` DECIMAL(8,2) NOT NULL,
  `produit_quantite` INT NOT NULL,
  `fk_categorie_id` INT NOT NULL,
  PRIMARY KEY (`produit_id`),
  INDEX `fk_Produits_Catégories1_idx` (`fk_categorie_id` ASC),
  CONSTRAINT `fk_Produits_Catégories1`
    FOREIGN KEY (`fk_categorie_id`)
    REFERENCES `e1995655`.`categories` (`categorie_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `e1995655`.`Commandes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `e1995655`.`commandes` (
  `commande_id` INT NOT NULL AUTO_INCREMENT,
  `commande_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `commande_adresse` VARCHAR(255) NOT NULL,
  `commande_adresse2` VARCHAR(255) NULL,
  `commande_adresse_ville` VARCHAR(255) NOT NULL,
  `commande_adresse_cp` VARCHAR(255) NOT NULL,
  `commande_etat` VARCHAR(255) NOT NULL DEFAULT "en cours",
  `commande_commentaires` VARCHAR(255) NULL,
  `fk_client_id` INT NOT NULL,
  PRIMARY KEY (`commande_id`),
  INDEX `fk_Commandes_Clients_idx` (`fk_client_id` ASC),
  CONSTRAINT `fk_Commandes_Clients`
    FOREIGN KEY (`fk_client_id`)
    REFERENCES `e1995655`.`clients` (`client_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `e1995655`.`Commandes_Produits`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `e1995655`.`commandes_produits` (
  `fk_commande_id` INT NOT NULL,
  `fk_produit_id` INT NOT NULL,
  `commande_produit_quantite` INT NOT NULL,
  PRIMARY KEY (`fk_produit_id`, `fk_commande_id`),
  INDEX `fk_Commandes_has_Produits_Produits1_idx` (`fk_produit_id` ASC),
  INDEX `fk_Commandes_has_Produits_Commandes1_idx` (`fk_commande_id` ASC),
  CONSTRAINT `fk_Commandes_has_Produits_Commandes1`
    FOREIGN KEY (`fk_commande_id`)
    REFERENCES `e1995655`.`commandes` (`commande_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Commandes_has_Produits_Produits1`
    FOREIGN KEY (`fk_produit_id`)
    REFERENCES `e1995655`.`produits` (`produit_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Insertion de données dans la table "utilisateurs"
-- -----------------------------------------------------
INSERT INTO 
  utilisateurs (utilisateur_nom, utilisateur_prenom, utilisateur_email, utilisateur_mdp, utilisateur_type)
VALUES
  ("Pignot", "Sacha", "admin@e1995655.com", SHA2('admin', 256), "administrateur"),

  ("Marc", "Jean", "Jean@e1995655.com", SHA2('jean', 256), "gestionnaire"),
  ("Mazi", "Jeanne", "Jeanne@e1995655.com", SHA2('jeanne', 256), "gestionnaire"),

  ("Lopez", "Carlos", "Carlos@e1995655.com", SHA2('carle', 256), "vendeur"),
  ("Laza", "Clara", "Clara@e1995655.com", SHA2('clara', 256), "vendeur"),
  ("Ledragon", "Elliotte", "Elliotte@e1995655.com", SHA2('elliotte', 256), "vendeur");

-- -----------------------------------------------------
-- Insertion de données dans la table "categories"
-- -----------------------------------------------------
INSERT INTO
  categories (categorie_nom)
VALUES
  ("Accessoires"),
  ("Partitions"),
  ("Saxophones"),
  ("Trompettes"),
  ("Trombonnes"),
  ("Flutes"),
  ("Claviers"),
  ("Guitares"),
  ("Batteries");

-- -----------------------------------------------------
-- Insertion de données dans la table "produits"
-- -----------------------------------------------------
INSERT INTO
  produits (produit_nom, produit_description, produit_prix, produit_quantite, fk_categorie_id)
VALUES
  ("Bec Vandoren 4AO25", "Bec Vendoren, 25mm d'ouverture, parfait pour étudiants.", 149.99, 32, 1),
  ("Bec Yamaha 4C", "Bec standard, parfait pour débutants.", 43.99, 20, 1),
  ("Real Book 6th Edition en Do", "Incontournable bible de standards jazz pour instruments en Do.", 65.99, 100, 2),
  ("Real Book 6th Edition en Mib", "Incontournable bible de standards jazz pour instruments en Mi bémole.", 65.99, 50, 2),
  ("Real Book 6th Edition en Sib", "Incontournable bible de standards jazz pour instruments en Si bémole.", 65.99, 50, 2),
  ("Expression Sax Ténor E4523C", "Saxophone Ténore de marque Expression. Qualité : Professionnelle, Modèle : E4523C.", 2566.99, 2,3),
  ("Expression Sax Alto E6453C", "Saxophone Ténore de marque Expression. Qualité : Professionnelle, Modèle : E6453C.", 1882.99, 1, 3),
  ("Jupiter Trompette JP2566F", "Trompette de marque Jupiter. Qualité : Professionnelle, Modèle : JP2566F.", 1585.99, 5, 4),
  ("Yamaha Trompette YH1286D", "Trompette de marque Yamaha. Qualité : Intermédiaire, Modèle : YH1286D.", 855.99, 6, 4),
  ("Jupiter Trombonne JP2569V", "Trombonne de marque Jupiter. Qualité : Intermédiaire, Modèle : JP2569V.", 1256.99, 2, 5),
  ("Jupiter Trombonne JP2633Z", "Trombonne de marque Jupiter. Qualité : Professionnelle, Modèle : JP2633Z.", 2156.99, 3, 5),
  ("Jupiter Flûte JP2566F", "Flûte de marque Jupiter. Qualité : Professionnelle, Modèle : JP2566F.", 1285.99, 5, 6),
  ("Yamaha Flûte YH1286D", "Flûte de marque Yamaha. Qualité : Intermédiaire, Modèle : YH1286D.", 655.99, 6, 6),
  ("Yamaha Clavier YH2569V", "Clavier de marque Yamaha. Qualité : Intermédiaire, Modèle : YH2569V.", 2556.99, 2, 7),
  ("Korg Clavier KG2633Z", "Clavier de marque Korg. Qualité : Professionnelle, Modèle : KG2633Z.", 2856.99, 3, 7),
  ("Roland Clavier RL2633Z", "Clavier de marque Roland. Qualité : Professionnelle, Modèle : RL2633Z.", 3156.99, 3, 7),
  ("Gibson Guitare GB2633Z", "Guitare de marque Gibson. Qualité : Professionnelle, Modèle : GB2633Z.", 830.99, 3, 8),
  ("Fender Guitare FD2633Z", "Guitare de marque Fender. Qualité : Professionnelle, Modèle : FD2633Z.", 1056.99, 3, 8),
  ("Pearl Batterie PE2633Z", "Batterie de marque Pearl. Qualité : Professionnelle, Modèle : PE2633Z.", 4856.99, 3, 9),
  ("Mapex Batterie MP2633Z", "Batterie de marque Mapex. Qualité : Intermédiaire, Modèle : MP2633Z.", 2156.99, 3, 9);

-- -----------------------------------------------------
-- Insertion de données dans la table "Commandes"
-- -----------------------------------------------------
INSERT INTO
  clients (client_nom, client_prenom, client_telephone, client_email , client_adresse, client_adresse2, client_ville, client_cp)
VALUES
  ("Pignot", "Sacha", "514-396-4589", "sacha.pignot@gmail.com", "513 rue Regina", "app.12", "Verdun", "H4G 3J4"),
  ("Gilbert", "Josiane", "514-954-4236", "josiane.gilbert@gmail.com", "513 rue Regina", "app.12", "Verdun", "H4G 3J4"),
  ("Garneau", "Louis", "450-325-9654", "louis.garneau@gmail.com", "22 avenue Magrin", NULL, "St-Hubert", "C3F 6A5"),
  ("Colimbourd", "Colette", "514-555-5656", "colimbourd.colette@gmail.com", "5539 boulevard de la Mort", "app.666", "Laval", "G6J 6S6"),
  ("Bavoie", "Rose", "514-226-4419", "bavoie.rose@gmail.com", "563 rue Lejour", NULL, "Longueuil", "H3G 5V4"),
  ("Boulet", "Pierre", "450-392-3611", "boulet.pierre@gmail.com", "3 rue Lanuit", "app.306", "Montréal", "H2J 9B6"),
  ("Viget", "Roxane", "514-223-4596", "roxane.viget@gmail.com", "1 rue du Manoir", NULL, "Montréal", "V9E 8L2");

-- -----------------------------------------------------
-- Insertion de données dans la table "Commandes"
-- -----------------------------------------------------
INSERT INTO
  commandes (fk_client_id, commande_date, commande_adresse, commande_adresse2, commande_adresse_ville, commande_adresse_cp, commande_etat, commande_commentaires)
VALUES
  (2, "2019-02-13", "513 rue Regina", "app.12", "Verdun", "H4G 3J4", "complète", NULL),
  (5, "2019-05-25", "563 rue Lejour", NULL, "Longueuil", "V9E 8L2", "en cours", NULL),
  (1, "2019-03-09", "4021 boulevard Poli", NULL, "Montréal", "V9E 8L2","annulée", "Commande incomplète"),
  (4, "2019-02-23", "5539 boulevard de la Mort", "app.666", "Laval", "G6J 6S6","complète", NULL),
  (3, "2019-06-15", "22 avenue Magrin", NULL, "Saint-Hubert", "C3F 6A5","en cours", NULL),
  (7, "2019-12-21", "1 avenue du Manoir", NULL, "Montréal", "H3G 5V4","en cours", NULL),
  (6, "2019-11-13", "3 rue Lanuit", "app.306", "Verdun", "H4G 3J4","complète", NULL),
  (1, "2019-08-10", "16 avenue Drôle", NULL, "Brossard", "H2J 9B6", "annulée", "Erreur de livraison");

-- -----------------------------------------------------
-- Insertion de données dans la table "Commandes-Produits"
-- -----------------------------------------------------
INSERT INTO
  commandes_produits (fk_commande_id, fk_produit_id, commande_produit_quantite)
VALUES
  (1, 4, 1),
  (1, 5, 1),
  (1, 1, 1),
  (2, 6, 1),
  (2, 2, 1),
  (2, 3, 1),
  (2, 8, 1),
  (3, 12, 1),
  (3, 3, 1),
  (4, 2, 1),
  (5, 14, 1),
  (6, 1, 1),
  (6, 3, 1),
  (7, 12, 1),
  (8, 19, 1),
  (8, 3, 1);
