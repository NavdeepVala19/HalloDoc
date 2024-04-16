CREATE TABLE Projects( internal_id int, project_name varchar(225), team_size int, team_lead varchar (225), income INT)


INSERT INTO projects VALUES(1384,"Map Reduce",100,"Jeffrey Dean",0);
INSERT INTO projects VALUES(2855,"Windows",1000,"Bill Gates",100500);
INSERT INTO projects VALUES(5961,"Snapchat",3,"Evan Speigel",2000);


SELECT project_name,team_lead,income FROM projects ORDER BY internal_id




CREATE TABLE new_projectlogs(project_name varchar(225), team_lead varchar (225), income INT)

INSERT INTO new_projectlogs VALUES("Map Reduce","Jeffrey Dean",0);
INSERT INTO new_projectlogs VALUES("Windows","Bill Gates",100500);
INSERT INTO new_projectlogs VALUES("Snapchat","Evan Speigel",2000);


DELIMITER //
CREATE PROCEDURE project_new()
RETURNS TABLE (name VARCHAR(255), team_lead VARCHAR(255), income INT)
BEGIN
  RETURN SELECT name, team_lead, income FROM new_projectlogs ORDER BY internal_id;
END;
//
DELIMITER ;