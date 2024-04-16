CREATE TABLE events (
  id INT,
  `name` VARCHAR(25),
  event_date DATE
);

INSERT INTO events 
VALUES
  (1, 'TGIF', '2016-11-18'),
  (2, 'TGIF', '2016-11-11'),
  (3, 'Weekly team meeting', '2016-11-07'),
  (4, 'Weekly team meeting', '2016-11-14');


--Doubt
  SELECT name, event_date
FROM Events
WHERE event_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
AND event_date < CURDATE()
ORDER BY event_date DESC;
