CREATE TABLE scores (
  arbiter_id INT,
  first_criterion INT,
  second_criterion INT,
  third_criterion INT
);

INSERT INTO scores (arbiter_id, first_criterion, second_criterion, third_criterion)
VALUES
  (1, 3, 10, 10),
  (2, 2, 3, 4),
  (3, 5, 6, 7),
  (4, 2, 5, 9),
  (5, 2, 2, 2);