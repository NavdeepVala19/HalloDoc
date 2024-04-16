CREATE TABLE availableItems(
    id int UNIQUE,
    item_name varchar(20),
    item_type varchar(20)
    )


INSERT INTO availableitems VALUES(1,"SafeDisk 4GB","USB drive");
INSERT INTO availableitems VALUES(2,"SafeDisk 8GB","USB drive");
INSERT INTO availableitems VALUES(3,"Cinco 50-Pack","DVD");
INSERT INTO availableitems VALUES(4,"SafeDisk 4GB","Memory card");
INSERT INTO availableitems VALUES(5,"SafeDisk 8GB","Memory card");
INSERT INTO availableitems VALUES(6,"Cinco 30-Pack","DVD");
INSERT INTO availableitems VALUES(7,"SafeDisk 4GB","Memory card");
INSERT INTO availableitems VALUES(8,"SafeDisk 4GB","Memory card");
INSERT INTO availableitems VALUES(9,"DiskHolder","Misc");
INSERT INTO availableitems VALUES(10,"Cinco 50-Pack","DVD");
INSERT INTO availableitems VALUES(11,"SafeDisk 4GB","USB drive");
INSERT INTO availableitems VALUES(12,"DiskCleaner Pro","Misc");

SELECT item_name, item_type, COUNT(*) AS "item_count" FROM availableItems
GROUP BY item_name, item_type
ORDER BY item_type ASC, item_name ASC;