CREATE TABLE half_year(
    id int UNIQUE,
    newspaper varchar(20),
    subscriber varchar(20))


INSERT INTO half_year VALUES(1,"The Daily Reporter","Lavelle Phu");
INSERT INTO half_year VALUES(2,"Daily Breakfast","Tonie Moreton");
INSERT INTO half_year VALUES(3,"The Paragon Herald","Lia Cover");
INSERT INTO half_year VALUES(4,"The Community Gazette","Lavelle Phu");
INSERT INTO half_year VALUES(5,"Nova Daily","Lia Cover");
INSERT INTO half_year VALUES(6,"Nova Daily","Joya Buss");



SELECT DISTINCT subscriber FROM `full_year` WHERE newspaper LIKE '%Daily' UNION SELECT DISTINCT subscriber FROM `half_year` WHERE newspaper LIKE '%Daily'
