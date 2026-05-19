<?php

require_once "../../app/helpers/session.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "housekeeping") {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../../models/housekeepingModel.php";

$history = getHousekeepingHistory();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Cleaning History</title>
    <link rel="stylesheet" href="../../assets/css/housekeeping.css">
</head>
<body>

<div class="housekeeping-wrapper">

    <div class="housekeeping-sidebar">
        <div class="housekeeping-logo">
            <h2>GPH</h2>
            <p>Housekeeping Panel</p>
        </div>

        <ul class="housekeeping-menu">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="room_status.php">Room Status Board</a></li>
            <li><a href="tasks.php">Housekeeping Tasks</a></li>
            <li><a href="create_task.php">Create Task</a></li>
            <li><a href="maintenance.php">Maintenance Reports</a></li>
            <li><a href="create_maintenance.php">Log Maintenance</a></li>
            <li><a href="report.php">Daily Report</a></li>
            <li><a href="history.php" class="active">Cleaning History</a></li>
            <li><a href="../../logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="housekeeping-main">

        <div class="housekeeping-top">
            <h1>Cleaning History</h1>
            <p>View completed task log per room with cleaner and completion time.</p>
        </div>

        <div class="housekeeping-table">

            <h2>Completed Task Log</h2>

            <input type="text" id="historySearch" class="search-box" placeholder="Search room, cleaner or notes...">

            <table>
                <thead>
                    <tr>
                        <th>Task ID</th>
                        <th>Room</th>
                        <th>Task Type</th>
                        <th>Cleaner</th>
                        <th>Notes</th>
                        <th>Completed At</th>
                    </tr>
                </thead>

                <tbody id="historyTableBody">
                    <?php if ($history && $history->num_rows > 0) { ?>
                        <?php while ($row = $history->fetch_assoc()) { ?>
                            <tr>
                                <td>#T<?php echo $row["id"]; ?></td>
                                <td>Room <?php echo htmlspecialchars($row["room_number"]); ?></td>
                                <td><?php echo ucfirst(str_replace("_", " ", htmlspecialchars($row["task_type"]))); ?></td>
                                <td><?php echo htmlspecialchars($row["cleaner_name"] ?? "Housekeeping"); ?></td>
                                <td><?php echo htmlspecialchars($row["notes"] ?? "N/A"); ?></td>
                                <td><?php echo htmlspecialchars($row["completed_at"]); ?></td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr><td colspan="6">No completed task history found.</td></tr>
                    <?php } ?>
                </tbody>
            </table>

        </div>

    </div>

</div>

<script src="../../assets/js/housekeeping.js"></script>
</body>
</html>
