CREATE TABLE emails (
    id INT PRIMARY KEY,
    email_title VARCHAR(255),
    size INT
);


INSERT INTO emails (id, email_title, size) VALUES
(1, 'You won 1M dollars!', 21432432),
(2, 'You are fired', 312342),
(3, 'Black Friday is coming', 323),
(4, 'Spam email', 23532),
(5, 'Your requested backup', 234234324);


-- Doubt
SELECT id, email_title,
CASE
  WHEN size >= 1024 * 1024 THEN CONCAT(FLOOR(size / (1024 * 1024)), ' Mb')
  WHEN size >= 1024 THEN CONCAT(FLOOR(size / 1024), ' Kb')
  ELSE CONCAT(size, ' B')
END AS short_size
FROM emails
ORDER BY size DESC;