CREATE TABLE ips (
    id INT PRIMARY KEY,
    ip VARCHAR(15) UNIQUE
);


INSERT INTO ips (id, ip) VALUES
(4, '1.1.1.1'),
(3, '1.111.111.11'),
(2, '11.11.11.11'),
(1, '11.11.11.11'),
(5, '11.11.11.111');

-- Doubt
SELECT *
    FROM ips
    WHERE IS_IPV4(ip)
    AND length(SUBSTRING_INDEX(ip, '.', -2)) > 3
    ORDER BY id;