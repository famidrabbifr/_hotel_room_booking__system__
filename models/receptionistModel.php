<?php

require_once __DIR__ . "/../config/database.php";

function getReceptionistDashboardStats()
{
    global $conn;

    $stats = [];
    $today = date("Y-m-d");

    $sql = "SELECT COUNT(*) AS total FROM bookings WHERE checkin_date = ? AND status = 'confirmed'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $today);
    $stmt->execute();
    $checkins = $stmt->get_result()->fetch_assoc();

    $sql = "SELECT COUNT(*) AS total FROM bookings WHERE checkout_date = ? AND status = 'checked_in'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $today);
    $stmt->execute();
    $checkouts = $stmt->get_result()->fetch_assoc();

    $sql = "SELECT COUNT(*) AS total FROM rooms WHERE status = 'occupied'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $occupied = $stmt->get_result()->fetch_assoc();

    $sql = "SELECT COUNT(*) AS total FROM rooms WHERE status = 'available'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $available = $stmt->get_result()->fetch_assoc();

    $stats["checkins"] = $checkins["total"];
    $stats["checkouts"] = $checkouts["total"];
    $stats["occupied"] = $occupied["total"];
    $stats["available"] = $available["total"];

    return $stats;
}

function getTodayCheckins()
{
    global $conn;

    $today = date("Y-m-d");

    $sql = "SELECT 
                bookings.id,
                bookings.room_type_id,
                bookings.checkin_date,
                bookings.checkout_date,
                bookings.num_guests,
                bookings.status,
                users.name AS guest_name,
                users.id_number,
                room_types.name AS room_type
            FROM bookings
            INNER JOIN users ON bookings.guest_id = users.id
            INNER JOIN room_types ON bookings.room_type_id = room_types.id
            WHERE bookings.checkin_date = ?
            AND bookings.status = 'confirmed'
            ORDER BY bookings.id DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $today);
    $stmt->execute();

    return $stmt->get_result();
}

function getTodayCheckinsSearch($keyword = "")
{
    global $conn;

    $today = date("Y-m-d");

    $sql = "SELECT 
                bookings.id,
                bookings.room_type_id,
                bookings.checkin_date,
                bookings.checkout_date,
                bookings.num_guests,
                bookings.status,
                users.name AS guest_name,
                users.id_number,
                room_types.name AS room_type
            FROM bookings
            INNER JOIN users ON bookings.guest_id = users.id
            INNER JOIN room_types ON bookings.room_type_id = room_types.id
            WHERE bookings.checkin_date = ?
            AND bookings.status = 'confirmed'";

    $params = [$today];
    $types = "s";

    if ($keyword != "") {
        $sql .= " AND (
                    bookings.id LIKE ?
                    OR users.name LIKE ?
                    OR users.id_number LIKE ?
                    OR room_types.name LIKE ?
                    OR bookings.status LIKE ?
                  )";

        $search = "%" . $keyword . "%";

        $params[] = $search;
        $params[] = $search;
        $params[] = $search;
        $params[] = $search;
        $params[] = $search;

        $types .= "sssss";
    }

    $sql .= " ORDER BY bookings.id DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();

    return $stmt->get_result();
}

function getAvailableRoomsByType($roomTypeId)
{
    global $conn;

    $sql = "SELECT id, room_number, floor 
            FROM rooms 
            WHERE room_type_id = ? 
            AND status = 'available'
            ORDER BY room_number ASC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $roomTypeId);
    $stmt->execute();

    return $stmt->get_result();
}

function getAvailableRoomsArrayByType($roomTypeId)
{
    global $conn;

    $sql = "SELECT id, room_number, floor
            FROM rooms
            WHERE room_type_id = ?
            AND status = 'available'
            ORDER BY room_number ASC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $roomTypeId);
    $stmt->execute();

    $result = $stmt->get_result();
    $rooms = [];

    while ($row = $result->fetch_assoc()) {
        $rooms[] = $row;
    }

    return $rooms;
}

function processCheckIn($bookingId, $roomId)
{
    global $conn;

    $conn->begin_transaction();

    try {
        $bookingSql = "UPDATE bookings 
                       SET room_id = ?, status = 'checked_in'
                       WHERE id = ?";

        $bookingStmt = $conn->prepare($bookingSql);
        $bookingStmt->bind_param("ii", $roomId, $bookingId);
        $bookingStmt->execute();

        $roomSql = "UPDATE rooms 
                    SET status = 'occupied'
                    WHERE id = ?";

        $roomStmt = $conn->prepare($roomSql);
        $roomStmt->bind_param("i", $roomId);
        $roomStmt->execute();

        $conn->commit();
        return true;

    } catch (mysqli_sql_exception $e) {
        $conn->rollback();
        return false;
    }
}

