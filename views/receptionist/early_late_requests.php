<?php

require_once "../../app/helpers/session.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "receptionist") {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../../models/receptionistModel.php";

$requests = getEarlyLateRequests();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Early / Late Requests</title>
    <link rel="stylesheet" href="../../assets/css/receptionist.css">
</head>
<body>

<div class="reception-wrapper">

    <div class="reception-sidebar">

        <div class="reception-logo">
            <h2>GPH</h2>
            <p>Reception Desk</p>
        </div>

        <ul class="reception-menu">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="today_checkins.php">Today Check-ins</a></li>
            <li><a href="today_checkouts.php">Today Check-outs</a></li>
            <li><a href="room_status.php">Room Status</a></li>
            <li><a href="payments.php">Payments</a></li>
            <li><a href="daily_report.php">Daily Report</a></li>
            <li><a href="walkin_booking.php">Walk-in Booking</a></li>
            <li><a href="booking_modifications.php">Booking Changes</a></li>
            <li><a href="early_late_requests.php">Early/Late</a></li>
            <li><a href="service_requests.php">Service Requests</a></li>
            <li><a href="activity_logs.php">Activity Logs</a></li>
        </ul>

        <button class="dark-toggle" onclick="toggleDarkMode()">Dark Mode</button>

    </div>

    <div class="reception-main">

        <div class="reception-top">
            <div>
                <h1>Early Check-in / Late Checkout</h1>
                <p>Review eligible bookings manually without changing database structure.</p>
            </div>

            <a href="../../logout.php" class="reception-logout">Logout</a>
        </div>

        <div class="reception-table">
            <h2>Eligible Bookings</h2>

            <table>
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Guest</th>
                        <th>Email</th>
                        <th>Room Type</th>
                        <th>Room</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Status</th>
                        <th>Decision</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if ($requests && $requests->num_rows > 0) { ?>
                        <?php while ($row = $requests->fetch_assoc()) { ?>
                            <tr>
                                <td>#B<?php echo $row["booking_id"]; ?></td>
                                <td><?php echo htmlspecialchars($row["guest_name"]); ?></td>
                                <td><?php echo htmlspecialchars($row["email"]); ?></td>
                                <td><?php echo htmlspecialchars($row["room_type_name"]); ?></td>
                                <td><?php echo $row["room_number"] ?? "Not Assigned"; ?></td>
                                <td><?php echo $row["checkin_date"]; ?></td>
                                <td><?php echo $row["checkout_date"]; ?></td>
                                <td>
                                    <span class="reception-badge badge-<?php echo $row["status"]; ?>">
                                        <?php echo ucfirst(str_replace("_", " ", $row["status"])); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="reception-badge badge-pending">
                                        Manual Review
                                    </span>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="9">No eligible bookings found.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

    </div>

</div>

<script src="../../assets/js/receptionist.js"></script>

</body>
</html>