-- Assignment 2 :  Retrieve data using join with where clause

CREATE TABLE salesman
(salesman_id int primary key,
name varchar(20) not null,
city varchar(20) not null,
commission int not null);

CREATE TABLE customer
(customer_id int primary key,
cust_name varchar(20) not null,
city varchar(20) not null,
grade int not null,
salesman_id INT, 
FOREIGN KEY (salesman_id) REFERENCES salesman(salesman_id)
);

CREATE TABLE orders
(ord_no int primary key,
pur_amt int not null,
ord_date date not null,
customer_id INT,
salesman_id INT,
FOREIGN KEY (customer_id) REFERENCES customer(customer_id),
FOREIGN KEY (salesman_id) REFERENCES salesman(salesman_id)
);


INSERT INTO salesman (salesman_id, name, city, commission) 
VALUES
(11,'Pranav','Karwar',200),
(24,'Prasanna','Bengalore',300),
(39,'Prajwal','Kodagu',100),
(44,'Pooja','Hubli',500.5),
(15,'Prokta','Mysore',200.2);

INSERT INTO customer (customer_id, cust_name, city, grade, salesman_id) 
VALUES
(101,'Bhargav','Mysore',1, 15),
(206,'Ramya','Bengalore',3, 24),
(225,'Rajesh','Hubli',2, 39),
(324,'Ravi','Mangalore',5, 44),
(456,'Rajdeep','Belagavi',3, 15),
(501,'Raghu','Dharavad',4, 39),
(300,'Bhavya','Bengalore',1, 15);

INSERT INTO orders (ord_no, pur_amt, ord_date, customer_id, salesman_id) 
VALUES
(5,10000,'2020-03-25', 101, 11),
(10,5000,'2020-03-25', 456, 15),
(7,9500,'2020-04-30',225, 44),
(11,8700,'2020-07-07', 324, 24),
(17,1500,'2020-07-07', 206, 39);

-- 1. write a SQL query to find the salesperson and customer who reside in the same city. Return Salesman, cust_name and city
SELECT salesman.*, customer.cust_name 
FROM salesman
INNER JOIN customer
ON salesman.city = customer.city;

-- 2. write a SQL query to find those orders where the order amount exists between 500 and 2000. Return ord_no, purch_amt, cust_name, city
SELECT orders.ord_no,orders.pur_amt, customer.cust_name, customer.city
FROM orders
INNER JOIN customer
ON orders.customer_id = customer.customer_id
WHERE pur_amt BETWEEN 500 AND 2000;

-- 3. write a SQL query to find the salesperson(s) and the customer(s) he represents. Return Customer Name, city, Salesman, commission
SELECT customer.cust_name, customer.city, salesman.name, salesman.commission
FROM customer
INNER JOIN salesman
ON customer.salesman_id = salesman.salesman_id;

-- 4. write a SQL query to find salespeople who received commissions of more than 12 percent from the company. Return Customer Name, customer city, Salesman, commission.
SELECT customer.cust_name, customer.city, salesman.name, salesman.commission
FROM  customer 
INNER JOIN salesman
ON customer.salesman_id = salesman.salesman_id
WHERE commission > 12;

-- 5. write a SQL query to locate those salespeople who do not live in the same city where their customers live and have received a commission of more than 12% from the company. Return Customer Name, customer city, Salesman, salesman city, commission
SELECT customer.cust_name, customer.city, salesman.name, salesman.city, salesman.commission
FROM customer
INNER JOIN salesman
ON customer.salesman_id = salesman.salesman_id
WHERE commission > 12 AND customer.city != salesman.city;

-- 6. write a SQL query to find the details of an order. Return ord_no, ord_date, purch_amt, Customer Name, grade, Salesman, commission
SELECT orders.ord_no, orders.ord_date, orders.pur_amt, customer.cust_name, customer.grade, salesman.name, salesman.commission
FROM orders 
JOIN customer ON orders.customer_id = customer.customer_id
JOIN salesman ON customer.salesman_id = salesman.salesman_id;

-- 7. Write a SQL statement to join the tables salesman, customer and orders so that the same column of each table appears once and only the relational rows are returned. 
SELECT s.salesman_id, c.customer_id, s.city , s.name, s.commission, c.cust_name, c.grade, o.ord_no, o.pur_amt, o.ord_date
FROM orders o
INNER JOIN customer c ON c.customer_id = o.customer_id
INNER JOIN salesman s ON c.salesman_id = s.salesman_id;

-- 8. write a SQL query to display the customer name, customer city, grade, salesman, salesman city. The results should be sorted by ascending customer_id.
SELECT c.cust_name, c.city, c.grade, s.name, s.city 
FROM customer c
INNER JOIN salesman s ON c.salesman_id = s.salesman_id
ORDER BY c.customer_id ASC;

