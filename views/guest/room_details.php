<?php

require_once "../../app/helpers/session.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "guest") {

    header("Location: ../auth/login.php");
    exit();
}

require_once "../../models/guestModel.php";

$roomTypeId = $_GET["id"];

$checkin = $_GET["checkin"];

$checkout = $_GET["checkout"];

$guests = $_GET["guests"];

$room = getRoomTypeDetails($roomTypeId);

$images = getRoomTypeImages($roomTypeId);

$ratings = getRoomAverageRatings($roomTypeId);

$seasonal = getSeasonalPricingNotice(
    $roomTypeId,
    $checkin,
    $checkout
);

require_once "../../app/layouts/header.php";
require_once "../../app/layouts/sidebar.php";
require_once "../../app/layouts/navbar.php";

?>

<div class="main-content">

    <div class="dashboard-title">

        <h1>
            <?php echo htmlspecialchars($room["name"]); ?>
        </h1>

        <p>
            Luxury room details and amenities.
        </p>

    </div>

    <?php if ($seasonal) { ?>

        <div class="success-message">

            Seasonal Pricing Active:
            <?php echo htmlspecialchars($seasonal["label"]); ?>

        </div>

    <?php } ?>

    <div class="room-gallery">

        <?php while($img = $images->fetch_assoc()) { ?>

            <img
            src="../../<?php echo $img["image_path"]; ?>">

        <?php } ?>

    </div>

    <div class="dashboard-table">

        <h2>Room Information</h2>

        <table>

            <tr>
                <th>Room Type</th>
                <td>
                    <?php echo htmlspecialchars($room["name"]); ?>
                </td>
            </tr>

            <tr>
                <th>Description</th>
                <td>
                    <?php echo htmlspecialchars($room["description"]); ?>
                </td>
            </tr>

            <tr>
                <th>Max Capacity</th>
                <td>
                    <?php echo $room["max_capacity"]; ?>
                </td>
            </tr>

            <tr>
                <th>Price Per Night</th>
                <td>
                    ৳
                    <?php echo number_format($room["price_per_night"],2); ?>
                </td>
            </tr>

            <tr>
                <th>Amenities</th>
                <td>
                    <?php echo htmlspecialchars($room["amenities"]); ?>
                </td>
            </tr>

        </table>

    </div>

    <div class="dashboard-table">

        <h2>Guest Ratings</h2>

        <table>

            <tr>
                <th>Overall Rating</th>
                <td>
                    <?php echo $ratings["overall_rating"] ?? "N/A"; ?>
                </td>
            </tr>

            <tr>
                <th>Cleanliness Rating</th>
                <td>
                    <?php echo $ratings["cleanliness_rating"] ?? "N/A"; ?>
                </td>
            </tr>

            <tr>
                <th>Service Rating</th>
                <td>
                    <?php echo $ratings["service_rating"] ?? "N/A"; ?>
                </td>
            </tr>

        </table>

    </div>

    <a

    class="print-btn"

    href="book_room.php?id=<?php echo $roomTypeId; ?>&checkin=<?php echo $checkin; ?>&checkout=<?php echo $checkout; ?>&guests=<?php echo $guests; ?>">

        Continue Booking

    </a>

</div>

<?php require_once "../../app/layouts/footer.php"; ?>