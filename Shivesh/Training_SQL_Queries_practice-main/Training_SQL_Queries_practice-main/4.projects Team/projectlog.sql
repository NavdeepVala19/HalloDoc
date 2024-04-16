

CREATE TABLE projectlog(
  id int unique,
  name varchar(20),
  description varchar(20),
  timestamps timestamp
)


x
INSERT INTO projectlog VALUES(1,"James smith","add new logo","2015-11-10 15:24:32");
INSERT INTO projectlog VALUES(2,"John Johnson","update license","2015-11-10 15:50:01");
INSERT INTO projectlog VALUES(3,"John Johnson","fix typos","2015-11-10 15:55:01");
INSERT INTO projectlog VALUES(4,"James smith","update logo","2015-11-10 17:53:04");
INSERT INTO projectlog VALUES(5,"James smith","delete old logo","2015-11-10 17:54:04");
INSERT INTO projectlog VALUES(6,"Michael Williams","fix the build","2015-11-12 13:37:002");
INSERT INTO projectlog VALUES(7,"Mary Troppins","add new logo","2015-11-08 17:54:04");
INSERT INTO projectlog VALUES(8,"James smith","fix fonts","	2015-11-14 13:54:042");
INSERT INTO projectlog VALUES(9,"Richard Young","remove unneeded files","2015-11-14 00:00:00");
INSERT INTO projectlog VALUES(10,"Michael Williams","add tests","2015-11-09 11:53:00");


SELECT DISTINCT name from projectlog