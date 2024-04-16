-- Assignment 1 :  Simple SQL Query with a single table with where clause

CREATE TABLE Products (
ProductID INT PRIMARY KEY AUTO_INCREMENT,
ProductName  VARCHAR(255),                  
SupplierID INT,
CategoryID INT,
QuantityPerUnit INT,     
UnitPrice INT,
UnitsInStock INT,
UnitsOnOrder INT,
ReorderLevel INT,
Discontinued BOOLEAN
);

-- 1. Write a query to get a Product list (id, name, unit price) where current products cost less than $20.
SELECT (ProductID, ProductName, UnitPrice) 
FROM Products
WHERE UnitPrice < 20;

-- 2. Write a query to get Product list (id, name, unit price) where products cost between $15 and $25
SELECT (ProductID, ProductName, UnitPrice) 
FROM Products
WHERE UnitPrice BETWEEN 15 AND 25;

-- 3. Write a query to get Product list (name, unit price) of above average price. 
SELECT (ProductID, UnitPrice) 
FROM Products
WHERE UnitPrice > (SELECT AVG(UnitPrice) FROM Products);

-- 4. Write a query to get Product list (name, unit price) of ten most expensive products
SELECT (ProductName, UnitPrice) 
FROM Products
ORDER BY UnitPrice DESC
LIMIT 10;


-- 5. Write a query to count current and discontinued products
SELECT Discontinued, COUNT(*) AS ProductCount
FROM Products 
GROUP BY Discontinued;

-- 6. Write a query to get Product list (name, units on order , units in stock) of stock is less than the quantity on order
SELECT (ProductName, UnitsOnOrder, UnitsInStock) 
FROM Products
WHERE UnitsInStock < UnitsOnOrder