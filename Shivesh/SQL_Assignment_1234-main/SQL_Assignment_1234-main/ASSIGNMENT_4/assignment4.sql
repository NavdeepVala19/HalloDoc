-- Assignment 4 : Create Stored procedure in Northwind database to insert or update a record in a table

-- 1. Create a stored procedure in the Northwind database that will calculate the average value of Freight for a specified customer.Then, a business rule will be added that will be triggered before every Update and Insert command in the Orders controller,and will use the stored procedure to verify that the Freight does not exceed the average freight. If it does, a message will be displayed and the command will be cancelled.
CREATE PROCEDURE GetAverageFreight
    @CustomerID varchar(10)
AS
BEGIN
    SELECT AVG(Freight) AS AverageFreight
    FROM Orders
    WHERE CustomerID = @CustomerID
END

--Trigger
CREATE TRIGGER Triger1
ON Orders
AFTER INSERT,UPDATE
AS
BEGIN
  DECLARE @EmployeeID INT ,@Freight MONEY,@AvgF MONEY

  SELECT @EmployeeID=EmployeeID , @Freight=Freight FROM inserted

  SELECT @AvgF= AVG(Freight)FROM Orders 
  IF @Freight > @AvgF
  BEGIN
    RAISERROR('freight can not be  exceeded from average freight',16,1);
	ROLLBACK TRANSACTION;
  END 

END


--to check Trigger

INSERT INTO Orders(EmployeeID,Freight) VALUES (6, 80.8);
update Orders
set Freight=90 where OrderID=10248

select top 1 * from Orders ;


-- 2. write a SQL query to Create Stored procedure in the Northwind database to retrieve Employee Sales by Country
CREATE PROCEDURE SalesByCountry @Country varchar(20) AS BEGIN SELECT e.FirstName,e.LastName,c.Country, SUM( o.Quantity * o.UnitPrice) AS "Total Sales" FROM Employees e -- JOIN Orders ON Orders.EmployeeID=e.EmployeeID JOIN [Order Details] o ON Orders.OrderID=o.OrderID JOIN Customers c ON c.CustomerID=Orders.CustomerID where c.Country=@Country GROUP BY e.FirstName,e.LastName,c.Country Order BY [Total Sales] DESC END
EXEC SalesByCountry 'Germany';


-- 3. write a SQL query to Create Stored procedure in the Northwind database to retrieve Sales by Year
CREATE PROCEDURE SalesByYear
@StartYear INT,
@EndYear INT
AS
BEGIN
    SELECT year(OrderDate) AS "YEAR", sum(Total) AS "TotalSale"
    FROM (
        SELECT OrderDate, o.OrderID, o.UnitPrice * o.Quantity AS Total
        FROM Orders
        INNER JOIN [Order Details] o ON Orders.OrderID = o.OrderID
    ) 
    WHERE year(OrderDate) BETWEEN @StartYear AND @EndYear
    GROUP BY YEAR(OrderDate)
    ORDER BY YEAR(OrderDate);
END;

EXEC SalesByYear 1995,1999;

-- 4. write a SQL query to Create Stored procedure in the Northwind database to retrieve Sales By Category
CREATE PROCEDURE GetSalesByCategory  
AS
BEGIN
    SELECT
        Categories.CategoryName,
        SUM(o.Quantity * o.UnitPrice) AS TotalSales
    FROM
        Categories
   
    INNER JOIN
       [Order Details] o ON Products.ProductID = o.ProductID
   
    GROUP BY
        Categories.CategoryName
    ORDER BY
        TotalSales DESC;
END;



-- 5. write a SQL query to Create Stored procedure in the Northwind database to retrieve Ten Most Expensive Products
CREATE PROCEDURE TenMostExpensiveProducts
AS
BEGIN
SELECT TOP 10 ProductName, UnitPrice from dbo.Products
ORDER BY UnitPrice DESC
END

-- 6. write a SQL query to Create Stored procedure in the Northwind database to insert Customer Order Details 

 ALTER PROCEDURE InsertCustomerOrderDetails
@ProductID int,
@UnitPrice MONEY,
@Quantity int,
@Discount real
AS
BEGIN
DECLARE @OrderID int
 SELECT @OrderID=MAX(OrderID) FROM [Order Details];
 INSERT INTO [Order Details](OrderID,ProductID,UnitPrice,Quantity,Discount)
 VALUES(@OrderID,@ProductID,@UnitPrice,@Quantity,@Discount)
END

InsertCustomerOrderDetails 19,50,10,0.2;

select * from [Order Details]
Order BY OrderID DESC

-- 7. write a SQL query to Create Stored procedure in the Northwind database to update Customer Order Details 

CREATE PROCEDURE UpdateCustomerOrderDetails
    @OrderID INT,
  --  @ProductID INT,
    @Quantity INT,
    @Discount REAL,
    @Price REAL
AS
BEGIN
    UPDATE [Order Details]
    SET 
     --   ProductID = @ProductID,
        Quantity = @Quantity,
        Discount = @Discount,
        UnitPrice = @Price
    WHERE OrderID = @OrderID;
END;