function getTodayCheckouts()
{
    global $conn;

    $today = date("Y-m-d");

    $sql = "SELECT 
                bookings.id,
                bookings.room_id,
                bookings.checkin_date,
                bookings.checkout_date,
                bookings.status,
                users.name AS guest_name,
                rooms.room_number
            FROM bookings
            INNER JOIN users ON bookings.guest_id = users.id
            LEFT JOIN rooms ON bookings.room_id = rooms.id
            WHERE bookings.checkout_date = ?
            ORDER BY bookings.id DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $today);
    $stmt->execute();

    return $stmt->get_result();
}

function getTodayCheckoutsSearch($keyword = "")
{
    global $conn;

    $today = date("Y-m-d");

    $sql = "SELECT 
                bookings.id,
                bookings.room_id,
                bookings.checkin_date,
                bookings.checkout_date,
                bookings.status,
                users.name AS guest_name,
                rooms.room_number
            FROM bookings
            INNER JOIN users ON bookings.guest_id = users.id
            LEFT JOIN rooms ON bookings.room_id = rooms.id
            WHERE bookings.checkout_date = ?
            AND bookings.status IN ('checked_in','checked_out')";

    $params = [$today];
    $types = "s";

    if ($keyword != "") {
        $sql .= " AND (
                    bookings.id LIKE ?
                    OR users.name LIKE ?
                    OR rooms.room_number LIKE ?
                    OR bookings.status LIKE ?
                  )";

        $search = "%" . $keyword . "%";

        $params[] = $search;
        $params[] = $search;
        $params[] = $search;
        $params[] = $search;

        $types .= "ssss";
    }

    $sql .= " ORDER BY bookings.id DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();

    return $stmt->get_result();
}

function getReceptionistRoomStatus()
{
    global $conn;

    $sql = "SELECT 
                rooms.id,
                rooms.room_number,
                rooms.floor,
                rooms.status,
                room_types.name AS room_type
            FROM rooms
            INNER JOIN room_types ON rooms.room_type_id = room_types.id
            ORDER BY rooms.room_number ASC";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    return $stmt->get_result();
}

function processCheckOut($bookingId, $roomId)
{
    global $conn;

    $conn->begin_transaction();

    try {
        $bookingSql = "UPDATE bookings 
                       SET status = 'checked_out'
                       WHERE id = ?";

        $bookingStmt = $conn->prepare($bookingSql);
        $bookingStmt->bind_param("i", $bookingId);
        $bookingStmt->execute();

        $roomSql = "UPDATE rooms 
                    SET status = 'dirty'
                    WHERE id = ?";

        $roomStmt = $conn->prepare($roomSql);
        $roomStmt->bind_param("i", $roomId);
        $roomStmt->execute();

        $conn->commit();
        return true;

    } catch (mysqli_sql_exception $e) {
        $conn->rollback();
        return false;
    }
}

function getPendingPayments()
{
    global $conn;

    $sql = "SELECT 
                billing.id AS bill_id,
                billing.booking_id,
                billing.base_amount,
                billing.extras_amount,
                billing.discount_amount,
                billing.total_amount,
                billing.payment_status,
                users.name AS guest_name,
                bookings.status AS booking_status
            FROM billing
            INNER JOIN users ON billing.guest_id = users.id
            INNER JOIN bookings ON billing.booking_id = bookings.id
            ORDER BY billing.id DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    return $stmt->get_result();
}

function processPayment($billId, $paymentMethod)
{
    global $conn;

    $sql = "UPDATE billing 
            SET payment_status = 'paid',
                payment_method = ?,
                paid_at = NOW()
            WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $paymentMethod, $billId);

    try {
        return $stmt->execute();
    } catch (mysqli_sql_exception $e) {
        return false;
    }
}

