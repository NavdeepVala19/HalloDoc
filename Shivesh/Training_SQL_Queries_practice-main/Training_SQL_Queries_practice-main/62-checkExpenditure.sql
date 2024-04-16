CREATE TABLE expenditure_plan (
  monday_date DATE,
  expenditure_sum INT,
);

CREATE TABLE allowable_expenditure (
  id INT
  left_bound INT,
  right_bound INT,
  value INT
);

INSERT INTO expenditure_plan (monday_date, expenditure_sum)
VALUES ('2016-02-08',  1),
       ('2016-02-15',  2),
       ('2016-06-13',  3),
       ('2016-06-27',  4);

INSERT INTO allowable_expenditure (id, left_bound, right_bound, value)
VALUES (1, 5, 8, 30),
       (2, 23, 26, 10);