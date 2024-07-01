CREATE TABLE product_photo_types (product CHAR(36) NOT NULL COMMENT '(DC2Type:uuid)', photo_type INT NOT NULL, INDEX IDX_4F4E1098D34A04AD (product), INDEX IDX_4F4E1098DEFE5DD (photo_type), PRIMARY KEY(product, photo_type)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE photo_types (id INT AUTO_INCREMENT NOT NULL, date_created DATETIME NOT NULL, date_modified DATETIME DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, display_order INT DEFAULT 0 NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE photos (id INT AUTO_INCREMENT NOT NULL, type INT DEFAULT NULL, consultation INT DEFAULT NULL, date_created DATETIME NOT NULL, date_modified DATETIME DEFAULT NULL, file VARCHAR(255) NOT NULL, INDEX IDX_876E0D98CDE5729 (type), INDEX IDX_876E0D9964685A6 (consultation), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
ALTER TABLE product_photo_types ADD CONSTRAINT FK_4F4E1098D34A04AD FOREIGN KEY (product) REFERENCES products (id) ON DELETE CASCADE;
ALTER TABLE product_photo_types ADD CONSTRAINT FK_4F4E1098DEFE5DD FOREIGN KEY (photo_type) REFERENCES photo_types (id) ON DELETE CASCADE;
ALTER TABLE photos ADD CONSTRAINT FK_876E0D98CDE5729 FOREIGN KEY (type) REFERENCES photo_types (id) ON DELETE CASCADE;
ALTER TABLE photos ADD CONSTRAINT FK_876E0D9964685A6 FOREIGN KEY (consultation) REFERENCES consultations (id) ON DELETE CASCADE;
