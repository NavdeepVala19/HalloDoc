-- First Table
CREATE TABLE full_year(
    id int UNIQUE,
    newspaper varchar(20),
    subscriber varchar(20));

INSERT INTO full_year VALUES(1,"The Paragon Herald","Crissy Sepe"),
(2,"The Daily Reporter","Tonie Moreton"),
(3,"Morningtide Daily","Erwin Chitty"),
(4,"Daily Breakfast","Tonie Moreton"),
(5,"Independent Weekly","Lavelle Phu");

-- Second Table
CREATE TABLE half_year(
    id int UNIQUE,
    newspaper varchar(20),
    subscriber varchar(20));


INSERT INTO half_year VALUES(1,"The Daily Reporter","Lavelle Phu"),
(2,"Daily Breakfast","Tonie Moreton"),
(3,"The Paragon Herald","Lia Cover"),
(4,"The Community Gazette","Lavelle Phu"),
(5,"Nova Daily","Lia Cover"),
(6,"Nova Daily","Joya Buss");


SELECT subscriber 
FROM full_year WHERE newspaper LIKE '%daily%'
UNION
SELECT subscriber
FROM half_year WHERE newspaper LIKE '%daily%' 
ORDER BY subscriber;