function getDailyReportData()
{
    global $conn;

    $today = date("Y-m-d");
    $data = [];

    $sql = "SELECT COUNT(*) AS total FROM bookings WHERE checkin_date = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $today);
    $stmt->execute();
    $checkins = $stmt->get_result()->fetch_assoc();

    $sql = "SELECT COUNT(*) AS total FROM bookings WHERE checkout_date = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $today);
    $stmt->execute();
    $checkouts = $stmt->get_result()->fetch_assoc();

    $sql = "SELECT COUNT(*) AS total FROM rooms WHERE status = 'occupied'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $occupied = $stmt->get_result()->fetch_assoc();

    $sql = "SELECT COUNT(*) AS total FROM rooms WHERE status = 'available'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $available = $stmt->get_result()->fetch_assoc();

    $sql = "SELECT COUNT(*) AS total FROM rooms WHERE status = 'dirty'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $dirty = $stmt->get_result()->fetch_assoc();

    $sql = "SELECT SUM(total_amount) AS total
            FROM billing
            WHERE payment_status = 'paid'
            AND paid_at >= CURDATE()
            AND paid_at < CURDATE() + INTERVAL 1 DAY";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $revenue = $stmt->get_result()->fetch_assoc();

    $data["checkins"] = $checkins["total"];
    $data["checkouts"] = $checkouts["total"];
    $data["occupied"] = $occupied["total"];
    $data["available"] = $available["total"];
    $data["dirty"] = $dirty["total"];
    $data["revenue"] = $revenue["total"] ? $revenue["total"] : 0;

    return $data;
}

function createWalkinBooking($guestName, $phone, $idNumber, $nationality, $roomTypeId, $numGuests, $checkinDate, $checkoutDate, $specialRequest)
{
    global $conn;

    $conn->begin_transaction();

    try {
        $email = strtolower(str_replace(" ", "", $guestName)) . time() . "@walkin.local";
        $password = password_hash("AIUB123", PASSWORD_DEFAULT);
        $role = "guest";
        $active = 1;

        $guestSql = "INSERT INTO users
                    (name, email, password_hash, phone, nationality, id_number, role, is_active)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $guestStmt = $conn->prepare($guestSql);
        $guestStmt->bind_param(
            "sssssssi",
            $guestName,
            $email,
            $password,
            $phone,
            $nationality,
            $idNumber,
            $role,
            $active
        );
        $guestStmt->execute();

        $guestId = $conn->insert_id;

        $priceSql = "SELECT price_per_night FROM room_types WHERE id = ?";
        $priceStmt = $conn->prepare($priceSql);
        $priceStmt->bind_param("i", $roomTypeId);
        $priceStmt->execute();
        $roomType = $priceStmt->get_result()->fetch_assoc();

        if (!$roomType) {
            throw new Exception("Room type not found");
        }

        $days = (strtotime($checkoutDate) - strtotime($checkinDate)) / 86400;

        if ($days < 1) {
            $days = 1;
        }

        $totalPrice = $roomType["price_per_night"] * $days;
        $status = "confirmed";
        $source = "walk_in";

        $bookingSql = "INSERT INTO bookings
                      (guest_id, room_type_id, checkin_date, checkout_date, num_guests, total_price, status, source, special_request)
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $bookingStmt = $conn->prepare($bookingSql);
        $bookingStmt->bind_param(
            "iissidsss",
            $guestId,
            $roomTypeId,
            $checkinDate,
            $checkoutDate,
            $numGuests,
            $totalPrice,
            $status,
            $source,
            $specialRequest
        );
        $bookingStmt->execute();

        $bookingId = $conn->insert_id;

        $paymentStatus = "pending";
        $extras = 0;
        $discount = 0;

        $billingSql = "INSERT INTO billing
                      (booking_id, guest_id, base_amount, extras_amount, discount_amount, total_amount, payment_status)
                      VALUES (?, ?, ?, ?, ?, ?, ?)";

        $billingStmt = $conn->prepare($billingSql);
        $billingStmt->bind_param(
            "iidddds",
            $bookingId,
            $guestId,
            $totalPrice,
            $extras,
            $discount,
            $totalPrice,
            $paymentStatus
        );
        $billingStmt->execute();

        addReceptionLog($_SESSION["user_id"], "Created walk-in booking #" . $bookingId);

        $conn->commit();
        return true;

    } catch (Exception $e) {
        $conn->rollback();
        return false;
    }
}

function addReceptionLog($userId, $description)
{
    global $conn;

    $action = "RECEPTION_ACTIVITY";
    $ip = $_SERVER["REMOTE_ADDR"];

    $sql = "INSERT INTO activity_logs
            (user_id, action, description, ip_address, created_at)
            VALUES (?, ?, ?, ?, NOW())";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "isss",
        $userId,
        $action,
        $description,
        $ip
    );

    try {
        return $stmt->execute();
    } catch (mysqli_sql_exception $e) {
        return false;
    }
}

function completeServiceRequest($serviceId)
{
    global $conn;

    $status = "completed";

    $sql = "UPDATE service_requests
            SET status = ?
            WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $serviceId);

    try {
        return $stmt->execute();
    } catch (mysqli_sql_exception $e) {
        return false;
    }
}

