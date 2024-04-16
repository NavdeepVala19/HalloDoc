-- First Table
CREATE TABLE products(
    id int unique,
    name varchar(20) unique,
    price int,
    quantity int
    );


INSERT INTO products VALUES(1,"MacBook Air",1500,1),
(2,"Magic Mouse",79,1),
(3,"Spray cleaner",10,3);


INSERT INTO products VALUES(1,"Tomato",10,4),
(2,"Cucumber",8,5),
(3,"Red Pepper",20,2),
(4,"Feta",40,1);

SELECT name FROM Products 
ORDER BY price*quantity DESC, name 
LIMIT 1;