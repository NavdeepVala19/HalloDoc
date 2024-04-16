CREATE TABLE people_interests (
    name VARCHAR(100) PRIMARY KEY,
    interests SET('reading','sports','swimming','drawing','writing','acting','cooking','dancing','fishkeeping','juggling','sculpting','videogaming')
);


INSERT INTO people_interests (name, interests) VALUES
('August', 'cooking,juggling'),
('Buddy', 'reading,swimming,drawing,acting,dancing,videogaming'),
('David', 'juggling,sculpting'),
('Dennis', 'swimming,cooking,fishkeeping'),
('James', 'reading,drawing');


