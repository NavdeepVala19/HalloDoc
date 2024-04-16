CREATE TABLE full_year(
    id int UNIQUE,
    newspaper varchar(20),
    subscriber varchar(20))

INSERT INTO full_year VALUES(1,"The Paragon Herald","Crissy Sepe");
INSERT INTO full_year VALUES(2,"The Daily Reporter","Tonie Moreton");
INSERT INTO full_year VALUES(3,"Morningtide Daily","Erwin Chitty");
INSERT INTO full_year VALUES(4,"Daily Breakfast","Tonie Moreton");
INSERT INTO full_year VALUES(5,"Independent Weekly","Lavelle Phu");


SELECT DISTINCT subscriber FROM `full_year` WHERE newspaper LIKE '%Daily' UNION SELECT DISTINCT subscriber FROM `half_year` WHERE newspaper LIKE '%Daily'
