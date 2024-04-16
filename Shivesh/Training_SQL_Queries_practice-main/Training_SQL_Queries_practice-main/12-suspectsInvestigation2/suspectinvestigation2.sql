-- creating Table
CREATE TABLE suspectinvestigation2(
	id INT AUTO_INCREMENT,
    name VARCHAR(20),
    surname VARCHAR(20),
    height int,
    weight int,
    PRIMARY KEY(id)

);


-- Inserting data to Table
INSERT INTO suspect(name, surname, height, weight) VALUES ("John", "Doe", 165, 60),
("Bill", "Green", 170, 67),
("Baelfire", "Grehn", 172, 70),
("Bill", "Gretan", 185, 55),
("Brendon", "Grewn", 180, 50),
("bill", "Green", 172, 50);



SELECT id, name, surname FROM Suspect WHERE height <= 170 OR name NOT LIKE 'B%' OR surname NOT LIKE 'Gre_n' ORDER BY id ASC