<?php

require_once "../../app/helpers/session.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "guest") {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../../models/guestModel.php";

$guestId = $_SESSION["user_id"];

$roomTypeId = intval($_GET["id"] ?? 0);
$checkin = $_GET["checkin"] ?? "";
$checkout = $_GET["checkout"] ?? "";
$guests = intval($_GET["guests"] ?? 1);

if ($roomTypeId == 0 || $checkin == "" || $checkout == "") {
    header("Location: search_rooms.php");
    exit();
}

$room = getRoomTypeDetails($roomTypeId);

if (!$room) {
    header("Location: search_rooms.php");
    exit();
}

$loyaltyBalance = getGuestLatestLoyaltyBalance($guestId);

$priceData = calculateGuestBookingPrice(
    $roomTypeId,
    $checkin,
    $checkout,
    0
);

require_once "../../app/layouts/header.php";
require_once "../../app/layouts/sidebar.php";
require_once "../../app/layouts/navbar.php";

?>

<div class="main-content">

    <div class="dashboard-title">
        <h1>Confirm Booking</h1>
        <p>Review your booking details, apply loyalty points and confirm your reservation.</p>
    </div>

    <?php if (!empty($priceData["seasonal_label"])) { ?>
        <div class="success-message">
            Seasonal pricing applied:
            <?php echo htmlspecialchars($priceData["seasonal_label"]); ?>
        </div>
    <?php } ?>

    <div class="dashboard-table">

        <h2>Booking Summary</h2>

        <table>
            <tr>
                <th>Room Type</th>
                <td><?php echo htmlspecialchars($room["name"]); ?></td>
            </tr>

            <tr>
                <th>Description</th>
                <td><?php echo htmlspecialchars($room["description"]); ?></td>
            </tr>

            <tr>
                <th>Check-in Date</th>
                <td><?php echo htmlspecialchars($checkin); ?></td>
            </tr>

            <tr>
                <th>Check-out Date</th>
                <td><?php echo htmlspecialchars($checkout); ?></td>
            </tr>

            <tr>
                <th>Guests</th>
                <td><?php echo $guests; ?></td>
            </tr>

            <tr>
                <th>Total Nights</th>
                <td><?php echo $priceData["days"]; ?></td>
            </tr>

            <tr>
                <th>Price Per Night</th>
                <td>৳ <?php echo number_format($priceData["price_per_night"], 2); ?></td>
            </tr>

            <tr>
                <th>Base Amount</th>
                <td>৳ <?php echo number_format($priceData["base_amount"], 2); ?></td>
            </tr>

            <tr>
                <th>Available Loyalty Points</th>
                <td><?php echo $loyaltyBalance; ?> points</td>
            </tr>
        </table>

    </div>

    <div class="form-box">

        <h2>Confirm Reservation</h2>

        <form method="POST" action="../../controllers/guestController.php">

            <input type="hidden" name="action" value="create_booking">

            <input type="hidden" name="room_type_id" value="<?php echo $roomTypeId; ?>">

            <input type="hidden" name="checkin" value="<?php echo htmlspecialchars($checkin); ?>">

            <input type="hidden" name="checkout" value="<?php echo htmlspecialchars($checkout); ?>">

            <input type="hidden" name="guests" value="<?php echo $guests; ?>">

            <div class="form-row">

                <div class="form-group">
                    <label>Redeem Loyalty Points</label>

                    <input
                        type="number"
                        name="redeem_points"
                        min="0"
                        max="<?php echo $loyaltyBalance; ?>"
                        value="0"
                    >

                    <small>
                        1 point = ৳1 discount. You can redeem maximum
                        <?php echo $loyaltyBalance; ?> points.
                    </small>
                </div>

                <div class="form-group">
                    <label>Special Request</label>

                    <textarea
                        name="special_request"
                        placeholder="Example: high floor, quiet room, extra pillow"
                    ></textarea>
                </div>

            </div>

            <button type="submit" class="print-btn">
                Confirm Booking
            </button>

            <a href="room_details.php?id=<?php echo $roomTypeId; ?>&checkin=<?php echo $checkin; ?>&checkout=<?php echo $checkout; ?>&guests=<?php echo $guests; ?>" class="cancel-link">
                Back to Room Details
            </a>

        </form>

    </div>

</div>

<?php require_once "../../app/layouts/footer.php"; ?>