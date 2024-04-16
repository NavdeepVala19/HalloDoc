CREATE TABLE results (
    name varchar(20) UNIQUE,
    country varchar(20),
    scored int,
    missed int,
    win int 
    )



INSERT INTO results VALUES("FC Tokyo","	Japan",26,28,1);
INSERT INTO results VALUES("Fujian","China",24,26,0);
INSERT INTO results VALUES("Jesus Maria","Argentina",25,23,3);
INSERT INTO results VALUES("University Blues","Australia",16,25,2);

SELECT * FROM `results` order by win        