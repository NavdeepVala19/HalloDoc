CREATE TABLE people_hobbies (
    name VARCHAR(100) PRIMARY KEY,
    hobbies SET('reading','sports','swimming','drawing','writing','acting','cooking','dancing','fishkeeping','juggling','sculpting','videogaming')
);


INSERT INTO people_hobbies (name, hobbies) VALUES
('Adam', 'swimming'),
('Amy', 'reading,sports,swimming'),
('Carl', 'filmwatching,writing'),
('Carol', 'reading,swimming'),
('Deril', 'sports'),
('Jake', 'reading,sports,swimming'),
('Lola', 'reading,sports,painting'),
('Nina', 'sports,painting'),
('Sam', 'sports');


CREATE PROCEDURE interestClub()
    SELECT name
    FROM people_interests
    WHERE interests LIKE '%reading%' AND interests LIKE '%drawing%'
    ORDER BY name;
