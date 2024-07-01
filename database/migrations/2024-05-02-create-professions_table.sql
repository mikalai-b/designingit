CREATE TABLE professions (
                             id INT AUTO_INCREMENT NOT NULL,
                             title VARCHAR(255) NOT NULL,
                             description LONGTEXT DEFAULT NULL,
                             date_created DATETIME NOT NULL,
                             date_modified DATETIME DEFAULT NULL,
                             PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB;

INSERT INTO professions (title, description, date_created) VALUES ('Dermatologist', NULL, NOW()), ('Psychiatrist', NULL, NOW());

ALTER TABLE people ADD COLUMN profession_id INT DEFAULT NULL AFTER last_name;


