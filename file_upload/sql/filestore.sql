--  Create Database
 CREATE DATABASE filestore;

-- Create table
CREATE TABLE filestore (
  id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  filename VARCHAR(255) NOT NULL,
  mimetype VARCHAR(50) NOT NULL,
  description VARCHAR(255) NOT NULL,
  filedata MEDIUMBLOB
) DEFAULT CHARACTER SET utf8 ENGINE=InnoDB;