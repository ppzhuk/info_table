-- MySQL Script generated by MySQL Workbench
-- 05/15/16 12:32:40
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema info_table
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `info_table` ;

-- -----------------------------------------------------
-- Schema info_table
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `info_table` DEFAULT CHARACTER SET utf8 ;
USE `info_table` ;

-- -----------------------------------------------------
-- Table `info_table`.`access_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `info_table`.`access_type` ;

CREATE TABLE IF NOT EXISTS `info_table`.`access_type` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `info_table`.`person`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `info_table`.`person` ;

CREATE TABLE IF NOT EXISTS `info_table`.`person` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `fio` VARCHAR(100) NULL,
  `login` VARCHAR(45) NOT NULL,
  `password` VARCHAR(45) NOT NULL,
  `access_type` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `login_UNIQUE` (`login` ASC),
  INDEX `acc_type_idx` (`access_type` ASC),
  CONSTRAINT `acc_type`
    FOREIGN KEY (`access_type`)
    REFERENCES `info_table`.`access_type` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `info_table`.`manual_sells`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `info_table`.`manual_sells` ;

CREATE TABLE IF NOT EXISTS `info_table`.`manual_sells` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `seller` INT NOT NULL,
  `manager` INT NOT NULL,
  `value` DECIMAL(15,2) NOT NULL,
  `date` DATE NOT NULL,
  `comment` VARCHAR(255) NULL,
  PRIMARY KEY (`id`),
  INDEX `man_sells_idx` (`seller` ASC),
  INDEX `man_sells_namager_idx` (`manager` ASC),
  CONSTRAINT `man_sells_seller`
    FOREIGN KEY (`seller`)
    REFERENCES `info_table`.`person` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `man_sells_manager`
    FOREIGN KEY (`manager`)
    REFERENCES `info_table`.`person` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `info_table`.`groups`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `info_table`.`groups` ;

CREATE TABLE IF NOT EXISTS `info_table`.`groups` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `group_name` VARCHAR(100) NULL,
  `owner` INT NOT NULL,
  `group_type` ENUM('seller', 'KAM') NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `owner_idx` (`owner` ASC),
  CONSTRAINT `owner`
    FOREIGN KEY (`owner`)
    REFERENCES `info_table`.`person` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `info_table`.`relation`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `info_table`.`relation` ;

CREATE TABLE IF NOT EXISTS `info_table`.`relation` (
  `person` INT NOT NULL,
  `group` INT NOT NULL,
  `monthly_plan` DECIMAL(15,2) NULL,
  `quarterly_plan` DECIMAL(15,2) NULL,
  INDEX `person_idx` (`person` ASC),
  INDEX `group_idx` (`group` ASC),
  PRIMARY KEY (`person`, `group`),
  CONSTRAINT `person`
    FOREIGN KEY (`person`)
    REFERENCES `info_table`.`person` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `group`
    FOREIGN KEY (`group`)
    REFERENCES `info_table`.`groups` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `info_table`.`sells`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `info_table`.`sells` ;

CREATE TABLE IF NOT EXISTS `info_table`.`sells` (
  `date` DATE NOT NULL,
  `seller` INT NOT NULL,
  `value` DECIMAL(15,2) NULL,
  INDEX `seller_idx` (`seller` ASC),
  PRIMARY KEY (`date`, `seller`),
  CONSTRAINT `seller`
    FOREIGN KEY (`seller`)
    REFERENCES `info_table`.`person` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SET SQL_MODE = '';
GRANT USAGE ON *.* TO it_root;
 DROP USER it_root;
SET SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';
CREATE USER 'it_root' IDENTIFIED BY 'it_root';

GRANT ALL ON `info_table`.* TO 'it_root';

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
