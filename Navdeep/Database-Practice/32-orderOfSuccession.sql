
CREATE TABLE Successors (
    name VARCHAR(100),
    birthday DATE PRIMARY KEY,
    gender CHAR(1) CHECK (gender IN ('M', 'F'))
);

INSERT INTO Successors (name, birthday, gender) VALUES
('Amelia', '1711-06-10', 'F'),
('Anne', '1709-11-02', 'F'),
('Caroline', '1713-06-10', 'F'),
('Frederick', '1707-02-01', 'M'),
('Loisa', '1724-12-18', 'F'),
('Mary', '1723-03-05', 'F'),
('William', '1721-04-26', 'M');

SELECT CONCAT(gender, ' ', name) AS name
FROM Successors
ORDER BY birthday ASC;
