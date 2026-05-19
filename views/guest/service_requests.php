<?php

require_once "../../app/helpers/session.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "guest") {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../../models/guestModel.php";

$search = $_GET["search"] ?? "";
$status = $_GET["status"] ?? "";

$activeBookings = getGuestActiveBookings($_SESSION["user_id"]);
$requests = getGuestServiceRequests($_SESSION["user_id"], $search, $status);

require_once "../../app/layouts/header.php";
require_once "../../app/layouts/sidebar.php";
require_once "../../app/layouts/navbar.php";

?>

<div class="main-content">

    <div class="dashboard-title">
        <h1>Service Requests</h1>
        <p>Submit in-stay requests and track their current status.</p>
    </div>

    <?php if (isset($_GET["success"])) { ?>
        <div class="success-message">
            Service request submitted successfully.
        </div>
    <?php } ?>

    <div class="form-box">

        <h2>Create New Service Request</h2>

        <form method="POST" action="../../controllers/guestController.php">

            <input type="hidden" name="action" value="create_service_request">

            <div class="form-row">

                <div class="form-group">
                    <label>Active Booking</label>

                    <select name="booking_id" required>
                        <option value="">Select Active Stay</option>

                        <?php while ($booking = $activeBookings->fetch_assoc()) { ?>
                            <option value="<?php echo $booking["id"]; ?>">
                                #B<?php echo $booking["id"]; ?>
                                —
                                <?php echo htmlspecialchars($booking["room_type_name"]); ?>
                                —
                                Room <?php echo $booking["room_number"] ?? "Not Assigned"; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Service Type</label>

                    <select name="service_type" required>
                        <option value="">Select Service</option>
                        <option value="extra_bed">Extra Bed</option>
                        <option value="toiletries">Toiletries</option>
                        <option value="laundry">Laundry</option>
                        <option value="room_service">Room Service</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" required placeholder="Write your request details"></textarea>
                </div>

            </div>

            <button type="submit" class="print-btn">Submit Request</button>

        </form>

    </div>

    <div class="dashboard-table">

        <h2>Track Service Requests</h2>

        <form method="GET" class="filter-form">

            <input
                type="text"
                name="search"
                placeholder="Search service, description, room..."
                value="<?php echo htmlspecialchars($search); ?>"
            >

            <select name="status">
                <option value="">All Status</option>
                <option value="pending" <?php if ($status == "pending") echo "selected"; ?>>Pending</option>
                <option value="in_progress" <?php if ($status == "in_progress") echo "selected"; ?>>In Progress</option>
                <option value="completed" <?php if ($status == "completed") echo "selected"; ?>>Completed</option>
            </select>

            <button type="submit">Search</button>

            <a href="service_requests.php" class="clear-btn">Clear</a>

        </form>

        <table>
            <thead>
                <tr>
                    <th>Booking</th>
                    <th>Room Type</th>
                    <th>Room</th>
                    <th>Service</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Requested At</th>
                </tr>
            </thead>

            <tbody>
                <?php if ($requests->num_rows > 0) { ?>
                    <?php while ($row = $requests->fetch_assoc()) { ?>
                        <tr>
                            <td>#B<?php echo $row["booking_id"]; ?></td>
                            <td><?php echo htmlspecialchars($row["room_type_name"]); ?></td>
                            <td><?php echo $row["room_number"] ?? "Not Assigned"; ?></td>
                            <td><?php echo ucfirst(str_replace("_", " ", $row["service_type"])); ?></td>
                            <td><?php echo htmlspecialchars($row["description"]); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo $row["status"]; ?>">
                                    <?php echo ucfirst(str_replace("_", " ", $row["status"])); ?>
                                </span>
                            </td>
                            <td><?php echo $row["requested_at"]; ?></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="7">No service requests found.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

    </div>

</div>

<?php require_once "../../app/layouts/footer.php"; ?>