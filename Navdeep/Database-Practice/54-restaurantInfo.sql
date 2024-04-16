CREATE TABLE restaurants (
  id INT,
  name VARCHAR(255)
);

INSERT INTO restaurants (id, name) VALUES (1, 'The Big City Barbecue'),
(2, 'Roadhouse'),
(3, 'Hibiscus'),
(4, 'The Waterfront Courtyard'),
(5, 'The Royal Spices');

ALTER TABLE restaurants 
        ADD COLUMN description VARCHAR(100) DEFAULT 'TBD',
        ADD COLUMN active INT DEFAULT 1;   

    SELECT * FROM restaurants ORDER BY id;