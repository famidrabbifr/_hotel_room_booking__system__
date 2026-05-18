<?php

require_once "../../app/helpers/session.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../../models/reviewModel.php";

$reviews = getAllReviews();

$editData = null;

if (isset($_GET["reply"])) {
    $editData = getReviewById($_GET["reply"]);
}

require_once "../../app/layouts/header.php";
require_once "../../app/layouts/sidebar.php";
require_once "../../app/layouts/navbar.php";

?>

<div class="main-content">

    <div class="dashboard-title">
        <h1>Review Management</h1>
        <p>View guest reviews and respond officially from the hotel.</p>
    </div>

    <?php

    if (isset($_SESSION["success"])) {
        echo "<div class='success-message'>";
        echo $_SESSION["success"];
        echo "</div>";

        unset($_SESSION["success"]);
    }

    if (isset($_SESSION["error"])) {
        echo "<div class='error'>";
        echo $_SESSION["error"];
        echo "</div>";

        unset($_SESSION["error"]);
    }

    ?>

    <?php if ($editData) { ?>

        <div class="form-box">

            <h2>Reply to Review</h2>

            <form action="../../controllers/reviewController.php" method="POST">

                <input
                    type="hidden"
                    name="id"
                    value="<?php echo $editData["id"]; ?>"
                >

                <div class="form-row">

                    <div class="form-group">
                        <label>Overall Rating</label>
                        <input
                            type="text"
                            value="<?php echo $editData["overall_rating"]; ?>/5"
                            readonly
                        >
                    </div>

                    <div class="form-group">
                        <label>Cleanliness Rating</label>
                        <input
                            type="text"
                            value="<?php echo $editData["cleanliness_rating"]; ?>/5"
                            readonly
                        >
                    </div>

                </div>

                <div class="form-row">

                    <div class="form-group">
                        <label>Service Rating</label>
                        <input
                            type="text"
                            value="<?php echo $editData["service_rating"]; ?>/5"
                            readonly
                        >
                    </div>

                    <div class="form-group">
                        <label>Review Date</label>
                        <input
                            type="text"
                            value="<?php echo $editData["created_at"]; ?>"
                            readonly
                        >
                    </div>

                </div>

                <div class="form-group">

                    <label>Guest Review</label>

                    <textarea readonly><?php echo $editData["review_text"]; ?></textarea>

                </div>

                <div class="form-group">

                    <label>Admin Reply</label>

                    <textarea
                        name="admin_reply"
                        required
                    ><?php echo $editData["admin_reply"]; ?></textarea>

                </div>

                <button type="submit">
                    Save Reply
                </button>

                <a href="reviews.php" class="cancel-link">
                    Cancel
                </a>

            </form>

        </div>

    <?php } ?>

    <div class="dashboard-table">
	<div class="table-search-box">
    <input
        type="text"
        class="table-search"
        placeholder="Search table data..."
    >
</div>

<br>

        <h2>All Guest Reviews</h2>

        <br>

        <table>

            <thead>

                <tr>
                    <th>ID</th>
                    <th>Guest</th>
                    <th>Overall</th>
                    <th>Cleanliness</th>
                    <th>Service</th>
                    <th>Review</th>
                    <th>Admin Reply</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>

            </thead>

            <tbody>

                <?php

                if ($reviews && $reviews->num_rows > 0) {

                    while ($review = $reviews->fetch_assoc()) {

                        echo "<tr>";

                        echo "<td>" . $review["id"] . "</td>";

                        echo "<td>" . $review["guest_name"] . "</td>";

                        echo "<td>" . $review["overall_rating"] . "/5</td>";

                        echo "<td>" . $review["cleanliness_rating"] . "/5</td>";

                        echo "<td>" . $review["service_rating"] . "/5</td>";

                        echo "<td>" . $review["review_text"] . "</td>";

                        if (!empty($review["admin_reply"])) {
                            echo "<td>" . $review["admin_reply"] . "</td>";
                        } else {
                            echo "<td>No Reply</td>";
                        }

                        echo "<td>" . $review["created_at"] . "</td>";

                        echo "<td>

                            <a class='edit-btn'
                               href='reviews.php?reply=" . $review["id"] . "'>
                               Reply
                            </a>

                            <a class='delete-btn'
                               href='../../controllers/reviewController.php?delete=" . $review["id"] . "'
                               onclick='return confirm(\"Delete this review?\")'>
                               Delete
                            </a>

                        </td>";

                        echo "</tr>";
                    }

                } else {

                    echo "<tr>";
                    echo "<td colspan='9'>No reviews found.</td>";
                    echo "</tr>";
                }

                ?>

            </tbody>

        </table>

    </div>

</div>

<?php

require_once "../../app/layouts/footer.php";

?>