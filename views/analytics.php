<?php

require_once "../../app/helpers/session.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../../models/reportModel.php";

$totalRooms = getTotalRoomsReport();
$occupiedRooms = getOccupiedRoomsReport();
$availableRooms = getAvailableRoomsReport();
$totalBookings = getTotalBookingsReport();
$totalRevenue = getTotalRevenue();
$popularRoomTypes = getPopularRoomTypes();

$occupancyRate = 0;

if ($totalRooms > 0) {
    $occupancyRate = round(($occupiedRooms / $totalRooms) * 100, 2);
}

require_once "../../app/layouts/header.php";
require_once "../../app/layouts/sidebar.php";
require_once "../../app/layouts/navbar.php";

?>

<div class="main-content">

    <div class="dashboard-title">
        <h1>Analytics</h1>
        <p>Hotel performance overview and room usage analysis.</p>
    </div>

    <div class="card-container">

        <div class="dashboard-card">
            <h3>Occupancy Rate</h3>
            <h2><?php echo $occupancyRate; ?>%</h2>
        </div>

        <div class="dashboard-card">
            <h3>Total Revenue</h3>
            <h2>৳ <?php echo $totalRevenue; ?></h2>
        </div>

        <div class="dashboard-card">
            <h3>Total Bookings</h3>
            <h2><?php echo $totalBookings; ?></h2>
        </div>

        <div class="dashboard-card">
            <h3>Available Rooms</h3>
            <h2><?php echo $availableRooms; ?></h2>
        </div>

    </div>

    <div class="dashboard-table">

        <h2>Most Popular Room Types</h2>

        <br>

        <table>
            <thead>
                <tr>
                    <th>Room Type</th>
                    <th>Total Bookings</th>
                </tr>
            </thead>

            <tbody>
                <?php
                if ($popularRoomTypes && $popularRoomTypes->num_rows > 0) {
                    while ($row = $popularRoomTypes->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["room_type"] . "</td>";
                        echo "<td>" . $row["total_bookings"] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>No analytics data found.</td></tr>";
                }
                ?>
            </tbody>
        </table>

    </div>

</div>

<?php require_once "../../app/layouts/footer.php"; ?>