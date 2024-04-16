

CREATE TABLE `products` (
  `ProductID` int(11) NOT NULL,
  `ProductName` varchar(100) NOT NULL,
  `SupplierID` int(11) NOT NULL,
  `CategoryID` int(11) NOT NULL,
  `QuantityPerUnit` int(11) NOT NULL,
  `UnitPrice` int(11) NOT NULL,
  `UnitsInStock` int(11) NOT NULL,
  `UnitsOnOrder` int(11) NOT NULL,
  `ReorderLevel` int(11) NOT NULL,
  `Discontinued` int(11) NOT NULL
) 


INSERT INTO `products` (`ProductID`, `ProductName`, `SupplierID`, `CategoryID`, `QuantityPerUnit`, `UnitPrice`, `UnitsInStock`, `UnitsOnOrder`, `ReorderLevel`, `Discontinued`) VALUES
(1, 'Product 1', 1, 1, 10, 5, 100, 10, 20, 0),
(2, 'Product 2', 2, 1, 12, 16, 75, 15, 25, 0),
(3, 'Product 3', 3, 2, 8, 25, 50, 5, 15, 0),
(4, 'Product 4', 4, 2, 20, 36, 120, 30, 40, 0),
(5, 'Product 5', 5, 3, 6, 50, 200, 20, 30, 0),
(6, 'Product 6', 6, 3, 24, 65, 80, 10, 15, 0),
(7, 'Product 7', 7, 1, 15, 76, 60, 8, 20, 0),
(8, 'Product 8', 8, 2, 18, 90, 150, 25, 35, 0),
(9, 'Product 9', 9, 3, 10, 106, 100, 15, 25, 0),
(10, 'Product 10', 10, 1, 30, 120, 180, 20, 30, 0),
(11, 'Product 11', 1, 2, 12, 135, 70, 8, 15, 0),
(12, 'Product 12', 2, 3, 8, 151, 40, 5, 10, 0),
(13, 'Product 13', 3, 1, 25, 166, 90, 10, 20, 0),
(14, 'Product 14', 4, 2, 20, 180, 130, 30, 40, 0),
(15, 'Product 15', 5, 3, 6, 195, 160, 15, 25, 0),
(16, 'Product 16', 6, 1, 18, 211, 110, 20, 30, 0),
(17, 'Product 17', 7, 2, 15, 226, 50, 8, 15, 0),
(18, 'Product 18', 8, 3, 10, 240, 120, 15, 25, 0),
(19, 'Product 19', 9, 1, 30, 255, 70, 20, 30, 0),
(20, 'Product 20', 10, 2, 12, 271, 90, 10, 20, 0);




-- 1.	Write a query to get a Product list (id, name, unit price) where current products cost less than $20.

SELECT ProductID,ProductName,UnitPrice FROM products WHERE UnitPrice <20;


-- 2.	Write a query to get Product list (id, name, unit price) where products cost between $15 and $25

SELECT ProductID,ProductName,UnitPrice FROM products WHERE UnitPrice BETWEEN 15 AND 25;

-- 3.	Write a query to get Product list (name, unit price) of above average price

SELECT ProductID,ProductName,UnitPrice FROM products WHERE UnitPrice > (SELECT AVG(UnitPrice) FROM products);


-- 4.	Write a query to get Product list (name, unit price) of ten most expensive products

SELECT ProductName,UnitPrice FROM products ORDER BY ProductID DESC LIMIT 10;

-- 5.	Write a query to count current and discontinued products

SELECT SUM(CASE WHEN Discontinued = 0 THEN 1 ELSE 0 END) AS CurrentProducts, SUM(CASE WHEN Discontinued = 1 THEN 1 ELSE 0 END) AS DiscontinuedProducts FROM Products;


-- 6.	Write a query to get Product list (name, units on order , units in stock) of stock is less than the quantity on order

SELECT ProductName,UnitsOnOrder,UnitsInStock FROM products WHERE UnitsInStock <UnitsOnOrder;

