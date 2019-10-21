-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema certificados
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema certificados
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `certificados` DEFAULT CHARACTER SET utf8 ;
USE `certificados` ;

-- -----------------------------------------------------
-- Table `certificados`.`tipo_certificado`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `certificados`.`tipo_certificado` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `descripcion` VARCHAR(45) NULL,
  `estado` INT NULL DEFAULT 1,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
COMMENT = '	';


-- -----------------------------------------------------
-- Table `certificados`.`certificado`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `certificados`.`certificado` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `descripcion` VARCHAR(45) NULL,
  `tipo_certificado_id` INT NOT NULL,
  `estado` INT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  INDEX `fk_certificados_tipo_certificado_idx` (`tipo_certificado_id` ASC),
  CONSTRAINT `fk_certificados_tipo_certificado`
    FOREIGN KEY (`tipo_certificado_id`)
    REFERENCES `certificados`.`tipo_certificado` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `certificados`.`tipo_evento`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `certificados`.`tipo_evento` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `descripcion` VARCHAR(45) NULL,
  `estado` INT NULL DEFAULT 1,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `certificados`.`evento`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `certificados`.`evento` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `descripcion` VARCHAR(450) NULL,
  `fecha_ini` DATETIME NULL,
  `fecha_fin` DATETIME NULL,
  `cupo` INT NULL,
  `evento_sgi_id` INT NULL,
  `tipo_evento_id` INT NOT NULL,
  `estado` INT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  INDEX `fk_evento_tipo_evento1_idx` (`tipo_evento_id` ASC) ,
  CONSTRAINT `fk_evento_tipo_evento1`
    FOREIGN KEY (`tipo_evento_id`)
    REFERENCES `certificados`.`tipo_evento` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `certificados`.`template`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `certificados`.`template` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `descripcion` VARCHAR(45) NULL,
  `codigo` LONGTEXT NULL,
  `estado` INT NULL DEFAULT 1,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `certificados`.`certificado_evento`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `certificados`.`certificado_evento` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `template_id` INT NULL,
  `certificado_id` INT NOT NULL,
  `evento_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_certificado_evento_template1_idx` (`template_id` ASC) ,
  INDEX `fk_certificado_evento_certificado1_idx` (`certificado_id` ASC) ,
  INDEX `fk_certificado_evento_evento1_idx` (`evento_id` ASC) ,
  CONSTRAINT `fk_certificado_evento_template1`
    FOREIGN KEY (`template_id`)
    REFERENCES `certificados`.`template` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_certificado_evento_certificado1`
    FOREIGN KEY (`certificado_id`)
    REFERENCES `certificados`.`certificado` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_certificado_evento_evento1`
    FOREIGN KEY (`evento_id`)
    REFERENCES `certificados`.`evento` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `certificados`.`persona`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `certificados`.`persona` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `apellido_nombre` VARCHAR(450) NULL,
  `dni` VARCHAR(45) NULL,
  `direccion` VARCHAR(45) NULL,
  `email` VARCHAR(45) NULL,
  `telefono` VARCHAR(45) NULL,
  `sexo` VARCHAR(45) NULL,
  `fecha_nac` DATE NULL,
  `estado` INT NULL DEFAULT 1,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `certificados`.`inscripto`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `certificados`.`inscripto` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `fecha_insc` DATETIME NULL,
  `evento_id` INT NOT NULL,
  `persona_id` INT NOT NULL,
  `estado` INT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  INDEX `fk_inscripto_evento1_idx` (`evento_id` ASC) ,
  INDEX `fk_inscripto_persona1_idx` (`persona_id` ASC) ,
  CONSTRAINT `fk_inscripto_evento1`
    FOREIGN KEY (`evento_id`)
    REFERENCES `certificados`.`evento` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_inscripto_persona1`
    FOREIGN KEY (`persona_id`)
    REFERENCES `certificados`.`persona` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `certificados`.`requisito`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `certificados`.`requisito` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `descripcion` VARCHAR(45) NULL,
  `estado` INT NULL DEFAULT 1,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `certificados`.`certificado_evento_requisito`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `certificados`.`certificado_evento_requisito` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `requisito_id` INT NOT NULL,
  `certificado_evento_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_evento_requisito_requisito1_idx` (`requisito_id` ASC) ,
  INDEX `fk_certificado_evento_requisito_certificado_evento1_idx` (`certificado_evento_id` ASC) ,
  CONSTRAINT `fk_evento_requisito_requisito1`
    FOREIGN KEY (`requisito_id`)
    REFERENCES `certificados`.`requisito` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_certificado_evento_requisito_certificado_evento1`
    FOREIGN KEY (`certificado_evento_id`)
    REFERENCES `certificados`.`certificado_evento` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `certificados`.`inscripto_evento_requisito`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `certificados`.`inscripto_evento_requisito` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `inscripto_id` INT NOT NULL,
  `certificado_evento_requisito_id` INT NOT NULL,
  `excluir` TINYINT NULL DEFAULT 0 COMMENT 'para casos en que un inscripto no necesite cumplir el requisito',
  PRIMARY KEY (`id`),
  INDEX `fk_inscripto_evento_requisito_inscripto1_idx` (`inscripto_id` ASC) ,
  INDEX `fk_inscripto_evento_requisito_certificado_evento_requisito1_idx` (`certificado_evento_requisito_id` ASC) ,
  CONSTRAINT `fk_inscripto_evento_requisito_inscripto1`
    FOREIGN KEY (`inscripto_id`)
    REFERENCES `certificados`.`inscripto` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_inscripto_evento_requisito_certificado_evento_requisito1`
    FOREIGN KEY (`certificado_evento_requisito_id`)
    REFERENCES `certificados`.`certificado_evento_requisito` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `certificados`.`inscripto_certificado`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `certificados`.`inscripto_certificado` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `fecha_obt` DATETIME NULL,
  `inscripto_id` INT NOT NULL,
  `certificado_evento_id` INT NOT NULL,
  `estado` INT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  INDEX `fk_inscripto_certificado_inscripto1_idx` (`inscripto_id` ASC) ,
  INDEX `fk_inscripto_certificado_certificado_evento1_idx` (`certificado_evento_id` ASC) ,
  CONSTRAINT `fk_inscripto_certificado_inscripto1`
    FOREIGN KEY (`inscripto_id`)
    REFERENCES `certificados`.`inscripto` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_inscripto_certificado_certificado_evento1`
    FOREIGN KEY (`certificado_evento_id`)
    REFERENCES `certificados`.`certificado_evento` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
