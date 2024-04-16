-- Assignment 3 :  Retrieve data using Group By clause

-- Department Table creation
create table
    Department (
        dept_id int PRIMARY KEY ,
        dept_name varchar(50) NOT NULL
    );

-- Department Table creation
create table
    Employee (
        emp_id int PRIMARY KEY ,
        dept_id int ,
        mngr_id int not null,
        emp_name varchar(20) not null,
        salary int,
        FOREIGN KEY (dept_id) REFERENCES Department(dept_id) 
    );

-- Inserting Data to Department table
INSERT INTO Department (dept_id, dept_name) 
VALUES  
(1, 'Information Technology'),
(2, 'Marketing'),
(3, 'Sales'),
(4, 'Networking'),
(5, 'Civil');


-- filling data in Employee

INSERT INTO Employee (emp_id, dept_id, mngr_id, emp_name, salary)
VALUES 
(1, 1,1,'Navdeep',80000),
(2, 1,2,'Ajay',50000),
(3, 1,1,'Rahul',55000),
(4, 1,3,'Vijay',50000),
(5, 2,2,'John',70000),
(6, 2,1,'Daina',30000),
(7,2,3,'David',55000),
(8,2,1,'Kayl',60000),
(9,3,2,'Jay',65000),
(10,3,1,'kevin',55000),
(11,4,1,'jeny',61000),
(12,4,2,'Yash',60000),
(13,5,1,'Ravi',65000);


-- 1. write a SQL query to find Employees who have the biggest salary in their Department
SELECT e.emp_id, e.mngr_id, e.emp_name, e.salary, d.dept_id, d.dept_name 
FROM Employee e
INNER JOIN Department d ON e.dept_id = d.dept_id
WHERE (e.salary) IN (
    SELECT MAX(Salary) 
    FROM Employee 
    WHERE d.dept_id = e.dept_id
    GROUP BY dept_id
);


-- 2. write a SQL query to find Departments that have less than 3 people in it
SELECT d.dept_name, COUNT(e.dept_id) AS "No Of Employees" 
FROM Employee e
INNER JOIN Department d ON d.dept_id = e.dept_id
GROUP BY d.dept_name
HAVING count(e.dept_id) > 3;

-- 3. write a SQL query to find All Department along with the number of people there
SELECT d.dept_name, COUNT(e.dept_id) AS "No Of Employees"
FROM Employee e
INNER JOIN Department d ON d.dept_id = e.dept_id
GROUP BY d.dept_name;

-- 4. write a SQL query to find All Department along with the total salary there
select d.dept_name AS "Department", sum(e.salary) AS "Total Salary" 
FROM Employee e
JOIN Department d ON d.dept_id=e.dept_id
GROUP BY dept_name;