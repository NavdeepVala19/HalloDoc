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

SELECT name FROM people_hobbies
WHERE hobbies LIKE "%reading%" AND hobbies LIKE "%sports%";