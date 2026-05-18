<?php

require_once "../../app/helpers/session.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../../models/pricingModel.php";
require_once "../../models/roomModel.php";

$pricingList = getAllPricing();
$roomTypes = getAllRoomTypes();

$editData = null;

if (isset($_GET["edit"])) {
    $editData = getPricingById($_GET["edit"]);
}

require_once "../../app/layouts/header.php";
require_once "../../app/layouts/sidebar.php";
require_once "../../app/layouts/navbar.php";

?>

<div class="main-content">

    <div class="dashboard-title">
        <h1>Seasonal Pricing</h1>
        <p>Manage holiday offers and date-based room pricing.</p>
    </div>

    <?php
    if (isset($_SESSION["success"])) {
        echo "<div class='success-message'>" . $_SESSION["success"] . "</div>";
        unset($_SESSION["success"]);
    }

    if (isset($_SESSION["error"])) {
        echo "<div class='error'>" . $_SESSION["error"] . "</div>";
        unset($_SESSION["error"]);
    }
    ?>

    <div class="form-box">
        <h2><?php echo $editData ? "Edit Pricing Rule" : "Add New Pricing Rule"; ?></h2>

        <form action="../../controllers/pricingController.php" method="POST">

            <input type="hidden" name="action" value="<?php echo $editData ? "update_pricing" : "add_pricing"; ?>">

            <?php if ($editData) { ?>
                <input type="hidden" name="id" value="<?php echo $editData["id"]; ?>">
            <?php } ?>

            <div class="form-row">

                <div class="form-group">
                    <label>Room Type</label>
                    <select name="room_type_id" required>
                        <option value="">Select Room Type</option>

                        <?php
                        if ($roomTypes && $roomTypes->num_rows > 0) {
                            while ($type = $roomTypes->fetch_assoc()) {
                                $selected = "";

                                if ($editData && $editData["room_type_id"] == $type["id"]) {
                                    $selected = "selected";
                                }

                                echo "<option value='" . $type["id"] . "' $selected>";
                                echo $type["name"];
                                echo "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Pricing Label</label>
                    <input type="text" name="label" required placeholder="Eid Holiday Offer"
                           value="<?php echo $editData ? $editData["label"] : ""; ?>">
                </div>

            </div>

            <div class="form-row">

                <div class="form-group">
                    <label>Start Date</label>
                    <input type="date" name="start_date" required
                           value="<?php echo $editData ? $editData["start_date"] : ""; ?>">
                </div>

                <div class="form-group">
                    <label>End Date</label>
                    <input type="date" name="end_date" required
                           value="<?php echo $editData ? $editData["end_date"] : ""; ?>">
                </div>

            </div>

            <div class="form-row">

                <div class="form-group">
                    <label>Price Per Night</label>
                    <input type="number" step="0.01" name="price_per_night" required
                           value="<?php echo $editData ? $editData["price_per_night"] : ""; ?>">
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="is_active" required>
                        <option value="1" <?php echo ($editData && $editData["is_active"] == 1) ? "selected" : ""; ?>>
                            Active
                        </option>

                        <option value="0" <?php echo ($editData && $editData["is_active"] == 0) ? "selected" : ""; ?>>
                            Inactive
                        </option>
                    </select>
                </div>

            </div>

            <button type="submit">
                <?php echo $editData ? "Update Pricing" : "Add Pricing"; ?>
            </button>

            <?php if ($editData) { ?>
                <a href="pricing.php" class="cancel-link">Cancel Edit</a>
            <?php } ?>

        </form>
    </div>

    <div class="dashboard-table">
	<div class="table-search-box">
    <input
        type="text"
        class="table-search"
        placeholder="Search table data..."
    >
</div>

<br>

        <h2>All Pricing Rules</h2>
        <br>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Room Type</th>
                    <th>Label</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php
                if ($pricingList && $pricingList->num_rows > 0) {

                    while ($pricing = $pricingList->fetch_assoc()) {

                        echo "<tr>";

                        echo "<td>" . $pricing["id"] . "</td>";
                        echo "<td>" . $pricing["room_type"] . "</td>";
                        echo "<td>" . $pricing["label"] . "</td>";
                        echo "<td>" . $pricing["start_date"] . "</td>";
                        echo "<td>" . $pricing["end_date"] . "</td>";
                        echo "<td>BDT " . $pricing["price_per_night"] . "</td>";

                        echo "<td>";
                       if ($pricing["is_active"] == 1) {

    echo "<span class='status-badge status-active'>
            Active
          </span>";

} else {

    echo "<span class='status-badge status-inactive'>
            Inactive
          </span>";
}
                        echo "</td>";

                        echo "<td>
                                <a class='edit-btn' href='pricing.php?edit=" . $pricing["id"] . "'>Edit</a>
                                <a class='delete-btn'
                                   href='../../controllers/pricingController.php?delete=" . $pricing["id"] . "'
                                   onclick='return confirm(\"Delete this pricing rule?\")'>
                                   Delete
                                </a>
                              </td>";

                        echo "</tr>";
                    }

                } else {
                    echo "<tr><td colspan='8'>No pricing rules found.</td></tr>";
                }
                ?>
            </tbody>
        </table>

    </div>

</div>

<?php require_once "../../app/layouts/footer.php"; ?>S