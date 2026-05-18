<?php

require_once "../../app/helpers/session.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "receptionist") {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../../models/receptionistModel.php";

$requests = getReceptionistServiceRequests();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Service Requests</title>
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
            <li><a href="walkin_booking.php">Walk-in Booking</a></li>
            <li><a href="service_requests.php">Service Requests</a></li>
            <li><a href="booking_modifications.php">Booking Changes</a></li>
            <li><a href="early_late_requests.php">Early/Late</a></li>
            <li><a href="daily_report.php">Daily Report</a></li>
            <li><a href="activity_logs.php">Activity Logs</a></li>
        </ul>

        <button class="dark-toggle" onclick="toggleDarkMode()">Dark Mode</button>

    </div>

    <div class="reception-main">

        <div class="reception-top">
            <div>
                <h1>Service Requests</h1>
                <p>View and manage guest service requests.</p>
            </div>

            <a href="../../logout.php" class="reception-logout">Logout</a>
        </div>

        <div class="reception-table">

            <h2>Guest Request List</h2>

            <input
                type="text"
                id="serviceSearch"
                class="search-box"
                placeholder="Search guest, room, service or status..."
            >

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Guest</th>
                        <th>Room</th>
                        <th>Service Type</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Requested At</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if ($requests && $requests->num_rows > 0) { ?>

                        <?php while ($row = $requests->fetch_assoc()) { ?>

                            <tr>
                                <td>#SR<?php echo $row["id"]; ?></td>

                                <td><?php echo htmlspecialchars($row["guest_name"]); ?></td>

                                <td>
                                    <?php
                                    if (!empty($row["room_number"])) {
                                        echo htmlspecialchars($row["room_number"]);
                                    } else {
                                        echo "Not Assigned";
                                    }
                                    ?>
                                </td>

                                <td>
                                    <?php echo ucfirst(str_replace("_", " ", $row["service_type"])); ?>
                                </td>

                                <td><?php echo htmlspecialchars($row["description"]); ?></td>

                                <td>
                                    <span class="reception-badge badge-<?php echo $row["status"]; ?>">
                                        <?php echo ucfirst(str_replace("_", " ", $row["status"])); ?>
                                    </span>
                                </td>

                                <td><?php echo $row["requested_at"]; ?></td>

                                <td>
                                    <?php if ($row["status"] != "completed") { ?>

                                        <form action="../../controllers/receptionistController.php" method="POST">
                                            <input type="hidden" name="action" value="complete_service">
                                            <input type="hidden" name="service_id" value="<?php echo $row["id"]; ?>">

                                            <button type="submit" class="edit-btn">
                                                Complete
                                            </button>
                                        </form>

                                    <?php } else { ?>

                                        <span class="reception-badge badge-available">
                                            Completed
                                        </span>

                                    <?php } ?>
                                </td>
                            </tr>

                        <?php } ?>

                    <?php } else { ?>

                        <tr>
                            <td colspan="8">No service requests found.</td>
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