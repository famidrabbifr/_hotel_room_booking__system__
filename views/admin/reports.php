<?php

require_once "../../app/helpers/session.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../../models/reportModel.php";

$totalRevenue = getTotalRevenue();
$totalRooms = getTotalRoomsReport();
$occupiedRooms = getOccupiedRoomsReport();
$availableRooms = getAvailableRoomsReport();
$totalBookings = getTotalBookingsReport();
$bookingsByStatus = getBookingsByStatus();
$revenueByRoomType = getRevenueByRoomType();

require_once "../../app/layouts/header.php";
require_once "../../app/layouts/sidebar.php";
require_once "../../app/layouts/navbar.php";

?>

<div class="main-content">

    <div class="dashboard-title">
	<div style="margin-top:15px;">

    <button
        class="print-btn"
        onclick="window.print()"
    >
        Print Report
    </button>

</div>
        <h1>Reports</h1>
        <p>View hotel financial and operational reports.</p>
    </div>

    <div class="card-container">

        <div class="dashboard-card">
            <h3>Total Revenue</h3>
            <h2>৳ <?php echo $totalRevenue; ?></h2>
        </div>

        <div class="dashboard-card">
            <h3>Total Rooms</h3>
            <h2><?php echo $totalRooms; ?></h2>
        </div>

        <div class="dashboard-card">
            <h3>Occupied Rooms</h3>
            <h2><?php echo $occupiedRooms; ?></h2>
        </div>

        <div class="dashboard-card">
            <h3>Available Rooms</h3>
            <h2><?php echo $availableRooms; ?></h2>
        </div>

        <div class="dashboard-card">
            <h3>Total Bookings</h3>
            <h2><?php echo $totalBookings; ?></h2>
        </div>

    </div>

    <div class="dashboard-table">
        <h2>Booking Status Report</h2>
        <br>

        <table>
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Total Bookings</th>
                </tr>
            </thead>

            <tbody>
                <?php
                if ($bookingsByStatus && $bookingsByStatus->num_rows > 0) {
                    while ($row = $bookingsByStatus->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . ucfirst($row["status"]) . "</td>";
                        echo "<td>" . $row["total"] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>No data found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <br>

    <div class="dashboard-table">
        <h2>Revenue by Room Type</h2>
        <br>

        <table>
            <thead>
                <tr>
                    <th>Room Type</th>
                    <th>Revenue</th>
                </tr>
            </thead>

            <tbody>
                <?php
                if ($revenueByRoomType && $revenueByRoomType->num_rows > 0) {
                    while ($row = $revenueByRoomType->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["room_type"] . "</td>";
                        echo "<td>৳ " . ($row["revenue"] ? $row["revenue"] : 0) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>No revenue data found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</div>

<?php require_once "../../app/layouts/footer.php"; ?>