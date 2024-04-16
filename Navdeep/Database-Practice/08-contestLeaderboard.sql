-- creating Table
CREATE TABLE leaderboard(
	id INT AUTO_INCREMENT,
    name VARCHAR(255),
    score INT,
    PRIMARY KEY(id)
);


-- Inserting data to Table
INSERT INTO leaderboard (name, score) VALUES ("gongy", 3001),
("urandom", 2401),
("eduardische", 2477),
("Gassa", 2999),
("bcc32", 2658),
("Alex_2oo8", 6000),
("mirosuaf", 2479),
("Sparik", 2399),
("thomas_holmes", 2478),
("cthaeghya", 2400);

SELECT name FROM leaderboard
ORDER BY score DESC
LIMIT 3,5;