CREATE TABLE country(
    name varchar(20),
    continent varchar(20),
    population int
    )


INSERT INTO country VALUES("Grenada","North America",103328);
INSERT INTO country VALUES("Monaco","Europe",38400);
INSERT INTO country VALUES("San Marino","Europe",33005);
INSERT INTO country VALUES("Vanuatu","Australia",277500);


SELECT COUNT(*) AS "number", AVG(population) AS "average", SUM(population) AS "total" from country;