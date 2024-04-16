
  CREATE TABLE events (
    id INT PRIMARY KEY,
    name VARCHAR(255),
    event_date DATE,
    participants INT
);


INSERT INTO events (id, name, event_date, participants) VALUES
(1, 'Dinner', '2016-11-27', 3),
(2, 'Comic-con', '2016-10-25', 100),
(3, 'Christmas', '2016-12-31', 5000),
(4, 'Meeting', '2016-10-18', 300);




SELECT *
    FROM events
    ORDER BY WEEKDAY(event_date), participants DESC;