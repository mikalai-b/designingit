ALTER TABLE orders ADD parent_id INT DEFAULT NULL;
ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE727ACA70 FOREIGN KEY (parent_id) REFERENCES orders (id);