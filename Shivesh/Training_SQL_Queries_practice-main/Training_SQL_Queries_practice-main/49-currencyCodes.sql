CREATE TABLE currencies (
  code CHAR(3) ,
  country VARCHAR(50) 
);

INSERT INTO currencies (code, country) VALUES
('AD', 'Andorra'),
('AUD', 'Australia'),
('BRL', 'Brazil (Real)'),
('MXN', 'Mexico (Peso)'),
('RUB', 'Russia (Ruble)'),
('SEK', 'Sweden (Krona)'),
('USD', 'USA (Dollar)');


    DELETE FROM currencies
    WHERE LENGTH(code) <> 3;

    SELECT * FROM currencies ORDER BY code;