ALTER TABLE products ADD default_refills INT DEFAULT NULL, ADD default_expiration INT DEFAULT NULL, ADD default_period INT DEFAULT NULL, ADD available_periods LONGTEXT DEFAULT NULL COMMENT '(DC2Type:array)', ADD default_auto_renewal INT DEFAULT NULL, ADD require_auto_renewal TINYINT(1) DEFAULT NULL;