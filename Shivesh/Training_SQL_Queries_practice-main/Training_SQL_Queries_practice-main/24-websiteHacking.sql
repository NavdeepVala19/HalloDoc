CREATE TABLE user_table (
    id INT PRIMARY KEY,
    login VARCHAR(50) UNIQUE,
    name VARCHAR(100),
    type VARCHAR(50)
);


INSERT INTO user_table (id, login, name, type) VALUES
(1, 'Ann92', 'Ann', 'user'),
(2, 'Bob88', 'Bob', 'user'),
(3, 'Carol412', 'Carol', 'admin'),
(4, 'Deril39', 'Deril', 'admin'),
(5, 'EvaGrin', 'Eva', 'user');


SELECT id, login, name
FROM users
WHERE type IS NOT NULL;  -- Add this condition to include all existing types
