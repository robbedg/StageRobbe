-- MySQL Script generated by MySQL Workbench
-- 02/13/17 13:59:54
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema stage_db
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema stage_db
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `stage_db` DEFAULT CHARACTER SET latin1 ;
USE `stage_db` ;

-- -----------------------------------------------------
-- Table `stage_db`.`itemtypes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `stage_db`.`itemtypes` ;

CREATE TABLE IF NOT EXISTS `stage_db`.`itemtypes` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `stage_db`.`locations`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `stage_db`.`locations` ;

CREATE TABLE IF NOT EXISTS `stage_db`.`locations` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `naam_UNIQUE` (`name` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 16
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `stage_db`.`items`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `stage_db`.`items` ;

CREATE TABLE IF NOT EXISTS `stage_db`.`items` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `itemtype_id` INT(11) NOT NULL,
  `location_id` INT(11) NOT NULL,
  `attributes` JSON NULL DEFAULT NULL,
  `visible` BIT(1) NOT NULL DEFAULT b'1',
  PRIMARY KEY (`id`),
  INDEX `itemType_id_idx` (`itemtype_id` ASC),
  INDEX `location_id_idx` (`location_id` ASC),
  CONSTRAINT `itemtype_id`
    FOREIGN KEY (`itemtype_id`)
    REFERENCES `stage_db`.`itemtypes` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `location_id`
    FOREIGN KEY (`location_id`)
    REFERENCES `stage_db`.`locations` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 35
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `stage_db`.`usernotes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `stage_db`.`usernotes` ;

CREATE TABLE IF NOT EXISTS `stage_db`.`usernotes` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(45) NOT NULL,
  `text` VARCHAR(1024) NOT NULL,
  `item_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `item_id_idx` (`item_id` ASC),
  CONSTRAINT `item_id`
    FOREIGN KEY (`item_id`)
    REFERENCES `stage_db`.`items` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
