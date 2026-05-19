<?php

require_once "../app/helpers/session.php";
require_once "../models/pricingModel.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: ../views/auth/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $action = $_POST["action"];

    $roomTypeId = $_POST["room_type_id"];
    $label = trim($_POST["label"]);
    $startDate = $_POST["start_date"];
    $endDate = $_POST["end_date"];
    $price = trim($_POST["price_per_night"]);
    $status = $_POST["is_active"];

    if (
        empty($roomTypeId) ||
        empty($label) ||
        empty($startDate) ||
        empty($endDate) ||
        empty($price)
    ) {

        $_SESSION["error"] = "Required fields cannot be empty";

        header("Location: ../views/admin/pricing.php");
        exit();
    }

    if ($action == "add_pricing") {

        if (
            addPricing(
                $roomTypeId,
                $label,
                $startDate,
                $endDate,
                $price,
                $status
            )
        ) {

            $_SESSION["success"] = "Pricing added successfully";

        } else {

            $_SESSION["error"] = "Failed to add pricing";
        }

        header("Location: ../views/admin/pricing.php");
        exit();
    }

    if ($action == "update_pricing") {

        $id = $_POST["id"];

        if (
            updatePricing(
                $id,
                $roomTypeId,
                $label,
                $startDate,
                $endDate,
                $price,
                $status
            )
        ) {

            $_SESSION["success"] = "Pricing updated successfully";

        } else {

            $_SESSION["error"] = "Failed to update pricing";
        }

        header("Location: ../views/admin/pricing.php");
        exit();
    }
}

if (isset($_GET["delete"])) {

    $id = $_GET["delete"];

    if (deletePricing($id)) {

        $_SESSION["success"] = "Pricing deleted successfully";

    } else {

        $_SESSION["error"] = "Cannot delete pricing";
    }

    header("Location: ../views/admin/pricing.php");
    exit();
}

header("Location: ../views/admin/dashboard.php");
exit();

?>