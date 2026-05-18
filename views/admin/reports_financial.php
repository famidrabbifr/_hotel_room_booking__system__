<?php
require_once "../../app/helpers/session.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../../models/reportModel.php";

$summary = getFinancialSummary();
$dailyRevenue = getRevenueByDate();
$roomRevenue = getRevenueByRoomType();

require_once "../../app/layouts/header.php";
require_once "../../app/layouts/sidebar.php";
require_once "../../app/layouts/navbar.php";
?>

<div class="main-content">

    <div class="dashboard-title">
        <h1>Financial Reports</h1>
        <p>Total revenue, room revenue, extras revenue, and room type income.</p>
    </div>

    <button onclick="window.print()" class="print-btn">Print Report</button>

    <div class="card-container">
        <div class="dashboard-card">
            <h3>Total Revenue</h3>
            <h2>৳ <?php echo number_format($summary["total_revenue"], 2); ?></h2>
        </div>

        <div class="dashboard-card">
            <h3>Room Revenue</h3>
            <h2>৳ <?php echo number_format($summary["room_revenue"], 2); ?></h2>
        </div>

        <div class="dashboard-card">
            <h3>Extras Revenue</h3>
            <h2>৳ <?php echo number_format($summary["extras_revenue"], 2); ?></h2>
        </div>

        <div class="dashboard-card">
            <h3>Total Discount</h3>
            <h2>৳ <?php echo number_format($summary["discount_total"], 2); ?></h2>
        </div>
    </div>

    <div class="dashboard-table">
        <h2>Daily Revenue</h2>

        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Revenue</th>
                </tr>
            </thead>

            <tbody>
                <?php while ($row = $dailyRevenue->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row["report_date"]; ?></td>
                        <td>৳ <?php echo number_format($row["revenue"], 2); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <div class="dashboard-table">
        <h2>Revenue By Room Type</h2>

        <table>
            <thead>
                <tr>
                    <th>Room Type</th>
                    <th>Revenue</th>
                </tr>
            </thead>

            <tbody>
                <?php while ($row = $roomRevenue->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row["name"]); ?></td>
                        <td>৳ <?php echo number_format($row["revenue"], 2); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</div>

<?php require_once "../../app/layouts/footer.php"; ?>