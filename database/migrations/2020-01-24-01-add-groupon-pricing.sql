ALTER TABLE products ADD groupon_price `price` decimal(10,0) DEFAULT NULL;
UPDATE products SET groupon_price = price; UPDATE products SET price = 160 WHERE type = 2;