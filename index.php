DB::statement('CALL registerUserFull(?, ?, ?)', [
    $request->name,
    $request->email,
    bcrypt($request->password)
]);


DELIMITER $$

CREATE PROCEDURE registerUserFull(
    IN p_name VARCHAR(100),
    IN p_email VARCHAR(100),
    IN p_password VARCHAR(255)
)
BEGIN
    DECLARE v_user_id INT;

    START TRANSACTION;

    -- Insert into users table
    INSERT INTO users(name, email, password)
    VALUES (p_name, p_email, p_password);

    SET v_user_id = LAST_INSERT_ID();

    -- Insert default role
    INSERT INTO user_roles(user_id, role)
    VALUES (v_user_id, 'USER');

    -- Insert user profile
    INSERT INTO user_profiles(user_id, created_at)
    VALUES (v_user_id, NOW());

    COMMIT;
END $$

DELIMITER ;
