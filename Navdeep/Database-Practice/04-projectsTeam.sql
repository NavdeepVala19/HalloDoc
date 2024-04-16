
-- creating Table
CREATE TABLE projectLog(
	id INT AUTO_INCREMENT,
    name VARCHAR(255),
    description VARCHAR(255),
    timestamp DATETIME,
    PRIMARY KEY(id)
);


-- Inserting data to Table
INSERT INTO projectLog (name, description, timestamp) VALUES ("James Smith", "add new logo", "2015-11-10 15:24:32"),
("John Johnson", "update license", "2015-11-10 15:50:01"),
("John Johnson", "fix typos", "2015-11-10 15:55:01"),
("James Smith", "update logo", "2015-11-10 17:53:04"),
("James Smith", "delete old logo", "2015-11-10 17:54:04"),
("Michael Williams", "fix the build", "2015-11-12 13:37:00"),
("Mary Troppins", "add new feature", "2015-11-08 17:54:04"),
("James Smith", "fix fonts", "2015-11-14 13:54:04"),
("Richard Young", "remove unneeded files", "2015-11-14 00:00:00"),
("Michael Williams", "add tests", "2015-11-09 11:53:00");

-- New Table 
CREATE TABLE result SELECT name FROM projectLog GROUP BY name;