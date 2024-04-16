CREATE TABLE scholarships ( id int primary key, scholarship int )

INSERT INTO scholarships VALUES (1,12000);
INSERT INTO scholarships VALUES (2,18000);
INSERT INTO scholarships VALUES (3,24000);
INSERT INTO scholarships VALUES (4,15000);
INSERT INTO scholarships VALUES (5,21000);
INSERT INTO scholarships VALUES (6,13000);

select id ,scholarship/12 as scholarship from scholarships

CREATE PROCEDURE getScholarship() BEGIN SELECT id ,scholarship/12 AS scholarship FROM scholarships; END

call getScholarship()

