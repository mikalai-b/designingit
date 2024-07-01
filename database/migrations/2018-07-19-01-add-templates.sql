ALTER TABLE product_types ADD approve_template LONGTEXT DEFAULT NULL, ADD decline_template LONGTEXT DEFAULT NULL, DROP instructions;
ALTER TABLE product_types ADD default_refills INT DEFAULT NULL, ADD default_expiration INT DEFAULT NULL;
