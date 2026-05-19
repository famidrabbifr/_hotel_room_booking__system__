<?php

require_once __DIR__ . "/../config/database.php";

function getAllPricing()
{
    global $conn;

    $sql = "SELECT 
                seasonal_pricing.id,
                seasonal_pricing.label,
                seasonal_pricing.start_date,
                seasonal_pricing.end_date,
                seasonal_pricing.price_per_night,
                seasonal_pricing.is_active,
                room_types.name AS room_type
            FROM seasonal_pricing
            INNER JOIN room_types
            ON seasonal_pricing.room_type_id = room_types.id
            ORDER BY seasonal_pricing.id DESC";

    return $conn->query($sql);
}

function getPricingById($id)
{
    global $conn;

    $sql = "SELECT * FROM seasonal_pricing WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}

function addPricing($roomTypeId, $label, $startDate, $endDate, $price, $status)
{
    global $conn;

    $sql = "INSERT INTO seasonal_pricing
            (room_type_id, label, start_date, end_date, price_per_night, is_active)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssdi", $roomTypeId, $label, $startDate, $endDate, $price, $status);

    try {
        return $stmt->execute();
    } catch (mysqli_sql_exception $e) {
        return false;
    }
}

function updatePricing($id, $roomTypeId, $label, $startDate, $endDate, $price, $status)
{
    global $conn;

    $sql = "UPDATE seasonal_pricing
            SET room_type_id = ?, label = ?, start_date = ?, end_date = ?, price_per_night = ?, is_active = ?
            WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssdii", $roomTypeId, $label, $startDate, $endDate, $price, $status, $id);

    try {
        return $stmt->execute();
    } catch (mysqli_sql_exception $e) {
        return false;
    }
}

function deletePricing($id)
{
    global $conn;

    $sql = "DELETE FROM seasonal_pricing WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    try {
        return $stmt->execute();
    } catch (mysqli_sql_exception $e) {
        return false;
    }
}

?>