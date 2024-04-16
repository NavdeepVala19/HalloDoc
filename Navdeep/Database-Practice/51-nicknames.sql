CREATE TABLE reserved_nicknames (
    id VARCHAR(20),
    nickname VARCHAR(50)
);

INSERT INTO reserved_nicknames (id, nickname)
VALUES
    ('id001', 'alex1990'),
    ('id142', 'killer007'),
    ('id15674', 'prohunter'),
    ('id4242', 'accOrdin'),
    ('id616', 'Zoneg'),
    ('id9999', 'butch');

    UPDATE reservedNicknames
    SET nickname = CONCAT('rename - ',nickname), id = CONCAT('rename - ', id) 
    WHERE LENGTH(nickname) <> 8;

    SELECT * FROM reservedNicknames ORDER BY id;