-- creating Table
CREATE TABLE mischief(
	mischief_date DATE,
    author VARCHAR(50),
    title VARCHAR(255)
);


-- Inserting data to Table
INSERT INTO mischief VALUES ("2016-12-01", "Dewey", "Cook the golden fish in a bucket"),
("2016-12-01", "Dewey", "Paint the walls pink"),
("2016-12-01", "Huey", "Eat all the candies"),
("2016-12-01", "Louie", "Wrap the cat in toilet paper"),
("2016-12-08", "Louie", "Play hockey on linoleum"),
("2017-01-01", "Huey", "Smash a window"),
("2017-02-06", "Dewey", "Create a rink on the porch");

-- create new table
SELECT WEEKDAY(mischief_date) AS weekday, mischief_date, author, title 
FROM mischief 
ORDER BY weekday, FIELD(author, "Huey", "Dewey", "Louie"), mischief_date, title;