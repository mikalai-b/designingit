ALTER TABLE product_types ADD consent_form VARCHAR(255) DEFAULT NULL, ADD default_period INT DEFAULT NULL;
ALTER TABLE product_types ADD available_periods LONGTEXT DEFAULT NULL COMMENT '(DC2Type:array)', ADD default_auto_renewal TINYINT(1) DEFAULT NULL;
ALTER TABLE line_items ADD period INT DEFAULT NULL;
ALTER TABLE orders DROP auto_refill;
