CREATE TABLE pr_department (
    id INT,
    name VARCHAR(255),
    date_joined DATE
);

INSERT INTO pr_department (id, name, date_joined)
VALUES (2, 'Bertha Neiman', "2010-12-15"),
       (3, 'Melissa Chace', '2012-08-14'),
       (4, 'Doyle Wiste', '2015-01-02'),
       (8, 'Libbie Troy', '2016-03-04'),
       (15, 'Noreen Bonner', '2012-07-07'),
       (42, 'Farah Dahlquist', '2006-12-12');

CREATE TABLE it_department (
    id INT,
    name VARCHAR(255),
    date_joined DATE
);

INSERT INTO it_department (id, name, date_joined)
VALUES (1, 'Farah Dahlquist', '2017-01-15' ),
       (2, 'Mohammed Claycomb','2016-06-11'  ),
       (3, 'Allen Reger',   '2015-08-12'),
       (5, 'Jacque Fuhr', '2013-04-01'),
       (4, 'Joya Buss',  '2011-11-11'),
       (6, 'Leonia Maultsby',  '2009-02-02'),
       (7, 'Oliva Mcgarry', '2008-05-05');



CREATE TABLE sales_department (
    id INT,
    name VARCHAR(255),
    date_joined DATE
);

INSERT INTO sales_department (id, name, date_joined)
VALUES (3, 'Jayne Kravitz', "2009-09-09"),
       (4, 'Joya Buss', '2008-08-08'),
       (7, 'Edmond Kind', '2007-07-07');


-- Doubt
       SELECT name as names FROM (
    
(SELECT name, 1 as filter FROM (SELECT name from pr_department ORDER BY date_joined DESC LIMIT 5) pr ORDER BY name)
UNION ALL
(SELECT  name, 2 as filter FROM (SELECT name from it_department ORDER BY date_joined DESC LIMIT 5) it ORDER BY name)
UNION ALL
(SELECT name, 3 as filter FROM (SELECT name from sales_department ORDER BY date_joined DESC LIMIT 5) sales ORDER BY name)

    ) a ORDER BY filter, name;

    -- Another solution with common table expression (CTE)