
CREATE TABLE countryActivities (
    id INT PRIMARY KEY,
    country VARCHAR(100),
    region VARCHAR(100),
    leisure_activity_type VARCHAR(50) CHECK (leisure_activity_type IN ('Adventure park', 'Golf', 'Kart racing', 'River cruise')),
    number_of_places INT
);


INSERT INTO countryActivities (id, country, region, leisure_activity_type, number_of_places) VALUES
(1, 'France', 'Normandy', 'River cruise', 2),
(2, 'Germany', 'Bavaria', 'Golf', 5),
(3, 'Germany', 'Berlin', 'Adventure park', 2),
(4, 'France', 'Ile-de-France', 'River cruise', 1),
(5, 'Sweden', 'Stockholm', 'River cruise', 3),
(6, 'France', 'Normandy', 'Kart racing', 4);

SELECT country,
    SUM(CASE WHEN leisure_activity_type LIKE 'A%' THEN number_of_places ELSE 0 END) as adventure_park,
    SUM(CASE WHEN leisure_activity_type LIKE 'G%' THEN number_of_places ELSE 0 END) as golf,
    SUM(CASE WHEN leisure_activity_type LIKE 'R%' THEN number_of_places ELSE 0 END) as river_cruise,
    SUM(CASE WHEN leisure_activity_type LIKE 'K%' THEN number_of_places ELSE 0 END) as kart_racing
FROM countryActivities
GROUP BY country
ORDER BY country;