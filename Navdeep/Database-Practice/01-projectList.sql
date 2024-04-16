
-- creating Table
CREATE TABLE Projects(
	internal_id INT,
    project_name VARCHAR(255),
    team_size INT,
    team_lead VARCHAR(255),
    income INT
);

-- Inserting data to Table
INSERT INTO projects VALUES (1384, "MapReduce", 100, "Jeffrey Dean", 0),
(2855, "Windows", 1000, "Bill Gates", 100500),
(5961, "Snapchat", 3, "Evan Spiegel", 2000);


-- create new table same as old table with selected colums
CREATE TABLE new_table AS SELECT project_name, team_lead, income FROM  projects
ORDER BY internal_id ASC;