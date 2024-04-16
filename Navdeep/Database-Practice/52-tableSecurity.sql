CREATE TABLE employees (
  id INT,
  name VARCHAR(50),
  date_joined DATE,
  salary INT
);

INSERT INTO employees (id, name, date_joined, salary) 
VALUES
(1, 'Farah Dahlquist', '2006-03-05', 2000),
(2, 'Yessenia Lininger', '2008-04-11', 2500),
(3, 'Justin Penrose', '2009-09-12', 2400),
(4, 'Josphine Clickner', '2007-07-25', 3000),
(5, 'Linwood Mcquiston', '2011-01-15', 4000);

-- Doubt
CREATE VIEW employee_view AS
SELECT id, name, YEAR(date_joined) AS date_joined, '-' AS salary
FROM employees;
