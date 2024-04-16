-- creating Table
CREATE TABLE answers(
	id INT,
    correct_answer CHAR(10),
    given_answer CHAR(10) NULL
);


-- Inserting data to Table
INSERT INTO answers VALUES (1, 'a', 'a'),
(2, 'b', NULL),
(3, 'c', 'b');

SELECT id,IF (given_answer is NULL, "no answer", IF(given_answer = correct_answer, "correct", "incorrect"))  
AS checks 
FROM answers
ORDER BY id;