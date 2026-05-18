<?php

require_once "../../app/helpers/session.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "receptionist") {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../../models/receptionistModel.php";

$rooms = getReceptionistRoomStatus();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Room Status</title>
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
                <h1>Room Status</h1>
                <p>Monitor live room availability and housekeeping status.</p>
            </div>

            <a href="../../logout.php" class="reception-logout">Logout</a>
        </div>

        <div class="reception-table">

            <h2>Hotel Room Overview</h2>

            <input
                type="text"
                id="roomSearch"
                class="search-box"
                placeholder="Search room number, type or status..."
            >

            <table>
                <thead>
                    <tr>
                        <th>Room Number</th>
                        <th>Floor</th>
                        <th>Room Type</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if ($rooms && $rooms->num_rows > 0) { ?>

                        <?php while ($row = $rooms->fetch_assoc()) { ?>

                            <tr>
                                <td><?php echo htmlspecialchars($row["room_number"]); ?></td>

                                <td>Floor <?php echo htmlspecialchars($row["floor"]); ?></td>

                                <td><?php echo htmlspecialchars($row["room_type"]); ?></td>

                                <td>
                                    <span class="reception-badge badge-<?php echo $row["status"]; ?>">
                                        <?php echo ucfirst(str_replace("_", " ", $row["status"])); ?>
                                    </span>
                                </td>
                            </tr>

                        <?php } ?>

                    <?php } else { ?>

                        <tr>
                            <td colspan="4">No rooms found.</td>
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