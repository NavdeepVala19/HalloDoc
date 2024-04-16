
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


SELECT
    country,
    SUM(CASE WHEN leisure_activity_type = 'Adventure park' THEN number_of_places ELSE 0 END) AS adventure_park,
    SUM(CASE WHEN leisure_activity_type = 'Golf' THEN number_of_places ELSE 0 END) AS golf,
    SUM(CASE WHEN leisure_activity_type = 'River cruise' THEN number_of_places ELSE 0 END) AS river_cruise,
    SUM(CASE WHEN leisure_activity_type = 'Kart racing' THEN number_of_places ELSE 0 END) AS kart_racing
FROM countryActivities
GROUP BY country
ORDER BY country;
