<?php

require_once "../app/helpers/session.php";
require_once "../models/announcementModel.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: ../views/auth/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $action = $_POST["action"];
    $title = trim($_POST["title"]);
    $message = trim($_POST["message"]);
    $status = $_POST["is_active"];

    if (empty($title) || empty($message)) {
        $_SESSION["error"] = "Title and message are required";
        header("Location: ../views/admin/announcements.php");
        exit();
    }

    if ($action == "add_announcement") {

        if (addAnnouncement($title, $message, $_SESSION["user_id"], $status)) {
            $_SESSION["success"] = "Announcement added successfully";
        } else {
            $_SESSION["error"] = "Failed to add announcement";
        }

        header("Location: ../views/admin/announcements.php");
        exit();
    }

    if ($action == "update_announcement") {

        $id = $_POST["id"];

        if (updateAnnouncement($id, $title, $message, $status)) {
            $_SESSION["success"] = "Announcement updated successfully";
        } else {
            $_SESSION["error"] = "Failed to update announcement";
        }

        header("Location: ../views/admin/announcements.php");
        exit();
    }
}

if (isset($_GET["delete"])) {

    $id = $_GET["delete"];

    if (deleteAnnouncement($id)) {
        $_SESSION["success"] = "Announcement deleted successfully";
    } else {
        $_SESSION["error"] = "Cannot delete announcement";
    }

    header("Location: ../views/admin/announcements.php");
    exit();
}

header("Location: ../views/admin/dashboard.php");
exit();

?>