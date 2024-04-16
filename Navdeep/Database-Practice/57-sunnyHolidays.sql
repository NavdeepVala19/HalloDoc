CREATE TABLE holidays (
  holiday_date DATE
);

INSERT INTO holidays (holiday_date)
VALUES ('2016-12-31'),
       ('2017-01-02'),
       ('2017-01-03'),
       ('2017-01-20');

CREATE TABLE weather (
  sunny_date DATE
);

INSERT INTO weather (sunny_date)
VALUES ('2016-12-31'),
       ('2016-12-25'),
       ('2017-02-01'),
       ('2017-01-02'),
       ('2016-11-27'),
       ('2017-01-01');

       SELECT holiday_date AS ski_date
    FROM holidays
    INNER JOIN weather ON holiday_date = sunny_date;