
CREATE TABLE Successors (
    name VARCHAR(100),
    birthday DATE PRIMARY KEY,
    gender CHAR(1) CHECK (gender IN ('M', 'F'))
);

INSERT INTO Successors (name, birthday, gender) VALUES
('Amelia', '1711-06-10', 'F'),
('Anne', '1709-11-02', 'F'),
('Caroline', '1713-06-10', 'F'),
('Frederick', '1707-02-01', 'M'),
('Loisa', '1724-12-18', 'F'),
('Mary', '1723-03-05', 'F'),
('William', '1721-04-26', 'M');


CREATE PROCEDURE throneSuccession()
BEGIN
    DECLARE current_gender CHAR(1);
    DECLARE current_title VARCHAR(20);
    DECLARE current_name VARCHAR(50);
    
    -- Cursor to iterate through the Successors table
    DECLARE successors_cursor CURSOR FOR
    SELECT name, birthday, gender
    FROM Successors
    ORDER BY birthday ASC;
    
    -- Temporary table to store the order of succession
    CREATE TEMPORARY TABLE OrderOfSuccession (
        order_rank INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(20),
        heir_name VARCHAR(50)
    );
    
    -- Open the cursor
    OPEN successors_cursor;
    
    -- Initialize variables
    SET current_gender = '';
    SET current_title = '';
    
    successor_loop: LOOP
        -- Fetch the next row from the cursor
        FETCH successors_cursor INTO current_name, _, _;
        
        -- Exit the loop if no more rows
        IF (current_name IS NULL) THEN
            LEAVE successor_loop;
        END IF;

        -- Determine the title based on gender
        IF (current_gender <> '' AND current_gender <> gender) THEN
            SET current_title = CASE
                WHEN gender = 'M' THEN 'Queen'
                WHEN gender = 'F' THEN 'King'
                ELSE ''
            END;
        END IF;

        -- Update the current gender
        SET current_gender = gender;

        -- Insert the heir into the temporary table
        INSERT INTO OrderOfSuccession (title, heir_name) VALUES (current_title, current_name);
    END LOOP;

    -- Close the cursor
    CLOSE successors_cursor;
    
    -- Select the order of succession from the temporary table
    SELECT CONCAT(title, ' ', heir_name) AS name
    FROM OrderOfSuccession
    ORDER BY order_rank;
    
    -- Drop the temporary table
    DROP TEMPORARY TABLE IF EXISTS OrderOfSuccession;
END
