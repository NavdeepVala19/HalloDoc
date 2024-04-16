CREATE TABLE users (
  id INT,
  city VARCHAR(100)
);

INSERT INTO users (id, city) VALUES
(1, 'San Francisco'),
(2, 'Moscow'),
(4, 'London'),
(5, 'Washington'),
(6, 'New York'),
(7, 'Saint Petersburg'),
(8, 'Helsinki');


CREATE TABLE cities (
  city VARCHAR(50),
  country VARCHAR(50)
);

INSERT INTO cities (city, country) VALUES
('Moscow', 'Russia'),
('Saint Petersburg', 'Russia'),
('San Francisco', 'USA'),
('Washington', 'USA'),
('New York', 'USA'),
('London', 'England');


-- Doubt
SELECT users.id, IFNULL(cities.country, 'unknown') as country
    FROM users
    LEFT JOIN cities
    ON users.city = cities.city
    ORDER BY 1;