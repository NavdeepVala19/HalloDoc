CREATE TABLE employees (
    id INT,
    name VARCHAR(255),
    salary INT
);


INSERT INTO employees (id, name, salary)
VALUES
    (1, 'John', 1200),
    (2, 'Bill', 1000),
    (3, 'Mike', 1300),
    (4, 'Katy', 1200),
    (5, 'Brendon', 1300),
    (6, 'Amanda', 900);


-- Doubt
    SELECT SUM(CASE WHEN salary = (SELECT MAX(salary) FROM employees) THEN salary
           WHEN salary = (SELECT MIN(salary) FROM employees) THEN salary
           ELSE 0 END) AS difference
FROM employees;
