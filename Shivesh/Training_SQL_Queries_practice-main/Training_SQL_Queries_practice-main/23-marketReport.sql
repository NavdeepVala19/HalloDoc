CREATE TABLE competitor (
    competitor_name VARCHAR(100),
    country VARCHAR(50)
);


INSERT INTO competitor (competitor_name, country) VALUES
('Acme Corp', 'USA'),
('GLOBEX', 'USA'),
('Openmedia', 'France'),
('K-bam', 'USA'),
('Hatdrill', 'UK'),
('Hexgreen', 'Germany'),
('D-ranron', 'France'),
('Faxla', 'Spain');


WITH CompetitorCount AS (
    SELECT
        country,
        COUNT(*) AS num_competitors
    FROM foreignCompetitors
    GROUP BY country
)

SELECT
    country,
    num_competitors
FROM CompetitorCount

UNION ALL

SELECT
    'Total:' AS country,
    SUM(num_competitors) AS num_competitors
FROM CompetitorCount

ORDER BY country;
