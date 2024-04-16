CREATE TABLE grades(
    name varchar(20),
    id int UNIQUE,
    Midterm1 int,
    Midterm2 int,
    Final INT
)


INSERT INTO grades VALUES ("David",42334,34,54,124), 
("Anthony",54528,100,10,50), 
("Jonathan",58754,49,58,121), 
("Jonty",11000,25,30,180);


SELECT name, id FROM grades
ORDER BY LEFT(name, 3), ID ASC;