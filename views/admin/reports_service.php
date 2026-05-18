<?php
require_once "../../app/helpers/session.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../../models/reportModel.php";

$summary = getServiceSummary();
$serviceTypes = getServiceTypeSummary();
$maintenance = getMaintenanceSummary();

require_once "../../app/layouts/header.php";
require_once "../../app/layouts/sidebar.php";
require_once "../../app/layouts/navbar.php";
?>

<div class="main-content">

    <div class="dashboard-title">
        <h1>Complaint & Service Trends</h1>
        <p>Service request volume, resolution status, and maintenance issue summary.</p>
    </div>

    <button onclick="window.print()" class="print-btn">Print Report</button>

    <div class="card-container">
        <div class="dashboard-card">
            <h3>Total Requests</h3>
            <h2><?php echo $summary["total_requests"]; ?></h2>
        </div>

        <div class="dashboard-card">
            <h3>Pending</h3>
            <h2><?php echo $summary["pending_requests"]; ?></h2>
        </div>

        <div class="dashboard-card">
            <h3>In Progress</h3>
            <h2><?php echo $summary["progress_requests"]; ?></h2>
        </div>

        <div class="dashboard-card">
            <h3>Completed</h3>
            <h2><?php echo $summary["completed_requests"]; ?></h2>
        </div>
    </div>

    <div class="dashboard-table">
        <h2>Service Request Types</h2>

        <table>
            <thead>
                <tr>
                    <th>Service Type</th>
                    <th>Total Requests</th>
                </tr>
            </thead>

            <tbody>
                <?php while ($row = $serviceTypes->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo ucfirst(str_replace("_", " ", $row["service_type"])); ?></td>
                        <td><?php echo $row["total"]; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <div class="dashboard-table">
        <h2>Maintenance / Complaint Summary</h2>

        <table>
            <thead>
                <tr>
                    <th>Severity</th>
                    <th>Status</th>
                    <th>Total</th>
                </tr>
            </thead>

            <tbody>
                <?php while ($row = $maintenance->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo ucfirst($row["severity"]); ?></td>
                        <td><?php echo ucfirst(str_replace("_", " ", $row["status"])); ?></td>
                        <td><?php echo $row["total"]; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</div>

<?php require_once "../../app/layouts/footer.php"; ?>