
CREATE TABLE soccer_team (
    id INT PRIMARY KEY,
    first_name VARCHAR(50),
    surname VARCHAR(50),
    player_number INT UNIQUE
);

INSERT INTO soccer_team (id, first_name, surname, player_number) VALUES
(1, 'Alexis', 'Sanchez', 7),
(2, 'Petr', 'Cech', 33),
(3, 'Hector', 'Bellerin', 24),
(4, 'Olivier', 'Giroud', 12),
(5, 'Theo', 'Walcott', 14),
(6, 'Santi', 'Cazorla', 19);



SELECT STRING_AGG(CONCAT(first_name, ' ', surname, ' #', player_number), '; ') AS players
FROM soccer_team
ORDER BY player_number;

SELECT GROUP_CONCAT(
        first_name, " ", surname, " #", player_number
        ORDER BY player_number ASC
        SEPARATOR "; "
    ) AS players
    FROM soccer_team;