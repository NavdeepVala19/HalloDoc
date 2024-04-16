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


