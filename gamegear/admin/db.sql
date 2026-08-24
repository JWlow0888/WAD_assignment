CREATE DATABASE gamegear_db CHARACTER SET utf8 COLLATE utf8_general_ci;

USE gamegear_db;

CREATE TABLE admin_users (
    id INT(11) NOT NULL AUTO_INCREMENT,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    PRIMARY KEY(id),
    UNIQUE KEY email(email)
);

CREATE TABLE announcement (
	id INT(11) AUTO_INCREMENT PRIMARY KEY,
	subject VARCHAR(255),
	message TEXT,
	type CHAR(1),
	posted DATETIME
);

CREATE TABLE products (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    category VARCHAR(50),
    condition_status VARCHAR(50),
    price DECIMAL(10,2) NOT NULL,
    description TEXT,
    image_url VARCHAR(255),
    is_featured TINYINT(1) DEFAULT 0,
    is_available TINYINT(1) DEFAULT 1, 
    posted DATETIME DEFAULT CURRENT_TIMESTAMP
);