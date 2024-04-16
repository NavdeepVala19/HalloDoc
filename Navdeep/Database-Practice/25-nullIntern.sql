CREATE TABLE departments (
    id INT PRIMARY KEY,
    name VARCHAR(100),
    description TEXT
);


INSERT INTO departments (id, name, description) VALUES
(1, 'finance', NULL),
(2, 'marketing', NULL),
(3, 'IT', 'not nil'),
(4, 'accounting', '-');

SELECT COUNT(id) AS number_of_nulls FROM departments
    WHERE description IS NULL OR 
    TRIM(description) IN ("null", "nil", "-");

