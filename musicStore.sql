-- MySQL Script generated by MySQL Workbench
-- Thu Jan 16 15:25:13 2020
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema musicStore
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema musicStore
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `musicStore` DEFAULT CHARACTER SET utf8 ;
USE `musicStore` ;

-- -----------------------------------------------------
-- Table `musicStore`.`Utilisateurs`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `musicStore`.`Utilisateurs` (
  `utilisateur_id` INT NOT NULL AUTO_INCREMENT,
  `utilisateur_email` VARCHAR(255) NOT NULL,
  `utilisateur_mdp` VARCHAR(255) NOT NULL,
  `utilisateur_type` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`utilisateur_id`),
  UNIQUE INDEX `utilisateur_email_UNIQUE` (`utilisateur_email` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `musicStore`.`Clients`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `musicStore`.`Clients` (
  `client_id` INT NOT NULL AUTO_INCREMENT,
  `client_nom` VARCHAR(255) NOT NULL,
  `client_prenom` VARCHAR(255) NOT NULL,
  `client_email` VARCHAR(255) NOT NULL,
  `client_telephone` VARCHAR(255) NOT NULL,
  `client_adresse` VARCHAR(255) NOT NULL,
  `client_ville` VARCHAR(255) NOT NULL,
  `client_cp` VARCHAR(255) NOT NULL,
  `client_pays` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`client_id`),
  UNIQUE INDEX `client_email_UNIQUE` (`client_email` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `musicStore`.`Catégories`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `musicStore`.`Catégories` (
  `categorie_id` INT NOT NULL AUTO_INCREMENT,
  `categorie_nom` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`categorie_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `musicStore`.`Produits`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `musicStore`.`Produits` (
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
    REFERENCES `musicStore`.`Catégories` (`categorie_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `musicStore`.`Commandes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `musicStore`.`Commandes` (
  `commande_id` INT NOT NULL AUTO_INCREMENT,
  `commande_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `commande_adresse_livraison` VARCHAR(255) NOT NULL,
  `commande_etat` VARCHAR(255) NOT NULL,
  `commande_commentaires` VARCHAR(255) NULL,
  `fk_client_id` INT NOT NULL,
  PRIMARY KEY (`commande_id`),
  INDEX `fk_Commandes_Clients_idx` (`fk_client_id` ASC),
  CONSTRAINT `fk_Commandes_Clients`
    FOREIGN KEY (`fk_client_id`)
    REFERENCES `musicStore`.`Clients` (`client_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `musicStore`.`Commandes_Produits`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `musicStore`.`Commandes_Produits` (
  `commande_id` INT NOT NULL,
  `produit_id` INT NOT NULL,
  `commande_produit_quantite` INT NOT NULL,
  PRIMARY KEY (`produit_id`, `commande_id`),
  INDEX `fk_Commandes_has_Produits_Produits1_idx` (`produit_id` ASC),
  INDEX `fk_Commandes_has_Produits_Commandes1_idx` (`commande_id` ASC),
  CONSTRAINT `fk_Commandes_has_Produits_Commandes1`
    FOREIGN KEY (`commande_id`)
    REFERENCES `musicStore`.`Commandes` (`commande_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Commandes_has_Produits_Produits1`
    FOREIGN KEY (`produit_id`)
    REFERENCES `musicStore`.`Produits` (`produit_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;


-- -----------------------------------------------------
-- Insertion de données dans la table "utilisateurs"
-- -----------------------------------------------------
INSERT INTO 
  Utilisateurs (utilisateur_email, utilisateur_mdp, utilisateur_type)
VALUES
  ("admin@musicstore.com", SHA2('admin', 256), "administrateur"),

  ("Jean@musicstore.com", SHA2('jean', 256), "gestionnaire"),
  ("Jeanne@musicstore.com", SHA2('jeanne', 256), "gestionnaire"),

  ("Carle@musicstore.com", SHA2('carle', 256), "vendeur"),
  ("Clara@musicstore.com", SHA2('clara', 256), "vendeur"),
  ("Elliotte@musicstore.com", SHA2('elliotte', 256), "vendeur");

-- -----------------------------------------------------
-- Insertion de données dans la table "catégories"
-- -----------------------------------------------------
INSERT INTO
  Catégories (categorie_nom)
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
  Produits (produit_nom, produit_description, produit_prix, produit_quantite, fk_categorie_id)
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



