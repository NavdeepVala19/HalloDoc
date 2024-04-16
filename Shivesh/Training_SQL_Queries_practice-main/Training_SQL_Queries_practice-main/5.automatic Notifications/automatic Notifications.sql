CREATE TABLE users(
    id int UNIQUE,
    username varchar(20),
    role varchar(20),
    email varchar(20)
    )


INSERT INTO users VALUES(6,"fasalytch","premium","much.premium@role.com");
INSERT INTO users VALUES(13,"luckygirl","regular","fun@meh.com");
INSERT INTO users VALUES(16,"todayhumor","guru","today@humor.com");
INSERT INTO users VALUES(23,"Felix","admin","felix@codesignal.com");
INSERT INTO users VALUES(52,"admin666","AdmiN","iamtheadmin@admin.admin");
INSERT INTO users VALUES(87,"solver100500","regular","email@notbot.com");

SELECT email FROM `users` WHERE role IN ("regular","guru")
order by email