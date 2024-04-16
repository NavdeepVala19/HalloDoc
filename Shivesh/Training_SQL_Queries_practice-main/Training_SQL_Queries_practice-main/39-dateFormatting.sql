CREATE TABLE documents (
    id INT PRIMARY KEY,
    date VARCHAR(10)
);


INSERT INTO documents (id, date) VALUES
(1, '2000-1-1'),
(2, '2014-8-21'),
(3, '2002-03-07'),
(4, '2008-10-5'),
(5, '2016-12-17');


SELECT
    id,
    DATE_FORMAT(STR_TO_DATE(date_str, '%Y-%c-%e'), '%Y-%m-%d') AS date_iso
FROM documents
ORDER BY id;

