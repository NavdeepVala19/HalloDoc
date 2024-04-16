CREATE TABLE students (
  id INT,
  name VARCHAR(255),
  club_id INT
);

INSERT INTO students (id, name, club_id)
VALUES
  (3, 'Richard Gear', 23),
  (6, 'Jennifer Offencerence', 11),
  (14, 'Obiten Kenobi', 87),
  (36, 'Closece', 87);


  CREATE TABLE clubs (
  id INT,
  name VARCHAR(255)
);

INSERT INTO clubs (id, name)
VALUES
  (23, 'Theater club'),
  (64, 'Soccer club'),
  (87, 'Glee club');

  SELECT * FROM students
    WHERE EXISTS (
        SELECT * FROM clubs WHERE clubs.id = students.club_id
    )
    ORDER BY students.id;