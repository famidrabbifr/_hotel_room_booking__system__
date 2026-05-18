<?php
require_once "../../app/helpers/session.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../../models/reportModel.php";

$summary = getLoyaltySummary();
$history = getLoyaltyHistory();

require_once "../../app/layouts/header.php";
require_once "../../app/layouts/sidebar.php";
require_once "../../app/layouts/navbar.php";
?>

<div class="main-content">

    <div class="dashboard-title">
        <h1>Loyalty Points Report</h1>
        <p>Total points issued, redeemed, and guest loyalty activity.</p>
    </div>

    <button onclick="window.print()" class="print-btn">Print Report</button>

    <div class="card-container">
        <div class="dashboard-card">
            <h3>Total Points Issued</h3>
            <h2><?php echo $summary["total_issued"]; ?></h2>
        </div>

        <div class="dashboard-card">
            <h3>Total Points Redeemed</h3>
            <h2><?php echo $summary["total_redeemed"]; ?></h2>
        </div>
    </div>

    <div class="dashboard-table">
        <h2>Loyalty Activity History</h2>

        <table>
            <thead>
                <tr>
                    <th>Guest</th>
                    <th>Points Earned</th>
                    <th>Points Used</th>
                    <th>Balance</th>
                    <th>Date</th>
                </tr>
            </thead>

            <tbody>
                <?php while ($row = $history->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row["name"]); ?></td>
                        <td><?php echo $row["points_earned"]; ?></td>
                        <td><?php echo $row["points_used"]; ?></td>
                        <td><?php echo $row["balance"]; ?></td>
                        <td><?php echo $row["created_at"]; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</div>

<?php require_once "../../app/layouts/footer.php"; ?>