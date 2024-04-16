CREATE TABLE expressions(
    id int UNIQUE,
    a int,
    b int,
    operation varchar(20),
    c int 
    )


INSERT INTO expressions VALUES(1,2,3,"+",5);
INSERT INTO expressions VALUES(2,2,3,"+",6);
INSERT INTO expressions VALUES(3,3,2,"/",1);
INSERT INTO expressions VALUES(4,4,7,"*",28);
INSERT INTO expressions VALUES(5,54,2,"-",27);
INSERT INTO expressions VALUES(6,3,0,"/",0);

