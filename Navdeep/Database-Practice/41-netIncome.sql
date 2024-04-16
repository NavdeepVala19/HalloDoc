CREATE TABLE accounting (
  date DATE,
  profit INT,
  loss INT
);

INSERT INTO accounting
VALUES
  ('2006-01-01', 100, 15),
  ('2006-07-15', 40, 100),
  ('2006-11-11', 50, 50),
  ('2006-08-01', 100, 50),
  ('2006-12-01', 50, 80),
  ('2007-05-03', 42, 16);

  SELECT YEAR(date) as year, QUARTER(date) as quarter, SUM(profit)-SUM(loss) as net_profit
    FROM accounting
    GROUP BY YEAR(date), QUARTER(date);