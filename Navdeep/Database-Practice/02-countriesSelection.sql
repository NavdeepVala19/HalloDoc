-- creating Table
CREATE TABLE countries(
	name VARCHAR(255),
    continent VARCHAR(255),
    population INT
);


-- Inserting data to Table
INSERT INTO countries VALUES ("Austria", "Europe", 8767919),
("Belize", "North America", 375909),
("Botswana", "Africa", 2230905),
("Cambodia", "Asia", 15626444),
("Cameroon", "Africa", 22709892);

--  function/procedure that will find all countries from any set of countries that are in same continent

DELIMITER $$

CREATE PROCEDURE find_countries (
    continent VARCHAR(255)
)
Begin 
create table same_continent SELECT * FROM countries where countries.continent = continent;
End $$

DELIMITER $$


-- Call created procedure 
CALL find_countries("africa")