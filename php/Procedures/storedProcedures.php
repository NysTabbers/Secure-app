<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

$naamRegister = $_POST["naam"];
$emailRegister = $_POST["email"];
$passwordRegister = password_hash($_POST["password"], PASSWORD_DEFAULT);

require_once("../db/db.php");

function createRegisterProcedure($conn)
{
    try {
        $registerProcedure = "
        CREATE PROCEDURE register(
            IN p_name VARCHAR(100),
            IN p_email VARCHAR(255),
            IN p_password VARCHAR(255),
            OUT p_user_id INT
        )
        BEGIN
            DECLARE existing_id INT;
            SELECT id INTO existing_id FROM users WHERE email = p_email LIMIT 1;
            IF existing_id IS NOT NULL THEN
                SET p_user_id = 0;
            ELSE
                INSERT INTO users (name, email, password) VALUES (p_name, p_email, p_password);
                SET p_user_id = LAST_INSERT_ID();
            END IF;
        END;
        ";
        $conn->multi_query($registerProcedure);
        while ($conn->more_results() && $conn->next_result()) { }
    } catch (Exception $e) {
        die("Dit was een error: " . $e->getMessage());
    } finally {
        if (isset($conn)) $conn->close();
    }
}

function createLoginProcedure($conn)
{
    try {
        $loginProcedure = "
        CREATE PROCEDURE login(IN p_email VARCHAR(255))
        BEGIN
            SELECT id, name, email, password FROM users WHERE email = p_email LIMIT 1;
        END;
        ";
        $conn->multi_query($loginProcedure);
        while ($conn->more_results() && $conn->next_result()) { }
    } catch (Exception $e) {
        die("Dit was een error: " . $e->getMessage());
    } finally {
        if (isset($conn)) $conn->close();
    }
}

function register($conn, $naam, $email, $passwordHash)
{
    try {
        $stmt = $conn->prepare('CALL register(?, ?, ?, @out_user_id)');
        $stmt->bind_param('sss', $naam, $email, $passwordHash);
        $stmt->execute();
        $stmt->close();

        $res = $conn->query('SELECT @out_user_id AS user_id');
        $row = $res->fetch_assoc();
        return isset($row['user_id']) ? (int)$row['user_id'] : 0;
    } catch (Exception $e) {
        die("Dit was een error: " . $e->getMessage());
    } finally {
        if (isset($conn)) $conn->close();
    }
}

function login($conn, $email, $plainPassword)
{
    try {
        $stmt = $conn->prepare('CALL login(?)');
        $stmt->bind_param('s', $email);
        $stmt->execute();

        $result = $stmt->get_result();
        $user = $result ? $result->fetch_assoc() : null;
        $stmt->close();

        if (!$user) return 0;
        return password_verify($plainPassword, $user['password']);
    } catch (Exception $e) {
        die("Dit was een error: " . $e->getMessage());
    } finally {
        if (isset($conn)) $conn->close();
    }
}