function getReceptionLogs()
{
    global $conn;

    $sql = "SELECT
                activity_logs.id,
                activity_logs.action,
                activity_logs.description,
                activity_logs.ip_address,
                activity_logs.created_at,
                users.name
            FROM activity_logs
            INNER JOIN users ON activity_logs.user_id = users.id
            WHERE activity_logs.action = 'RECEPTION_ACTIVITY'
            ORDER BY activity_logs.id DESC
            LIMIT 20";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    return $stmt->get_result();
}

function getGuestLoyaltyPoints($guestId)
{
    global $conn;

    $sql = "SELECT balance 
            FROM loyalty_points
            WHERE guest_id = ?
            ORDER BY id DESC
            LIMIT 1";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $guestId);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc()["balance"];
    }

    return 0;
}

function deductLoyaltyPoints($guestId, $pointsUsed, $bookingId)
{
    global $conn;

    $currentBalance = getGuestLoyaltyPoints($guestId);
    $newBalance = $currentBalance - $pointsUsed;

    if ($newBalance < 0) {
        $newBalance = 0;
    }

    $sql = "INSERT INTO loyalty_points
            (guest_id, booking_id, points_earned, points_used, balance, created_at)
            VALUES (?, ?, 0, ?, ?, NOW())";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "iiii",
        $guestId,
        $bookingId,
        $pointsUsed,
        $newBalance
    );

    return $stmt->execute();
}

function modifyBookingDates($bookingId, $checkinDate, $checkoutDate)
{
    global $conn;

    $stmt = $conn->prepare("
        UPDATE bookings
        SET checkin_date = ?, checkout_date = ?
        WHERE id = ?
    ");

    $stmt->bind_param(
        "ssi",
        $checkinDate,
        $checkoutDate,
        $bookingId
    );

    return $stmt->execute();
}

function applyLoyaltyDiscount($billId)
{
    global $conn;

    $sql = "SELECT billing.*, loyalty_points.balance
            FROM billing
            LEFT JOIN loyalty_points ON billing.guest_id = loyalty_points.guest_id
            WHERE billing.id = ?
            ORDER BY loyalty_points.id DESC
            LIMIT 1";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $billId);
    $stmt->execute();
    $bill = $stmt->get_result()->fetch_assoc();

    if (!$bill || $bill["payment_status"] == "paid") {
        return false;
    }

    $points = $bill["balance"] ? $bill["balance"] : 0;

    if ($points <= 0) {
        return false;
    }

    $discount = min($points, $bill["total_amount"]);
    $newTotal = $bill["total_amount"] - $discount;

    $updateBill = $conn->prepare("
        UPDATE billing
        SET discount_amount = discount_amount + ?,
            total_amount = ?
        WHERE id = ?
    ");

    $updateBill->bind_param("ddi", $discount, $newTotal, $billId);
    $updateBill->execute();

    $newBalance = $points - $discount;

    $updatePoints = $conn->prepare("
        INSERT INTO loyalty_points
        (guest_id, booking_id, points_earned, points_used, balance, created_at)
        VALUES (?, ?, 0, ?, ?, NOW())
    ");

    $updatePoints->bind_param(
        "iidd",
        $bill["guest_id"],
        $bill["booking_id"],
        $discount,
        $newBalance
    );

    return $updatePoints->execute();
}

function approveEarlyLateRequest($bookingId, $type)
{
    global $conn;

    if ($type == "early_checkin") {
        $note = "Early check-in approved";
    } else {
        $note = "Late checkout approved";
    }

    $sql = "UPDATE bookings
            SET special_request = CONCAT(IFNULL(special_request, ''), ' | ', ?)
            WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $note, $bookingId);

    return $stmt->execute();
}

function generateReceiptPath($billId)
{
    global $conn;

    $receiptPath = "receipts/receipt_" . $billId . ".php";

    $sql = "UPDATE billing
            SET receipt_path = ?
            WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $receiptPath, $billId);

    return $stmt->execute();
}

function getPendingBookingModifications()
{
    global $conn;

    $sql = "SELECT bmr.*,
                   b.id AS booking_id,
                   b.checkin_date,
                   b.checkout_date,
                   b.status AS booking_status,
                   u.name AS guest_name,
                   u.email AS guest_email,
                   rt.name AS room_type_name
            FROM booking_modification_requests bmr
            INNER JOIN bookings b ON bmr.booking_id = b.id
            INNER JOIN users u ON bmr.guest_id = u.id
            INNER JOIN room_types rt ON b.room_type_id = rt.id
            ORDER BY bmr.id DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    return $stmt->get_result();
}

function updateBookingModificationStatus($requestId, $status)
{
    global $conn;

    $sql = "UPDATE booking_modification_requests
            SET status = ?
            WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $requestId);

    return $stmt->execute();
}

