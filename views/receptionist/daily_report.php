<?php

require_once "../../app/helpers/session.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "receptionist") {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../../models/receptionistModel.php";

$report = getDailyReportData();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Daily Report</title>

    <link rel="stylesheet"
    href="../../assets/css/receptionist.css">
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

        <button
        class="dark-toggle"
        onclick="toggleDarkMode()">

            Dark Mode

        </button>

    </div>

    <div class="reception-main">

        <div class="reception-top">

            <div>

                <h1>Daily Report</h1>

                <p>
                    Today's front-desk operational summary.
                </p>

            </div>

            <button
            onclick="window.print()"
            class="reception-logout">

                Print Report

            </button>

        </div>

        <div class="reception-cards">

            <div class="reception-card">
                <h3>Today's Check-ins</h3>
                <h2><?php echo $report["checkins"]; ?></h2>
            </div>

            <div class="reception-card">
                <h3>Today's Check-outs</h3>
                <h2><?php echo $report["checkouts"]; ?></h2>
            </div>

            <div class="reception-card">
                <h3>Occupied Rooms</h3>
                <h2><?php echo $report["occupied"]; ?></h2>
            </div>

            <div class="reception-card">
                <h3>Available Rooms</h3>
                <h2><?php echo $report["available"]; ?></h2>
            </div>

            <div class="reception-card">
                <h3>Dirty Rooms</h3>
                <h2><?php echo $report["dirty"]; ?></h2>
            </div>

            <div class="reception-card revenue-card">
                <h3>Today's Revenue</h3>

                <h2>
                    BDT <?php echo number_format($report["revenue"], 2); ?>
                </h2>
            </div>

        </div>

        <div class="reception-table">

            <h2>Reception Summary</h2>

            <table>

                <thead>

                    <tr>
                        <th>Report Category</th>
                        <th>Value</th>
                    </tr>

                </thead>

                <tbody>

                    <tr>
                        <td>Total Check-ins Today</td>
                        <td><?php echo $report["checkins"]; ?></td>
                    </tr>

                    <tr>
                        <td>Total Check-outs Today</td>
                        <td><?php echo $report["checkouts"]; ?></td>
                    </tr>

                    <tr>
                        <td>Occupied Rooms</td>
                        <td><?php echo $report["occupied"]; ?></td>
                    </tr>

                    <tr>
                        <td>Available Rooms</td>
                        <td><?php echo $report["available"]; ?></td>
                    </tr>

                    <tr>
                        <td>Dirty Rooms</td>
                        <td><?php echo $report["dirty"]; ?></td>
                    </tr>

                    <tr>
                        <td>Total Revenue</td>

                        <td>
                            BDT <?php echo number_format($report["revenue"], 2); ?>
                        </td>
                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>

<script src="../../assets/js/receptionist.js"></script>

</body>
</html>