<?php

require_once "../../app/helpers/session.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "guest") {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../../models/guestModel.php";

$data = getGuestDashboardData($_SESSION["user_id"]);

require_once "../../app/layouts/header.php";
require_once "../../app/layouts/sidebar.php";
require_once "../../app/layouts/navbar.php";

?>

<div class="main-content">

    <div class="dashboard-title">
        <h1>Guest Dashboard</h1>
        <p>Welcome to your hotel account. Manage bookings, services, billing and loyalty points.</p>
    </div>

    <div class="card-container">

        <div class="dashboard-card">
            <h3>Total Bookings</h3>
            <h2><?php echo $data["total_bookings"]; ?></h2>
        </div>

        <div class="dashboard-card">
            <h3>Active Bookings</h3>
            <h2><?php echo $data["active_bookings"]; ?></h2>
        </div>

        <div class="dashboard-card">
            <h3>Loyalty Points</h3>
            <h2><?php echo $data["loyalty_balance"]; ?></h2>
        </div>

        <div class="dashboard-card">
            <h3>Service Requests</h3>
            <h2><?php echo $data["service_requests"]; ?></h2>
        </div>

        <div class="dashboard-card">
            <h3>Pending Bills</h3>
            <h2><?php echo $data["pending_bills"]; ?></h2>
        </div>

    </div>

    <div class="dashboard-table">
        <h2>Guest Quick Actions</h2>

        <table>
            <thead>
                <tr>
                    <th>Feature</th>
                    <th>Description</th>
                    <th>Open</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td>Search Rooms</td>
                    <td>Find available rooms by check-in, check-out and guests.</td>
                    <td><a href="search_rooms.php" class="edit-btn">Open</a></td>
                </tr>

                <tr>
                    <td>My Bookings</td>
                    <td>View upcoming and past reservations.</td>
                    <td><a href="my_bookings.php" class="edit-btn">Open</a></td>
                </tr>

                <tr>
                    <td>Service Requests</td>
                    <td>Request laundry, toiletries, room service and more.</td>
                    <td><a href="service_requests.php" class="edit-btn">Open</a></td>
                </tr>

                <tr>
                    <td>Billing History</td>
                    <td>View invoices, payment status and receipts.</td>
                    <td><a href="billing.php" class="edit-btn">Open</a></td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

<?php require_once "../../app/layouts/footer.php"; ?>