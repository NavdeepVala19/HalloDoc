CREATE TABLE department (
    dept_id INT PRIMARY KEY,
    dept_name VARCHAR(255) NOT NULL
);

INSERT INTO department (dept_id, dept_name)
VALUES 
    (1, 'HR'),
    (2, 'BA'),
    (3, 'BD'),
    (4, 'Product Manager'),
    (5, 'Sales Manager'),
    (6, 'Finance'),
    (7, 'Project Manager'),
    (8, 'Android'),
    (9, 'FrontEnd'),
    (10, 'BackEnd');  


CREATE TABLE Employee (
    emp_id INT PRIMARY KEY,
    dept_id INT,
    mngr_id INT,
    emp_name VARCHAR(255) NOT NULL,
    salary DECIMAL(10, 2)
);


INSERT INTO Employee (emp_id, dept_id, mngr_id, emp_name, salary)
VALUES 
    (1, 3, 2, 'John Doe', 60000.00),
    (2, 8, 5, 'Jane Smith', 75000.00),
    (3, 6, 1, 'Bob Johnson', 50000.00),
    (4, 4, 3, 'Alice Williams', 65000.00),
    (5, 10, 7, 'Charlie Brown', 70000.00),
    (6, 2, 1, 'Emily Davis', 55000.00),
    (7, 9, 6, 'David Wilson', 80000.00),
    (8, 1, 4, 'Ella Miller', 70000.00),
    (9, 7, 2, 'Frank Taylor', 60000.00),
    (10, 5, 3, 'Grace Martin', 65000.00),
    (11, 3, 2, 'Henry Anderson', 75000.00),
    (12, 8, 5, 'Ivy Clark', 55000.00),
    (13, 6, 1, 'Jack Thompson', 80000.00),
    (14, 4, 3, 'Kelly Harris', 50000.00),
    (15, 10, 7, 'Liam Garcia', 70000.00),
    (16, 2, 1, 'Mia Allen', 60000.00),
    (17, 9, 6, 'Noah Turner', 65000.00),
    (18, 1, 4, 'Olivia Lewis', 75000.00),
    (19, 7, 2, 'Peter White', 55000.00),
    (20, 5, 3, 'Quinn Scott', 80000.00),
    (21, 3, 2, 'Ruby Baker', 70000.00),
    (22, 8, 5, 'Samuel Hall', 60000.00),
    (23, 6, 1, 'Tessa Harris', 65000.00),
    (24, 4, 3, 'Ulysses Ford', 75000.00),
    (25, 10, 7, 'Violet Johnson', 55000.00);



-- 1.	write a SQL query to find Employees who have the biggest salary in their Department

SELECT e.*
FROM Employee e
JOIN (
    SELECT dept_id, MAX(salary) AS max_salary
    FROM Employee
    GROUP BY dept_id
) AS max_salaries
ON e.dept_id = max_salaries.dept_id AND e.salary = max_salaries.max_salary;


-- 2.	write a SQL query to find Departments that have less than 3 people in it

SELECT dept_id, dept_name
FROM Department
WHERE dept_id IN (
    SELECT dept_id
    FROM Employee
    GROUP BY dept_id
    HAVING COUNT(emp_id) < 3
);


-- 3.	write a SQL query to find All Department along with the number of people there

  SELECT d.*, COUNT(e.emp_id) AS num_people
  FROM Department d
  LEFT JOIN Employee e ON d.dept_id = e.dept_id
  GROUP BY d.dept_id, d.dept_name;


-- 4.	write a SQL query to find All Department along with the total salary there

SELECT d.*, SUM(e.salary) AS total_salary FROM Department d RIGHT JOIN Employee e ON d.dept_id = e.dept_id GROUP BY d.dept_id, d.dept_name;