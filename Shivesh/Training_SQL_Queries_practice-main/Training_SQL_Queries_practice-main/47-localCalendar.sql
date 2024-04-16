CREATE TABLE events (
  event_id INT,
  date DATETIME,
  user_id INT
);

INSERT INTO events (event_id, date, user_id)
VALUES
  (1, '2011-11-11 13:37:00', 4242),
  (2, '2012-12-12 04:04:00', 4987),
  (3, '2016-01-01 11:19:00', 4242),
  (4, '2011-11-11 13:37:00', 4987);


  CREATE TABLE user_settings (
  user_id INT,
  timeshift INT,
  hours INT
);

INSERT INTO user_settings (user_id, timeshift, hours)
VALUES 
(1, 30, 24),
(4242, 0, 24),
(4987, 60, 12);