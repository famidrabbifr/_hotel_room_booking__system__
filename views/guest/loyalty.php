<?php

require_once "../../app/helpers/session.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "guest") {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../../models/guestModel.php";

$search = $_GET["search"] ?? "";
$fromDate = $_GET["from_date"] ?? "";
$toDate = $_GET["to_date"] ?? "";

$summary = getGuestLoyaltySummary($_SESSION["user_id"]);

$history = getGuestLoyaltyHistoryFiltered(
    $_SESSION["user_id"],
    $search,
    $fromDate,
    $toDate
);

require_once "../../app/layouts/header.php";
require_once "../../app/layouts/sidebar.php";
require_once "../../app/layouts/navbar.php";

?>

<div class="main-content">

    <div class="dashboard-title">
        <h1>Loyalty Points</h1>
        <p>View earned points, redeemed points and current reward balance.</p>
    </div>

    <div class="card-container">

        <div class="dashboard-card">
            <h3>Total Earned</h3>
            <h2><?php echo $summary["total_earned"]; ?></h2>
        </div>

        <div class="dashboard-card">
            <h3>Total Used</h3>
            <h2><?php echo $summary["total_used"]; ?></h2>
        </div>

        <div class="dashboard-card">
            <h3>Current Balance</h3>
            <h2><?php echo $summary["current_balance"]; ?></h2>
        </div>

    </div>

    <div class="dashboard-table">

        <h2>Search Loyalty History</h2>

        <form method="GET" class="filter-form">

            <input
                type="text"
                name="search"
                placeholder="Search booking ID or room type..."
                value="<?php echo htmlspecialchars($search); ?>"
            >

            <input
                type="date"
                name="from_date"
                value="<?php echo htmlspecialchars($fromDate); ?>"
            >

            <input
                type="date"
                name="to_date"
                value="<?php echo htmlspecialchars($toDate); ?>"
            >

            <button type="submit">Search</button>

            <a href="loyalty.php" class="clear-btn">Clear</a>

        </form>

    </div>

    <div class="dashboard-table">

        <h2>Loyalty Activity</h2>

        <table>
            <thead>
                <tr>
                    <th>Booking</th>
                    <th>Room Type</th>
                    <th>Points Earned</th>
                    <th>Points Used</th>
                    <th>Balance</th>
                    <th>Date</th>
                </tr>
            </thead>

            <tbody>

                <?php if ($history->num_rows > 0) { ?>

                    <?php while ($row = $history->fetch_assoc()) { ?>

                        <tr>
                            <td>
                                <?php
                                if (!empty($row["booking_number"])) {
                                    echo "#B" . $row["booking_number"];
                                } else {
                                    echo "N/A";
                                }
                                ?>
                            </td>

                            <td>
                                <?php
                                if (!empty($row["room_type_name"])) {
                                    echo htmlspecialchars($row["room_type_name"]);
                                } else {
                                    echo "N/A";
                                }
                                ?>
                            </td>

                            <td><?php echo $row["points_earned"]; ?></td>

                            <td><?php echo $row["points_used"]; ?></td>

                            <td><?php echo $row["balance"]; ?></td>

                            <td><?php echo $row["created_at"]; ?></td>
                        </tr>

                    <?php } ?>

                <?php } else { ?>

                    <tr>
                        <td colspan="6">No loyalty history found.</td>
                    </tr>

                <?php } ?>

            </tbody>
        </table>

    </div>

</div>

<?php require_once "../../app/layouts/footer.php"; ?>