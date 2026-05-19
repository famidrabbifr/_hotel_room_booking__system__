<?php

require_once "../../app/helpers/session.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "guest") {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../../models/guestModel.php";

$search = $_GET["search"] ?? "";
$editId = $_GET["edit"] ?? "";

$completedBookings = getGuestCompletedBookingsForReview($_SESSION["user_id"]);
$reviews = getGuestReviews($_SESSION["user_id"], $search);

$editReview = null;

if ($editId != "") {
    $editReview = getSingleGuestReview($_SESSION["user_id"], intval($editId));
}

require_once "../../app/layouts/header.php";
require_once "../../app/layouts/sidebar.php";
require_once "../../app/layouts/navbar.php";

?>

<div class="main-content">

    <div class="dashboard-title">
        <h1>My Reviews</h1>
        <p>Share your completed stay experience and manage your reviews.</p>
    </div>

    <?php if (isset($_GET["success"])) { ?>
        <div class="success-message">
            Review action completed successfully.
        </div>
    <?php } ?>

    <div class="form-box">

        <?php if ($editReview) { ?>

            <h2>Edit Review</h2>

            <form method="POST" action="../../controllers/guestController.php">

                <input type="hidden" name="action" value="update_review">
                <input type="hidden" name="review_id" value="<?php echo $editReview["id"]; ?>">

                <div class="form-row">

                    <div class="form-group">
                        <label>Overall Rating</label>
                        <select name="overall_rating" required>
                            <?php for ($i = 1; $i <= 5; $i++) { ?>
                                <option value="<?php echo $i; ?>" <?php if ($editReview["overall_rating"] == $i) echo "selected"; ?>>
                                    <?php echo $i; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Cleanliness Rating</label>
                        <select name="cleanliness_rating" required>
                            <?php for ($i = 1; $i <= 5; $i++) { ?>
                                <option value="<?php echo $i; ?>" <?php if ($editReview["cleanliness_rating"] == $i) echo "selected"; ?>>
                                    <?php echo $i; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Service Rating</label>
                        <select name="service_rating" required>
                            <?php for ($i = 1; $i <= 5; $i++) { ?>
                                <option value="<?php echo $i; ?>" <?php if ($editReview["service_rating"] == $i) echo "selected"; ?>>
                                    <?php echo $i; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Review Text</label>
                        <textarea name="review_text" required><?php echo htmlspecialchars($editReview["review_text"]); ?></textarea>
                    </div>

                </div>

                <button type="submit" class="print-btn">Update Review</button>
                <a href="reviews.php" class="cancel-link">Cancel</a>

            </form>

        <?php } else { ?>

            <h2>Write a Review</h2>

            <form method="POST" action="../../controllers/guestController.php">

                <input type="hidden" name="action" value="create_review">

                <div class="form-row">

                    <div class="form-group">
                        <label>Completed Booking</label>

                        <select name="booking_id" required>
                            <option value="">Select Completed Stay</option>

                            <?php while ($booking = $completedBookings->fetch_assoc()) { ?>
                                <option value="<?php echo $booking["id"]; ?>">
                                    #B<?php echo $booking["id"]; ?>
                                    —
                                    <?php echo htmlspecialchars($booking["room_type_name"]); ?>
                                    —
                                    <?php echo $booking["checkin_date"]; ?> to <?php echo $booking["checkout_date"]; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Overall Rating</label>
                        <select name="overall_rating" required>
                            <option value="5">5 - Excellent</option>
                            <option value="4">4 - Good</option>
                            <option value="3">3 - Average</option>
                            <option value="2">2 - Poor</option>
                            <option value="1">1 - Very Poor</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Cleanliness Rating</label>
                        <select name="cleanliness_rating" required>
                            <option value="5">5 - Excellent</option>
                            <option value="4">4 - Good</option>
                            <option value="3">3 - Average</option>
                            <option value="2">2 - Poor</option>
                            <option value="1">1 - Very Poor</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Service Rating</label>
                        <select name="service_rating" required>
                            <option value="5">5 - Excellent</option>
                            <option value="4">4 - Good</option>
                            <option value="3">3 - Average</option>
                            <option value="2">2 - Poor</option>
                            <option value="1">1 - Very Poor</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Review Text</label>
                        <textarea name="review_text" required placeholder="Write your stay experience"></textarea>
                    </div>

                </div>

                <button type="submit" class="print-btn">Submit Review</button>

            </form>

        <?php } ?>

    </div>

    <div class="dashboard-table">

        <h2>My Review History</h2>

        <form method="GET" class="filter-form">

            <input
                type="text"
                name="search"
                placeholder="Search review, room type, admin reply..."
                value="<?php echo htmlspecialchars($search); ?>"
            >

            <button type="submit">Search</button>

            <a href="reviews.php" class="clear-btn">Clear</a>

        </form>

        <table>
            <thead>
                <tr>
                    <th>Room Type</th>
                    <th>Stay Date</th>
                    <th>Overall</th>
                    <th>Cleanliness</th>
                    <th>Service</th>
                    <th>Review</th>
                    <th>Admin Reply</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php if ($reviews->num_rows > 0) { ?>
                    <?php while ($row = $reviews->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row["room_type_name"]); ?></td>
                            <td><?php echo $row["checkin_date"]; ?> to <?php echo $row["checkout_date"]; ?></td>
                            <td><?php echo $row["overall_rating"]; ?>/5</td>
                            <td><?php echo $row["cleanliness_rating"]; ?>/5</td>
                            <td><?php echo $row["service_rating"]; ?>/5</td>
                            <td><?php echo htmlspecialchars($row["review_text"]); ?></td>
                            <td>
                                <?php
                                if (!empty($row["admin_reply"])) {
                                    echo htmlspecialchars($row["admin_reply"]);
                                } else {
                                    echo "No reply yet";
                                }
                                ?>
                            </td>
                            <td>
                                <a href="reviews.php?edit=<?php echo $row["id"]; ?>" class="edit-btn">Edit</a>

                                <form method="POST" action="../../controllers/guestController.php" style="display:inline;">
                                    <input type="hidden" name="action" value="delete_review">
                                    <input type="hidden" name="review_id" value="<?php echo $row["id"]; ?>">
                                    <button type="submit" class="delete-btn" onclick="return confirm('Delete this review?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="8">No reviews found.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

    </div>

</div>

<?php require_once "../../app/layouts/footer.php"; ?>