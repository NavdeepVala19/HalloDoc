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



 SELECT SUM(IF(type = "human", 2, 4)) as summary_legs
    FROM creatures
    ORDER BY id;