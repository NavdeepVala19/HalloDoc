CREATE TABLE expressions(
    id int UNIQUE,
    a int,
    b int,
    operation varchar(20),
    c int 
    );


INSERT INTO expressions VALUES(1,2,3,"+",5),
(2,2,3,"+",6),
(3,3,2,"/",1),
(4,4,7,"*",28),
(5,54,2,"-",27),
(6,3,0,"/",0);

SELECT * FROM expressions
WHERE (operation =  '+' AND a+b = c)
OR (operation = '-' AND a-b = c)
OR (operation = '*' AND a*b=c)
OR (operation = '/' AND a/b=c)
ORDER BY id;