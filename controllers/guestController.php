<?php

session_start();

require_once "../models/guestModel.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "guest") {
    header("Location: ../views/auth/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if ($_POST["action"] == "update_profile") {

        $guestId = $_SESSION["user_id"];

        $name = trim($_POST["name"]);
        $phone = trim($_POST["phone"]);
        $nationality = trim($_POST["nationality"]);
        $idNumber = trim($_POST["id_number"]);

        $profilePic = "";

        if (isset($_FILES["profile_pic"]) && $_FILES["profile_pic"]["name"] != "") {

            $uploadDir = "../uploads/profile/";

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileName = time() . "_" . basename($_FILES["profile_pic"]["name"]);
            $targetPath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $targetPath)) {
                $profilePic = "uploads/profile/" . $fileName;
            }
        }

        updateGuestProfile($guestId, $name, $phone, $nationality, $idNumber, $profilePic);

        header("Location: ../views/guest/profile.php?success=profile_updated");
        exit();
    }

    if ($_POST["action"] == "change_password") {

        $guestId = $_SESSION["user_id"];

        $newPassword = trim($_POST["new_password"]);
        $confirmPassword = trim($_POST["confirm_password"]);

        if ($newPassword != $confirmPassword) {
            header("Location: ../views/guest/profile.php?error=password_mismatch");
            exit();
        }

        if (strlen($newPassword) < 6) {
            header("Location: ../views/guest/profile.php?error=password_short");
            exit();
        }

        changeGuestPassword($guestId, $newPassword);

        header("Location: ../views/guest/profile.php?success=password_changed");
        exit();
    }
	
	/* =========================================
   AJAX ROOM SEARCH
========================================= */

if ($_POST["action"] == "search_rooms") {

    $checkin = trim($_POST["checkin"]);

    $checkout = trim($_POST["checkout"]);

    $guests = intval($_POST["guests"]);

    $rooms = searchAvailableRoomTypes(
        $checkin,
        $checkout,
        $guests
    );

    if ($rooms->num_rows == 0) {

        echo "
            <div class='empty-state'>
                No available rooms found.
            </div>
        ";

        exit();
    }

    echo "<div class='room-grid'>";

    while ($row = $rooms->fetch_assoc()) {

        echo "

        <div class='room-card'>

            <div class='room-image'>

                <img src='../../" . htmlspecialchars($row["thumbnail_path"]) . "'>

            </div>

            <div class='room-content'>

                <h3>
                    " . htmlspecialchars($row["name"]) . "
                </h3>

                <p>
                    " . htmlspecialchars(substr($row["description"],0,120)) . "...
                </p>

                <div class='room-meta'>

                    <span>
                        Max Guests:
                        " . $row["max_capacity"] . "
                    </span>

                    <span>
                        Available:
                        " . $row["available_rooms"] . "
                    </span>

                </div>

                <div class='room-price'>
                    ৳ " . number_format($row["price_per_night"],2) . "
                    <small>/ night</small>
                </div>

                <a class='room-btn'

                href='../guest/room_details.php?id=" . $row["id"] . "&checkin=" . $checkin . "&checkout=" . $checkout . "&guests=" . $guests . "'>

                    View Details

                </a>

            </div>

        </div>
        ";
    }

    echo "</div>";

    exit();
}

if ($_POST["action"] == "create_booking") {

    $guestId = $_SESSION["user_id"];

    $roomTypeId = intval($_POST["room_type_id"]);
    $checkin = $_POST["checkin"];
    $checkout = $_POST["checkout"];
    $guests = intval($_POST["guests"]);
    $specialRequest = trim($_POST["special_request"]);
    $redeemPoints = intval($_POST["redeem_points"]);

    $availablePoints = getGuestLatestLoyaltyBalance($guestId);

    if ($redeemPoints > $availablePoints) {
        $redeemPoints = $availablePoints;
    }

    $priceData = calculateGuestBookingPrice($roomTypeId, $checkin, $checkout, $redeemPoints);

    $bookingId = createGuestBookingWithBilling(
        $guestId,
        $roomTypeId,
        $checkin,
        $checkout,
        $guests,
        $priceData["total_amount"],
        $priceData["base_amount"],
        $priceData["discount_amount"],
        $specialRequest,
        $redeemPoints
    );

    if ($bookingId) {
        header("Location: ../views/guest/booking_confirmation.php?id=" . $bookingId);
        exit();
    }

    header("Location: ../views/guest/search_rooms.php?error=booking_failed");
    exit();
}

if ($_POST["action"] == "cancel_booking") {

    $guestId = $_SESSION["user_id"];
    $bookingId = intval($_POST["booking_id"]);

    $result = cancelGuestBooking($guestId, $bookingId);

    header("Location: ../views/guest/booking_details.php?id=" . $bookingId . "&cancel=" . $result);
    exit();
}

if ($_POST["action"] == "request_modification") {

    $guestId = $_SESSION["user_id"];

    $bookingId = intval($_POST["booking_id"]);
    $newCheckin = $_POST["new_checkin_date"];
    $newCheckout = $_POST["new_checkout_date"];
    $reason = trim($_POST["reason"]);

    createBookingModificationRequest($guestId, $bookingId, $newCheckin, $newCheckout, $reason);

    header("Location: ../views/guest/modification_request.php?success=1");
    exit();
}

if ($_POST["action"] == "create_service_request") {

    $guestId = $_SESSION["user_id"];

    $bookingId = intval($_POST["booking_id"]);
    $serviceType = trim($_POST["service_type"]);
    $description = trim($_POST["description"]);

    createGuestServiceRequest($guestId, $bookingId, $serviceType, $description);

    header("Location: ../views/guest/service_requests.php?success=created");
    exit();
}

if ($_POST["action"] == "create_review") {

    $guestId = $_SESSION["user_id"];

    $bookingId = intval($_POST["booking_id"]);
    $overallRating = intval($_POST["overall_rating"]);
    $cleanlinessRating = intval($_POST["cleanliness_rating"]);
    $serviceRating = intval($_POST["service_rating"]);
    $reviewText = trim($_POST["review_text"]);

    createGuestReview($guestId, $bookingId, $overallRating, $cleanlinessRating, $serviceRating, $reviewText);

    header("Location: ../views/guest/reviews.php?success=created");
    exit();
}

if ($_POST["action"] == "update_review") {

    $guestId = $_SESSION["user_id"];

    $reviewId = intval($_POST["review_id"]);
    $overallRating = intval($_POST["overall_rating"]);
    $cleanlinessRating = intval($_POST["cleanliness_rating"]);
    $serviceRating = intval($_POST["service_rating"]);
    $reviewText = trim($_POST["review_text"]);

    updateGuestReview($guestId, $reviewId, $overallRating, $cleanlinessRating, $serviceRating, $reviewText);

    header("Location: ../views/guest/reviews.php?success=updated");
    exit();
}

if ($_POST["action"] == "delete_review") {

    $guestId = $_SESSION["user_id"];

    $reviewId = intval($_POST["review_id"]);

    deleteGuestReview($guestId, $reviewId);

    header("Location: ../views/guest/reviews.php?success=deleted");
    exit();
}
}

?>