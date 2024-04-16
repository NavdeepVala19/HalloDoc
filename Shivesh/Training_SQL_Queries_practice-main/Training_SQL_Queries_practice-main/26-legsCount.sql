CREATE TABLE creatures (
    id INT PRIMARY KEY,
    name VARCHAR(100),
    type VARCHAR(50)
);


INSERT INTO creatures (id, name, type) VALUES
(1, 'Mike', 'human'),
(2, 'Misty', 'cat'),
(3, 'Max', 'dog'),
(4, 'Tiger', 'human');


SELECT
    SUM(CASE WHEN type = 'human' THEN 2
             WHEN type IN ('cat', 'dog') THEN 4
             ELSE 0
        END) AS summary_legs
FROM creatures;
