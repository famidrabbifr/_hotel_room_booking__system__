<?php

require_once __DIR__ . "/../config/database.php";

function getAllUsers()
{
    global $conn;

    $sql = "SELECT * FROM users ORDER BY id DESC";
    return $conn->query($sql);
}

function getUserById($id)
{
    global $conn;

    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}

function addUser($name, $email, $password, $phone, $role, $status)
{
    global $conn;

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users
            (name, email, password_hash, phone, role, is_active)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sssssi",
        $name,
        $email,
        $hashedPassword,
        $phone,
        $role,
        $status
    );

    try {
        return $stmt->execute();
    } catch (mysqli_sql_exception $e) {
        return false;
    }
}

function updateUser($id, $name, $email, $phone, $role, $status)
{
    global $conn;

    $sql = "UPDATE users
            SET name = ?, email = ?, phone = ?, role = ?, is_active = ?
            WHERE id = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "ssssii",
        $name,
        $email,
        $phone,
        $role,
        $status,
        $id
    );

    try {
        return $stmt->execute();
    } catch (mysqli_sql_exception $e) {
        return false;
    }
}

function deleteUser($id)
{
    global $conn;

    $sql = "DELETE FROM users WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    try {
        return $stmt->execute();
    } catch (mysqli_sql_exception $e) {
        return false;
    }
}

?>