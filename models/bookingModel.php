<?php

require_once __DIR__ . "/../config/database.php";

function getAllBookings($filters = [])
{
    global $conn;

    $sql = "SELECT
                bookings.id,
                bookings.checkin_date,
                bookings.checkout_date,
                bookings.num_guests,
                bookings.total_price,
                bookings.status,
                bookings.source,
                bookings.created_at,
                users.name AS guest_name,
                users.email AS guest_email,
                room_types.name AS room_type_name,
                rooms.room_number,
                billing.payment_status
            FROM bookings
            INNER JOIN users ON bookings.guest_id = users.id
            INNER JOIN room_types ON bookings.room_type_id = room_types.id
            LEFT JOIN rooms ON bookings.room_id = rooms.id
            LEFT JOIN billing ON bookings.id = billing.booking_id
            WHERE 1 = 1";

    $params = [];
    $types = "";

    if (!empty($filters["search"])) {
        $sql .= " AND (
                    users.name LIKE ?
                    OR users.email LIKE ?
                    OR rooms.room_number LIKE ?
                    OR bookings.id LIKE ?
                 )";

        $search = "%" . $filters["search"] . "%";

        $params[] = $search;
        $params[] = $search;
        $params[] = $search;
        $params[] = $search;

        $types .= "ssss";
    }

    if (!empty($filters["status"])) {
        $sql .= " AND bookings.status = ?";
        $params[] = $filters["status"];
        $types .= "s";
    }

    if (!empty($filters["room_type_id"])) {
        $sql .= " AND bookings.room_type_id = ?";
        $params[] = $filters["room_type_id"];
        $types .= "i";
    }

    if (!empty($filters["source"])) {
        $sql .= " AND bookings.source = ?";
        $params[] = $filters["source"];
        $types .= "s";
    }

    if (!empty($filters["from_date"])) {
        $sql .= " AND bookings.checkin_date >= ?";
        $params[] = $filters["from_date"];
        $types .= "s";
    }

    if (!empty($filters["to_date"])) {
        $sql .= " AND bookings.checkout_date <= ?";
        $params[] = $filters["to_date"];
        $types .= "s";
    }

    $sql .= " ORDER BY bookings.id DESC";

    $stmt = $conn->prepare($sql);

    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();

    return $stmt->get_result();
}

function getBookingStatistics()
{
    global $conn;

    $stats = [
        "total" => 0,
        "confirmed" => 0,
        "pending" => 0,
        "checked_in" => 0,
        "completed" => 0,
        "cancelled" => 0
    ];

    $sql = "SELECT status, COUNT(*) AS total
            FROM bookings
            GROUP BY status";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        if ($row["status"] == "confirmed") {
            $stats["confirmed"] = $row["total"];
        }

        if ($row["status"] == "pending") {
            $stats["pending"] = $row["total"];
        }

        if ($row["status"] == "checked_in") {
            $stats["checked_in"] = $row["total"];
        }

        if ($row["status"] == "checked_out") {
            $stats["completed"] = $row["total"];
        }

        if ($row["status"] == "cancelled") {
            $stats["cancelled"] = $row["total"];
        }

        $stats["total"] += $row["total"];
    }

    return $stats;
}

function getRoomTypesForBookingFilter()
{
    global $conn;

    $sql = "SELECT id, name FROM room_types ORDER BY name ASC";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    return $stmt->get_result();
}

?>