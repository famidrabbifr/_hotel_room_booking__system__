<?php

require_once __DIR__ . "/../config/database.php";

function getAllAnnouncements()
{
    global $conn;

    $sql = "SELECT announcements.*, users.name AS created_by_name
            FROM announcements
            INNER JOIN users ON announcements.created_by = users.id
            ORDER BY announcements.id DESC";

    return $conn->query($sql);
}

function getAnnouncementById($id)
{
    global $conn;

    $sql = "SELECT * FROM announcements WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}

function addAnnouncement($title, $message, $createdBy, $status)
{
    global $conn;

    $sql = "INSERT INTO announcements
            (title, message, created_by, is_active)
            VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $title, $message, $createdBy, $status);

    try {
        return $stmt->execute();
    } catch (mysqli_sql_exception $e) {
        return false;
    }
}

function updateAnnouncement($id, $title, $message, $status)
{
    global $conn;

    $sql = "UPDATE announcements
            SET title = ?, message = ?, is_active = ?
            WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $title, $message, $status, $id);

    try {
        return $stmt->execute();
    } catch (mysqli_sql_exception $e) {
        return false;
    }
}

function deleteAnnouncement($id)
{
    global $conn;

    $sql = "DELETE FROM announcements WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    try {
        return $stmt->execute();
    } catch (mysqli_sql_exception $e) {
        return false;
    }
}

?>