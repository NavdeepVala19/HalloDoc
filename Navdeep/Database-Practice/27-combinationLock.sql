CREATE TABLE discs (
    id INT PRIMARY KEY,
    characters VARCHAR(100) UNIQUE,
    color VARCHAR(50)
);


INSERT INTO discs (id, characters, color) VALUES
(1, 'code', 'blue'),
(2, 'fights', 'white');


SELECT ROUND(EXP(SUM(LOG(LENGTH(characters))))) 
AS combinations
FROM discs;
