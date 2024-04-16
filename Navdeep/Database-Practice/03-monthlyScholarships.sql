
-- creating Table
CREATE TABLE scholarships(
	id INT,
    scholarship INT
);


-- Inserting data to Table
INSERT INTO scholarships VALUES (1, 12000),
(2, 18000),
(3, 24000),
(4, 15000),
(5, 21000),
(6, 13000);


-- create new table
DELIMITER $$
CREATE PROCEDURE scholar () 
BEGIN
SELECT id, scholarship/12 as scholarship FROM scholarships;
END $$
DELIMITER $$

-- CALL PROCEDURE
CALL scholar();