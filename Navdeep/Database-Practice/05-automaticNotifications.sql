-- creating Table
CREATE TABLE users(
	id INT,
    username VARCHAR(255),
    role VARCHAR(255),
    email VARCHAR(255)
);


-- Inserting data to Table
INSERT INTO users VALUES (6, "fasalytch", "premium", "much.premium@role.com"),
(13, "luckygirl", "regular", "fun@meh.com"),
(16, "todayhumor", "guru", "today@humor.com"),
(23, "Felix", "admin", "felix@codesignal.com"),
(52, "admin666", "AdmiN", "iamtheadmin@admin.admin"),
(87, "solver100500", "regular", "email@notbot.com");

-- SELECT email FROM users WHERE (role <> "admin" OR "premium");
SELECT email FROM users WHERE role IN ("regular", "guru") ORDER BY email ASC;
