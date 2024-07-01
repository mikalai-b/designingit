DROP TABLE product_questions;
DROP TABLE product_question;
DROP TABLE prescriptions;
DROP TABLE line_items;
DROP TABLE answers;
DROP TABLE questions;
DROP TABLE products;
DROP TABLE product_types;

CREATE TABLE questions (id INT AUTO_INCREMENT NOT NULL, type INT DEFAULT NULL, date_created DATETIME NOT NULL, date_modified DATETIME DEFAULT NULL, content VARCHAR(255) NOT NULL, `order` INT DEFAULT 0 NOT NULL, INDEX IDX_8ADC54D58CDE5729 (type), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE product_question (question_id INT NOT NULL, product_id CHAR(36) NOT NULL COMMENT '(DC2Type:uuid)', INDEX IDX_7D4723D91E27F6BF (question_id), INDEX IDX_7D4723D94584665A (product_id), PRIMARY KEY(question_id, product_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE answers (id CHAR(36) NOT NULL COMMENT '(DC2Type:uuid)', date_created DATETIME NOT NULL, date_modified DATETIME DEFAULT NULL, question VARCHAR(255) NOT NULL, answer LONGTEXT NOT NULL, type INT DEFAULT NULL, INDEX IDX_50D0C6068CDE5729 (type), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE product_types (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, instructions LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE products (id CHAR(36) NOT NULL COMMENT '(DC2Type:uuid)', type INT DEFAULT NULL, date_created DATETIME NOT NULL, date_modified DATETIME DEFAULT NULL, name VARCHAR(255) NOT NULL, strength VARCHAR(255) DEFAULT NULL, quantity VARCHAR(255) DEFAULT NULL, prescription_only TINYINT(1) DEFAULT '1' NOT NULL, info VARCHAR(255) DEFAULT NULL, INDEX IDX_B3BA5A5A8CDE5729 (type), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE product_questions (product CHAR(36) NOT NULL COMMENT '(DC2Type:uuid)', question INT NOT NULL, INDEX IDX_E47CE258D34A04AD (product), INDEX IDX_E47CE258B6F7494E (question), PRIMARY KEY(product, question)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE line_items (id INT AUTO_INCREMENT NOT NULL, price DOUBLE PRECISION NOT NULL, type INT DEFAULT NULL, INDEX IDX_1E9ACECE8CDE5729 (type), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE prescriptions (id INT AUTO_INCREMENT NOT NULL, type INT DEFAULT NULL, product CHAR(36) DEFAULT NULL COMMENT '(DC2Type:uuid)', date_created DATETIME NOT NULL, date_modified DATETIME DEFAULT NULL, refills INT NOT NULL, date_start DATETIME NOT NULL, date_end DATETIME NOT NULL, INDEX IDX_E41E1AC38CDE5729 (type), INDEX IDX_E41E1AC3D34A04AD (product), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
ALTER TABLE questions ADD CONSTRAINT FK_8ADC54D58CDE5729 FOREIGN KEY (type) REFERENCES question_types (id) ON DELETE CASCADE;
ALTER TABLE product_question ADD CONSTRAINT FK_7D4723D91E27F6BF FOREIGN KEY (question_id) REFERENCES questions (id) ON DELETE CASCADE;
ALTER TABLE product_question ADD CONSTRAINT FK_7D4723D94584665A FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE;
ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5A8CDE5729 FOREIGN KEY (type) REFERENCES product_types (id) ON DELETE CASCADE;
ALTER TABLE product_questions ADD CONSTRAINT FK_E47CE258D34A04AD FOREIGN KEY (product) REFERENCES products (id);
ALTER TABLE product_questions ADD CONSTRAINT FK_E47CE258B6F7494E FOREIGN KEY (question) REFERENCES questions (id);
ALTER TABLE prescriptions ADD CONSTRAINT FK_E41E1AC38CDE5729 FOREIGN KEY (type) REFERENCES consultations (id) ON DELETE CASCADE;
ALTER TABLE prescriptions ADD CONSTRAINT FK_E41E1AC3D34A04AD FOREIGN KEY (product) REFERENCES products (id) ON DELETE CASCADE;