function approveBookingModification($requestId)
{
    global $conn;

    $conn->begin_transaction();

    try {
        $sql = "SELECT booking_id, requested_checkin_date, requested_checkout_date
                FROM booking_modification_requests
                WHERE id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $requestId);
        $stmt->execute();

        $request = $stmt->get_result()->fetch_assoc();

        if (!$request) {
            throw new Exception("Request not found");
        }

        $sql = "UPDATE bookings
                SET checkin_date = ?, checkout_date = ?
                WHERE id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "ssi",
            $request["requested_checkin_date"],
            $request["requested_checkout_date"],
            $request["booking_id"]
        );
        $stmt->execute();

        updateBookingModificationStatus($requestId, "approved");

        $conn->commit();

        return true;

    } catch (Exception $e) {
        $conn->rollback();
        return false;
    }
}

function getEarlyLateRequests()
{
    global $conn;

    $sql = "SELECT 
                b.id AS booking_id,
                b.checkin_date,
                b.checkout_date,
                b.status,
                u.name AS guest_name,
                u.email,
                rt.name AS room_type_name,
                r.room_number
            FROM bookings b
            INNER JOIN users u ON b.guest_id = u.id
            INNER JOIN room_types rt ON b.room_type_id = rt.id
            LEFT JOIN rooms r ON b.room_id = r.id
            WHERE b.status IN ('confirmed', 'checked_in')
            ORDER BY b.checkin_date ASC";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    return $stmt->get_result();
}

function updateEarlyLateRequestStatus($bookingId, $status)
{
    global $conn;

    $sql = "UPDATE bookings
            SET early_late_status = ?
            WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $bookingId);

    return $stmt->execute();
}

function getModifiableBookings()
{
    global $conn;

    $sql = "SELECT 
                bookings.id,
                bookings.checkin_date,
                bookings.checkout_date,
                bookings.num_guests,
                bookings.status,
                bookings.source,
                users.name AS guest_name,
                room_types.name AS room_type
            FROM bookings
            INNER JOIN users ON bookings.guest_id = users.id
            INNER JOIN room_types ON bookings.room_type_id = room_types.id
            WHERE bookings.status IN ('confirmed', 'checked_in')
            ORDER BY bookings.id DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    return $stmt->get_result();
}

function getReceptionistReceipt($billId)
{
    global $conn;

    $sql = "SELECT
                billing.*,
                users.name AS guest_name,
                bookings.checkin_date,
                bookings.checkout_date,
                room_types.name AS room_type
            FROM billing
            INNER JOIN users ON billing.guest_id = users.id
            INNER JOIN bookings ON billing.booking_id = bookings.id
            INNER JOIN room_types ON bookings.room_type_id = room_types.id
            WHERE billing.id = ?
            LIMIT 1";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $billId);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}

function getReceptionistServiceRequests()
{
    global $conn;

    $sql = "SELECT
                service_requests.id,
                service_requests.service_type,
                service_requests.description,
                service_requests.status,
                service_requests.requested_at,
                users.name AS guest_name,
                rooms.room_number
            FROM service_requests
            INNER JOIN users ON service_requests.guest_id = users.id
            LEFT JOIN rooms ON service_requests.room_id = rooms.id
            ORDER BY service_requests.id DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    return $stmt->get_result();
}

function searchWalkinRoomTypes($keyword = "")
{
    global $conn;

    $sql = "SELECT id, name, price_per_night
            FROM room_types
            WHERE name LIKE ?
            ORDER BY price_per_night ASC";

    $stmt = $conn->prepare($sql);
    $search = "%" . $keyword . "%";
    $stmt->bind_param("s", $search);
    $stmt->execute();

    return $stmt->get_result();
}

function getWalkinAvailableRooms($roomTypeId, $checkin, $checkout)
{
    global $conn;

    $sql = "SELECT rooms.id,
                   rooms.room_number,
                   rooms.floor
            FROM rooms
            WHERE rooms.room_type_id = ?
            AND rooms.status = 'available'
            AND rooms.id NOT IN (
                SELECT bookings.room_id
                FROM bookings
                WHERE bookings.room_id IS NOT NULL
                AND bookings.status IN ('confirmed', 'checked_in')
                AND bookings.checkin_date < ?
                AND bookings.checkout_date > ?
            )
            ORDER BY rooms.room_number ASC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $roomTypeId, $checkout, $checkin);
    $stmt->execute();

    return $stmt->get_result();
}
