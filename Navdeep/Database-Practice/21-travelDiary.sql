CREATE TABLE diary (
  id INT PRIMARY KEY,
  travel_date DATE,
  country VARCHAR(255)
);

INSERT INTO diary (id, travel_date, country)
VALUES (1, '2008-05-12', 'Ireland'),
       (2, '2010-11-04', 'France'),
       (3, '2005-10-02', 'Australia'),
       (4, '2008-06-08', 'Japan'),
       (5, '2010-08-27', 'Austria'),
       (6, '2009-02-15', 'France');

SELECT STRING_AGG(DISTINCT country, ';') AS countries FROM diary;

SELECT GROUP_CONCAT(
        DISTINCT country
        ORDER BY country ASC SEPARATOR ";"
    )
    AS countries FROM diary;