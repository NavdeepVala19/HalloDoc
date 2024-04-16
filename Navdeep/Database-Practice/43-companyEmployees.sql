CREATE TABLE departments(
dep_name VARCHAR(20);
);

INSERT INTO departments VALUES ("IT", "PD");

CREATE TABLE employees(
    emp_name VARCHAR(20);
)

INSERT INTO employees VALUES ("Donald Cole", "James Richard", "John Tucker");

SELECT departments.dep_name, employees.emp_name
    FROM departments
    CROSS JOIN employees
    ORDER BY dep_name, emp_name;