-- 9. write a SQL query to find those customers with a grade less than 300. Return cust_name, customer city, grade, Salesman, salesmancity. The result should be ordered by ascending customer_id. 
SELECT c.cust_name, c.city, c.grade, s.name, s.city
FROM customer c
INNER JOIN salesman s ON c.salesman_id = s.salesman_id
WHERE c.grade < 300
ORDER BY c.customer_id ASC;

-- 10. Write a SQL statement to make a report with customer name, city, order number, order date, and order amount in ascending order according to the order date to determine whether any of the existing customers have placed an order or not
SELECT c.cust_name, c.city, o.ord_no, o.ord_date, o.pur_amt
FROM customer c
LEFT JOIN orders o ON c.customer_id = o.customer_id
ORDER BY o.ord_date;

-- 11. Write a SQL statement to generate a report with customer name, city, order number, order date, order amount, salesperson name, and commission to determine if any of the existing customers have not placed orders or if they have placed orders through their salesman or by themselves
SELECT c.cust_name, c.city, o.ord_no, o.ord_date, o.pur_amt, s.name, s.commission
FROM customer c
LEFT JOIN orders o ON c.customer_id = o.customer_id
LEFT JOIN salesman s ON s.salesman_id = c.salesman_id;

-- 12. Write a SQL statement to generate a list in ascending order of salespersons who work either for one or more customers or have not yet joined any of the customers
SELECT s.name, c.cust_name
FROM salesman s
LEFT JOIN customer c ON s.salesman_id = c.salesman_id
ORDER BY s.name;

-- 13. write a SQL query to list all salespersons along with customer name, city, grade, order number, date, and amount.
SELECT c.cust_name, c.city, c.grade, o.ord_no, o.ord_date, o.pur_amt, s.name
FROM customer c
LEFT JOIN salesman s ON s.salesman_id =c.salesman_id
LEFT JOIN orders o ON o.customer_id = c.customer_id;


-- 14. Write a SQL statement to make a list for the salesmen who either work for one or more customers or yet to join any of the customers. The customer may have placed, either one or more orders on or above order amount 2000 and must have a grade, or he may not have placed any order to the associated supplier.
SELECT s.name as "Sales Person", c.cust_name, c.city, c.grade, o.ord_no, o.ord_date, o.pur_amt 
FROM customer c
LEFT JOIN salesman s ON s.salesman_id = c.salesman_id
LEFT JOIN orders o ON o.customer_id =c.customer_id
where pur_amt>=2000; 

-- 15. Write a SQL statement to generate a list of all the salesmen who either work for one or more customers or have yet to join any of them. The customer may have placed one or more orders at or above order amount 2000, and must have a grade, or he may not have placed any orders to the associated supplier.
SELECT s.name as "Sales Person", c.cust_name, c.city, c.grade, o.ord_no, o.ord_date, o.pur_amt 
FROM customer c
LEFT JOIN salesman s ON s.salesman_id = c.salesman_id
LEFT JOIN orders o ON o.customer_id =c.customer_id
where pur_amt>=2000; 


-- 16. Write a SQL statement to generate a report with the customer name, city, order no. order date, purchase amount for only those customers on the list who must have a grade and placed one or more orders or which order(s) have been placed by the customer who neither is on the list nor has a grade.
SELECT s.name as "Sales Person", cust_name, c.city, grade, ord_no, ord_date, pur_amt 
FROM customer c
LEFT JOIN salesman s ON s.salesman_id = c.salesman_id
LEFT JOIN orders o ON o.customer_id = c.customer_id
where grade is not null;


-- 17. Write a SQL query to combine each row of the salesman table with each row of the customer table
SELECT * FROM salesman 
cross JOIN customer;

-- 18. Write a SQL statement to create a Cartesian product between salesperson and customer, i.e. each salesperson will appear for all customers and vice versa for that salesperson who belongs to that city
SELECT s.name as "Sales Person" ,s.city as "SalesPerson City", c.cust_name as "Customer name", c.city as "Customer city" 
FROM salesman s 
cross JOIN customer  c;

-- 19. Write a SQL statement to create a Cartesian product between salesperson and customer, i.e. each salesperson will appear for every customer and vice versa for those salesmen who belong to a city and customers who require a grade
SELECT s.name as "Sales Person" ,s.city as "SalesPerson City", c.cust_name as "Customer name", c.city
as "Customer  city" 
FROM salesman s
cross JOIN customer  c
where s.city is not null AND grade is not null;


-- 20. Write a SQL statement to make a Cartesian product between salesman and customer i.e. each salesman will appear for all customers and vice versa for those salesmen who must belong to a city which is not the same as his customer and the customers should have their own grade
SELECT s.name as "Sales Person" ,s.city as "SalesPerson City", c.cust_name as "Customer name", c.city
as "Customer  city" FROM salesman s
cross JOIN customer  c
where s.city != c.city;