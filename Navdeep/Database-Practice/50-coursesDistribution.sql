CREATE TABLE courses (
  id INT,
  name VARCHAR(255),
  description TEXT
);

INSERT INTO courses (id, name, description)
VALUES
  (1, 'Linear algebra', 'Basis of matrix theory and linear algebra'),
  (2, 'Geometry', NULL),
  (3, 'Determinants and matrices', NULL),
  (4, 'Matlab-toremove', 'Matlab 7');

  CREATE TABLE groupcourses (
  group_id INT,
  course_id INT
);

INSERT INTO groupcourses (group_id, course_id) 
VALUES 
(1, 1),
(1, 2),
(1, 3),
(2, 1),
(2, 4);


CREATE TABLE groupexams (
    date DATE ,
    group_id INT ,
    course_id INT 
);

INSERT INTO groupexams (date, group_id, course_id)
VALUES
    ('2010-01-10', 1, 1),
    ('2010-01-11', 1, 2),
    ('2010-01-12', 2, 4);


    ALTER TABLE groupcourses ADD FOREIGN KEY (course_id)
    REFERENCES courses(id) ON DELETE CASCADE;

    ALTER TABLE groupexams ADD FOREIGN KEY (course_id)
    REFERENCES courses(id) ON DELETE CASCADE;

    DELETE FROM courses WHERE name LIKE '%-toremove';


    SELECT group_id, course_id
      FROM groupcourses
     UNION
    SELECT group_id, course_id
      FROM groupexams
     ORDER BY group_id, course_id;