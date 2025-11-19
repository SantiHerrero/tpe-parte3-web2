CREATE TABLE IF NOT EXISTS `usuarios` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `nombre_usuario` VARCHAR(100),
    `nombre` VARCHAR(50),
    `apellido` VARCHAR(50),
    `tipo` SMALLINT,
    `contrasenia` VARCHAR(255),
    PRIMARY KEY(`id`)
);

CREATE TABLE IF NOT EXISTS `laboratorios` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `nombre` VARCHAR(50) NOT NULL,
    `descripcion` VARCHAR(512),
    `url` VARCHAR(255) NOT NULL,
    `fundador` VARCHAR(255) NOT NULL,
    `pais` VARCHAR(50) NOT NULL,
    PRIMARY KEY(`id`)
);

CREATE TABLE IF NOT EXISTS `perfumes` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `id_laboratorio` INT NOT NULL,
    `precio` DECIMAL(10,2) NOT NULL,
    `codigo` VARCHAR(50),
    `duracion` INT NOT NULL,
    `aroma` VARCHAR(255),
    `sexo` SMALLINT NOT NULL,
    PRIMARY KEY(`id`),
    FOREIGN KEY (`id_laboratorio`) REFERENCES `laboratorios`(`id`)
        ON UPDATE NO ACTION
        ON DELETE NO ACTION
);

INSERT IGNORE INTO laboratorios (id,nombre,descripcion,url,fundador,pais) VALUES
(1,'AromaLab','Laboratorio de ejemplo','','Dr. Olfato','Argentina'),
(2,'EssenceCo','Creador de fragancias','','Sra. Nota','Francia');

INSERT IGNORE INTO perfumes (id,id_laboratorio,precio,codigo,duracion,aroma,sexo) VALUES
(1,1,1999.00,'AROMA-001',120,'Cítrico',0),
(2,1,2499.50,'AROMA-002',180,'Amaderado',1),
(3,2,1599.99,'ESS-001',90,'Floral',2);
