CREATE TABLE phoneNumbers (
    name VARCHAR(100),
    surname VARCHAR(100) PRIMARY KEY,
    phone_number VARCHAR(15) UNIQUE
);


INSERT INTO phoneNumbers (name, surname, phone_number) VALUES
('Cornelius', 'Walsh', '1-234-567-8910'),
('Frank', 'McKenzie', '1-2345-678-911'),
('John', 'Smith', '(1)111-111-1111'),
('Lester', 'Goodwin', '(1)-111-111-1111');


SELECT name, surname, phone_number
FROM phone_numbers
WHERE phone_number REGEXP
  '^((1\\s*-\\s*)|\\(1\\)\\s*)?([0-9]{3})\\s*-\\s*([0-9]{3})\\s*-\\s*([0-9]{4})$'
  ORDER BY surname;
