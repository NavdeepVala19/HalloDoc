CREATE TABLE scores (
    match_id INT PRIMARY KEY,
    first_team_score INT,
    second_team_score INT,
    match_host INT CHECK (match_host IN (1, 2))
);

INSERT INTO scores (match_id, first_team_score, second_team_score, match_host) VALUES
(1, 3, 2, 1),
(2, 2, 1, 2),
(3, 1, 2, 1),
(4, 2, 1, 2);

