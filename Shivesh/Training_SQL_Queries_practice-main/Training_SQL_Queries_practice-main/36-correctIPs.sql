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
