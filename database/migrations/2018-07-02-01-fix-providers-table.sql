CREATE TABLE providers (person INT NOT NULL, date_created DATETIME NOT NULL, date_modified DATETIME DEFAULT NULL, PRIMARY KEY(person)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
ALTER TABLE providers ADD CONSTRAINT FK_E225D41734DCD176 FOREIGN KEY (person) REFERENCES people (id) ON DELETE CASCADE;
ALTER TABLE permissions CHANGE description description VARCHAR(255) DEFAULT NULL;
ALTER TABLE consultations CHANGE patient patient INT DEFAULT NULL, CHANGE provider provider INT DEFAULT NULL, CHANGE `order` `order` INT DEFAULT NULL, CHANGE date_modified date_modified DATETIME DEFAULT NULL;
ALTER TABLE questions CHANGE type type INT DEFAULT NULL, CHANGE date_modified date_modified DATETIME DEFAULT NULL;
ALTER TABLE roles CHANGE description description VARCHAR(255) DEFAULT NULL;
ALTER TABLE messages CHANGE parent parent CHAR(36) DEFAULT NULL COMMENT '(DC2Type:uuid)', CHANGE sender sender INT DEFAULT NULL, CHANGE date_modified date_modified DATETIME DEFAULT NULL;
ALTER TABLE question_types CHANGE template template VARCHAR(255) DEFAULT NULL;
ALTER TABLE answers CHANGE type type INT DEFAULT NULL, CHANGE consultation consultation INT DEFAULT NULL, CHANGE date_modified date_modified DATETIME DEFAULT NULL;
ALTER TABLE orders CHANGE person person INT DEFAULT NULL, CHANGE provider provider INT DEFAULT NULL, CHANGE date_modified date_modified DATETIME DEFAULT NULL;
ALTER TABLE products CHANGE type type INT DEFAULT NULL, CHANGE date_modified date_modified DATETIME DEFAULT NULL, CHANGE strength strength VARCHAR(255) DEFAULT NULL, CHANGE quantity quantity VARCHAR(255) DEFAULT NULL, CHANGE info info VARCHAR(255) DEFAULT NULL, CHANGE price price VARCHAR(255) DEFAULT NULL, CHANGE thumbnail thumbnail VARCHAR(255) DEFAULT NULL;
ALTER TABLE people CHANGE prescription_state prescription_state VARCHAR(255) DEFAULT NULL, CHANGE state state VARCHAR(255) DEFAULT NULL, CHANGE date_modified date_modified DATETIME DEFAULT NULL, CHANGE first_name first_name VARCHAR(255) DEFAULT NULL, CHANGE last_name last_name VARCHAR(255) DEFAULT NULL, CHANGE title title VARCHAR(255) DEFAULT NULL, CHANGE credentials credentials VARCHAR(255) DEFAULT NULL, CHANGE address_line_1 address_line_1 VARCHAR(255) DEFAULT NULL, CHANGE address_line_2 address_line_2 VARCHAR(255) DEFAULT NULL, CHANGE city city VARCHAR(255) DEFAULT NULL, CHANGE postal_code postal_code VARCHAR(255) DEFAULT NULL, CHANGE remember_token remember_token VARCHAR(255) DEFAULT NULL;
ALTER TABLE line_items CHANGE product product CHAR(36) DEFAULT NULL COMMENT '(DC2Type:uuid)', CHANGE `order` `order` INT DEFAULT NULL;
ALTER TABLE state_providers DROP FOREIGN KEY FK_AAA8113A92C4739C;
ALTER TABLE state_providers ADD CONSTRAINT FK_AAA8113A92C4739C FOREIGN KEY (provider) REFERENCES providers (person);
ALTER TABLE accounts CHANGE date_modified date_modified DATETIME DEFAULT NULL, CHANGE date_last_logged_in date_last_logged_in DATETIME DEFAULT NULL, CHANGE password password VARCHAR(255) DEFAULT NULL;
ALTER TABLE prescriptions CHANGE consultation consultation INT DEFAULT NULL, CHANGE product product CHAR(36) DEFAULT NULL COMMENT '(DC2Type:uuid)', CHANGE date_modified date_modified DATETIME DEFAULT NULL;
ALTER TABLE message_receipts CHANGE recipient recipient INT DEFAULT NULL, CHANGE message message CHAR(36) DEFAULT NULL COMMENT '(DC2Type:uuid)', CHANGE date_modified date_modified DATETIME DEFAULT NULL, CHANGE date_seen date_seen DATETIME DEFAULT NULL;
