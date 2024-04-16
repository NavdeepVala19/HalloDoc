CREATE TABLE products(
    id int unique,
    name varchar(20) unique,
    price int,
    quantity int
    )


  
INSERT INTO products VALUES(1,"MacBook Air",1500,1);
INSERT INTO products VALUES(2,"Magic Mouse",79,1);
INSERT INTO products VALUES(3,"Spray cleaner",10,3);



-- SELECT name,(price*quantity) AS spent FROM products

SELECT name FROM products ORDER BY (price*quantity) DESC , name LIMIT 1



INSERT INTO products VALUES(1,"Tomato",10,4);
INSERT INTO products VALUES(2,"Cucumber",8,5);
INSERT INTO products VALUES(3,"Red Pepper",20,2);
INSERT INTO products VALUES(4,"Feta",40,1);

