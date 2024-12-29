CREATE DATABASE car_dealership;

USE car_dealership;

CREATE TABLE cars (
    id INT AUTO_INCREMENT PRIMARY KEY,
    make VARCHAR(50),
    model VARCHAR(50),
    year INT,
    price int
);
