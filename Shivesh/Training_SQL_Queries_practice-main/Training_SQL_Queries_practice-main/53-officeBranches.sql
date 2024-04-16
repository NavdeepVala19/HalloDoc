CREATE TABLE branches (
  branch_id INT,
  name VARCHAR(255),
  branchtype_id INT
);

CREATE TABLE branch_types (
  branchtype_id INT ,
  name VARCHAR(255) 
);

INSERT INTO branches (branch_id, id, name, branchtype_id) 
VALUES
(1, 'Frankfurt branch', 2),
(2, 'Paris branch', 3),
(3, 'New York branch', 2),
(4, 'Madrid branch', 1);

INSERT INTO branch_types (branchtype_id, name) 
VALUES
(1, 'Small-outdated'),
(2, 'Big'),
(3, 'Medium');


 ALTER TABLE branches ADD FOREIGN KEY (branchtype_id)
    REFERENCES branch_types(id) ON DELETE SET NULL;

    DELETE FROM branch_types WHERE name LIKE '%-outdated';

    SELECT * FROM branches
    ORDER BY branch_id;