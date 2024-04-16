  CREATE TABLE cities (
  id INT,
  x INT,
  y INT
);

INSERT INTO user_settings (user_id, timeshift, hours)
VALUES 
(1, 0, 0),
(2, 1, 1),
(3, 2, 2),
(4, 4, 2);

-- Doubt
SELECT ROUND(SUM(SQRT(POWER(p.x - c.x, 2) + POWER(p.y - c.y, 2))), 9) AS total
FROM cities AS c
LEFT JOIN cities AS p ON c.id = p.id + 1
WHERE p.id IS NOT NULL;