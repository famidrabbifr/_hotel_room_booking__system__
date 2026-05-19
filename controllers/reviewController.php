<?php

require_once "../app/helpers/session.php";
require_once "../models/reviewModel.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: ../views/auth/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = $_POST["id"];
    $reply = trim($_POST["admin_reply"]);

    if (updateReviewReply($id, $reply)) {

        $_SESSION["success"] = "Reply updated successfully";

    } else {

        $_SESSION["error"] = "Failed to update reply";
    }

    header("Location: ../views/admin/reviews.php");
    exit();
}

if (isset($_GET["delete"])) {

    $id = $_GET["delete"];

    if (deleteReview($id)) {

        $_SESSION["success"] = "Review deleted successfully";

    } else {

        $_SESSION["error"] = "Cannot delete review";
    }

    header("Location: ../views/admin/reviews.php");
    exit();
}

header("Location: ../views/admin/dashboard.php");
exit();

?>