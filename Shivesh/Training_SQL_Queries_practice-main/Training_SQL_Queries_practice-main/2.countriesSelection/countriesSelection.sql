CREATE TABLE countries ( name varchar(20), continent varchar(20), population int )


INSERT INTO countries VALUES ('austria','europe', 8767919);
INSERT INTO countries VALUES ('Belize', 'North America',375909);
INSERT INTO countries VALUES ('Botswana','Africa',2230905);
INSERT INTO countries VALUES ('Cambodia	','Asia', 15626444);
INSERT INTO countries VALUES ('Cameroon', 'Africa',22709892);

select * from countries where continent="Africa" order by name

DELIMITER //
CREATE FUNCTION getAfricanCountries()
RETURNS TABLE (name VARCHAR(255), continent VARCHAR(255), population BIGINT)
BEGIN
  RETURN SELECT name, continent, population FROM countries WHERE continent = 'Africa' ORDER BY name;
END;
//
DELIMITER ;