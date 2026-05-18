<?php

require_once "../../app/helpers/session.php";

if (
    !isset($_SESSION["user_id"]) ||
    $_SESSION["role"] != "receptionist"
) {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../../models/receptionistModel.php";

$logs = getReceptionLogs();

?>

<!DOCTYPE html>
<html>

<head>

    <title>Activity Logs</title>

    <link rel="stylesheet"
          href="../../assets/css/receptionist.css">

</head>

<body>

<div class="reception-wrapper">

    <!-- SIDEBAR -->

    <div class="reception-sidebar">

        <div class="reception-logo">
            <h2>GPH</h2>
            <p>Reception Desk</p>
        </div>

        <ul class="reception-menu">

            <li><a href="dashboard.php">Dashboard</a></li>

            <li><a href="today_checkins.php">
                Today Check-ins
            </a></li>

            <li><a href="today_checkouts.php">
                Today Check-outs
            </a></li>

            <li><a href="room_status.php">
                Room Status
            </a></li>

            <li><a href="payments.php">
                Payments
            </a></li>

            <li><a href="walkin_booking.php">
                Walk-in Booking
            </a></li>

            <li><a href="service_requests.php">
                Service Requests
            </a></li>

            <li><a href="daily_report.php">
                Daily Report
            </a></li>

            <li><a href="activity_logs.php">
                Activity Logs
            </a></li>

            <li><a href="booking_modifications.php">
                Booking Changes
            </a></li>

            <li><a href="early_late_requests.php">
                Early/Late
            </a></li>

        </ul>

        <button class="dark-toggle"
                onclick="toggleDarkMode()">

            🌙 Dark Mode

        </button>

    </div>

    <!-- MAIN -->

    <div class="reception-main">

        <div class="reception-top">

            <div>

                <h1>Activity Logs</h1>

                <p>
                    Receptionist operational history
                    and recent activities.
                </p>

            </div>

        </div>

        <!-- TABLE -->

        <div class="reception-table">

            <h2>Recent Activities</h2>

            <br>

            <input
                type="text"
                id="logSearch"
                class="search-box"
                placeholder="Search activity, receptionist or IP..."
            >

            <table>

                <thead>

                    <tr>

                        <th>ID</th>

                        <th>Receptionist</th>

                        <th>Action</th>

                        <th>Description</th>

                        <th>IP Address</th>

                        <th>Date</th>

                    </tr>

                </thead>

                <tbody>

                    <?php

                    if ($logs && $logs->num_rows > 0) {

                        while ($row = $logs->fetch_assoc()) {

                    ?>

                        <tr>

                            <td>
                                #LOG<?php echo $row["id"]; ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $row["name"]
                                );
                                ?>
                            </td>

                            <td>

                                <span class="reception-badge badge-occupied">

                                    <?php
                                    echo htmlspecialchars(
                                        $row["action"]
                                    );
                                    ?>

                                </span>

                            </td>

                            <td>

                                <?php
                                echo htmlspecialchars(
                                    $row["description"]
                                );
                                ?>

                            </td>

                            <td>

                                <?php
                                echo htmlspecialchars(
                                    $row["ip_address"]
                                );
                                ?>

                            </td>

                            <td>

                                <?php
                                echo $row["created_at"];
                                ?>

                            </td>

                        </tr>

                    <?php

                        }

                    } else {

                    ?>

                        <tr>

                            <td colspan="6">

                                No activity logs found.

                            </td>

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