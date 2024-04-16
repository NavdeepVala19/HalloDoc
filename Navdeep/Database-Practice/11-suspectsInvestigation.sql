-- creating Table
CREATE TABLE suspect(
	id INT AUTO_INCREMENT,
    name VARCHAR(20),
    surname VARCHAR(20),
    height int,
    weight int,
    PRIMARY KEY(id)
);


-- Inserting data to Table
INSERT INTO suspect(name, surname, height, weight) VALUES ("John", "Doe", 165, 60),
("Bill", "Green", 170, 60),
("Baelfire", "Grehn", 172, 70),
("Bill", "Gretan", 165, 55),
("Brendon", "Grewn", 150, 50),
("bill", "Green", 169, 50);

SELECT id, name, surname from suspect
WHERE height <= 170 AND name LIKE 'B%' AND surname LIKE "Gre_n"
ORDER BY id