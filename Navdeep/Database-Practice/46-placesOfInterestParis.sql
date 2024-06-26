CREATE TABLE sights (
  id INT,
  name VARCHAR(255),
  x FLOAT,
  y FLOAT
);

INSERT INTO sights (id, name, x, y) VALUES
(1, 'Tower of London', 51508.026, -7.5939),
(2, 'Trafalgar Square', 51508.040, -12.7899),
(3, 'London Eye', 51503.538, -11.9371),
(4, 'The Shard', 51504.533, -8.6028);

SELECT a.name as place1, b.name as place2
    FROM sights a
    JOIN sights b ON(a.name < b.name)
    WHERE  ST_DISTANCE(POINT(a.`x`,a.`y`), POINT(b.`x`, b.`y`)) < 5
    ORDER BY place1, place2;