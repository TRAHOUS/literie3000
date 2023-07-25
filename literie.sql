CREATE DATABASE literie;

USE literie;

CREATE TABLE matelas(
    id SMALLINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    picture VARCHAR(255),
    dimension VARCHAR(20),
    price DECIMAL(10,2)
);

INSERT INTO matelas
(name, description,picture, dimension,price);