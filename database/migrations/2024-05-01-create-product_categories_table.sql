CREATE TABLE product_categories (
                                    id INT AUTO_INCREMENT NOT NULL,
                                    slug VARCHAR(255) NOT NULL,
                                    title VARCHAR(255) NOT NULL,
                                    description LONGTEXT DEFAULT NULL,
                                    date_created DATETIME NOT NULL,
                                    date_modified DATETIME DEFAULT NULL,
                                    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB;

INSERT INTO product_categories (slug, title, description, date_created) VALUES ('derma', 'Derma', NULL, NOW()), ('mental-health','Mental health',NULL, NOW());

ALTER TABLE products ADD COLUMN category_id INT DEFAULT NULL AFTER type;
