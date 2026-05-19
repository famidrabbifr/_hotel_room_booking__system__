<?php

require_once "../../app/helpers/session.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "guest") {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../../models/guestModel.php";

$guest = getGuestProfile($_SESSION["user_id"]);
$loyaltyHistory = getGuestLoyaltyHistory($_SESSION["user_id"]);

require_once "../../app/layouts/header.php";
require_once "../../app/layouts/sidebar.php";
require_once "../../app/layouts/navbar.php";

?>

<div class="main-content">

    <div class="dashboard-title">
        <h1>My Profile</h1>
        <p>Update your personal information and view loyalty history.</p>
    </div>


    <?php if (isset($_GET["success"])) { ?>
        <div class="success-message">
            Profile updated successfully.
        </div>
    <?php } ?>

    <?php if (isset($_GET["error"])) { ?>
        <div class="error-message">
            <?php
            if ($_GET["error"] == "password_mismatch") {
                echo "Password and confirm password do not match.";
            } elseif ($_GET["error"] == "password_short") {
                echo "Password must be at least 6 characters.";
            }
            ?>
        </div>
    <?php } ?>

    <div class="form-box">
        <h2>Update Personal Information</h2>

        <form method="POST" action="../../controllers/guestController.php" enctype="multipart/form-data">

            <input type="hidden" name="action" value="update_profile">

            <div class="form-row">

                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($guest["name"]); ?>" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" value="<?php echo htmlspecialchars($guest["email"]); ?>" readonly>
                </div>

                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" value="<?php echo htmlspecialchars($guest["phone"]); ?>" required>
                </div>

                <div class="form-group">
                    <label>Nationality</label>
                    <input type="text" name="nationality" value="<?php echo htmlspecialchars($guest["nationality"]); ?>" required>
                </div>

                <div class="form-group">
                    <label>ID Number</label>
                    <input type="text" name="id_number" value="<?php echo htmlspecialchars($guest["id_number"]); ?>" required>
                </div>

                <div class="form-group">
                    <label>Profile Picture</label>
                    <input type="file" name="profile_pic">
                </div>

            </div>

            <button type="submit" class="print-btn">Update Profile</button>

        </form>
    </div>

    <div class="form-box">
        <h2>Change Password</h2>

        <form method="POST" action="../../controllers/guestController.php">

            <input type="hidden" name="action" value="change_password">

            <div class="form-row">

                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" name="new_password" required>
                </div>

                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" required>
                </div>

            </div>

            <button type="submit" class="print-btn">Change Password</button>

        </form>
    </div>

    <div class="dashboard-table">
        <h2>Loyalty Points History</h2>

        <table>
            <thead>
                <tr>
                    <th>Points Earned</th>
                    <th>Points Used</th>
                    <th>Balance</th>
                    <th>Date</th>
                </tr>
            </thead>

            <tbody>
                <?php if ($loyaltyHistory->num_rows > 0) { ?>
                    <?php while ($row = $loyaltyHistory->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row["points_earned"]; ?></td>
                            <td><?php echo $row["points_used"]; ?></td>
                            <td><?php echo $row["balance"]; ?></td>
                            <td><?php echo $row["created_at"]; ?></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="4">No loyalty history found.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</div>

<?php require_once "../../app/layouts/footer.php"; ?>