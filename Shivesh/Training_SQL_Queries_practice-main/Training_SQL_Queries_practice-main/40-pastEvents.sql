CREATE TABLE pastevent (
    id INT PRIMARY KEY,
    name VARCHAR(255),
    event_date DATE
);


INSERT INTO pastevent (id, name, event_date) VALUES
(1, 'TGIF', '2016-11-18'),
(2, 'TGIF', '2016-11-11'),
(3, 'Weekly team meeting', '2016-11-07'),
(4, 'Weekly team meeting', '2016-11-14');


SELECT name, event_date
FROM Events
WHERE event_date BETWEEN CURDATE() - INTERVAL 7 DAY AND CURDATE() - INTERVAL 1 DAY
ORDER BY event_date DESC;
