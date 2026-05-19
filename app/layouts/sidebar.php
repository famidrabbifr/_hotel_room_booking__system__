<?php

$role = $_SESSION["role"] ?? "";

?>

<div class="sidebar">

    <div class="sidebar-logo">
        <h2>GPH</h2>
        <p>
            <?php echo ucfirst($role); ?> Panel
        </p>
    </div>

    <ul class="sidebar-menu">

        <!-- ================= ADMIN ================= -->

        <?php if ($role == "admin") { ?>

            <li>
                <a href="dashboard.php">
                    <i class="fa-solid fa-chart-line"></i>
                    Dashboard
                </a>
            </li>

            <li>
                <a href="room_types.php">
                    <i class="fa-solid fa-bed"></i>
                    Room Types
                </a>
            </li>

            <li>
                <a href="rooms.php">
                    <i class="fa-solid fa-door-open"></i>
                    Rooms
                </a>
            </li>

            <li>
                <a href="users.php">
                    <i class="fa-solid fa-users"></i>
                    Users
                </a>
            </li>

            <li>
                <a href="bookings.php">
                    <i class="fa-solid fa-calendar-check"></i>
                    Bookings
                </a>
            </li>

            <li>
                <a href="pricing.php">
                    <i class="fa-solid fa-money-bill-wave"></i>
                    Pricing
                </a>
            </li>

            <li>
                <a href="reports.php">
                    <i class="fa-solid fa-chart-pie"></i>
                    Reports
                </a>
            </li>

            <li>
                <a href="reports_financial.php">
                    <i class="fa-solid fa-chart-column"></i>
                    Financial Reports
                </a>
            </li>

            <li>
                <a href="reports_occupancy.php">
                    <i class="fa-solid fa-hotel"></i>
                    Occupancy Reports
                </a>
            </li>

            <li>
                <a href="reports_loyalty.php">
                    <i class="fa-solid fa-gift"></i>
                    Loyalty Reports
                </a>
            </li>

            <li>
                <a href="reports_service.php">
                    <i class="fa-solid fa-headset"></i>
                    Service Trends
                </a>
            </li>

            <li>
                <a href="reviews.php">
                    <i class="fa-solid fa-star"></i>
                    Reviews
                </a>
            </li>

            <li>
                <a href="announcements.php">
                    <i class="fa-solid fa-bullhorn"></i>
                    Announcements
                </a>
            </li>

        <?php } ?>



        <!-- ================= RECEPTIONIST ================= -->

        <?php if ($role == "receptionist") { ?>

            <li>
                <a href="dashboard.php">
                    <i class="fa-solid fa-chart-line"></i>
                    Dashboard
                </a>
            </li>

            <li>
                <a href="bookings.php">
                    <i class="fa-solid fa-calendar-check"></i>
                    Bookings
                </a>
            </li>

            <li>
                <a href="checkin.php">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    Check In
                </a>
            </li>

            <li>
                <a href="checkout.php">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    Check Out
                </a>
            </li>

            <li>
                <a href="walkin_booking.php">
                    <i class="fa-solid fa-person-walking-luggage"></i>
                    Walk-In Booking
                </a>
            </li>

            <li>
                <a href="billing.php">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                    Billing
                </a>
            </li>

            <li>
                <a href="service_requests.php">
                    <i class="fa-solid fa-bell-concierge"></i>
                    Service Requests
                </a>
            </li>

        <?php } ?>



        <!-- ================= GUEST ================= -->

        <?php if ($role == "guest") { ?>

            <li>
                <a href="dashboard.php">
                    <i class="fa-solid fa-house"></i>
                    Dashboard
                </a>
            </li>

            <li>
                <a href="profile.php">
                    <i class="fa-solid fa-user"></i>
                    My Profile
                </a>
            </li>

            <li>
                <a href="search_rooms.php">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    Search Rooms
                </a>
            </li>

            <li>
                <a href="my_bookings.php">
                    <i class="fa-solid fa-calendar-check"></i>
                    My Bookings
                </a>
            </li>

            <li>
                <a href="service_requests.php">
                    <i class="fa-solid fa-bell-concierge"></i>
                    Service Requests
                </a>
            </li>

            <li>
                <a href="reviews.php">
                    <i class="fa-solid fa-star"></i>
                    Reviews
                </a>
            </li>

            <li>
                <a href="loyalty.php">
                    <i class="fa-solid fa-gift"></i>
                    Loyalty Points
                </a>
            </li>

            <li>
                <a href="billing.php">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                    Billing History
                </a>
            </li>

        <?php } ?>

    </ul>

</div>