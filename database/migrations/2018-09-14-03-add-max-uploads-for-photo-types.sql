ALTER TABLE photo_types ADD max_uploads INT DEFAULT 1 NOT NULL, CHANGE date_modified date_modified DATETIME DEFAULT NULL, CHANGE description description VARCHAR(255) DEFAULT NULL;
