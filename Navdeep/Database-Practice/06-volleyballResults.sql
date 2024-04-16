-- creating Table
CREATE TABLE results(
	name VARCHAR(255),
	country VARCHAR(255),
	scored INT,
	missed INT,
    wins INT
);


-- Inserting data to Table

INSERT INTO results VALUES("FC Tokyo","Japan",26,28,1),
("Fujian","China",24,26,0),
("Jesus Maria","Argentina",25,23,3),
("University Blues","Australia",16,25,2);

SELECT * FROM `results` order by wins;  