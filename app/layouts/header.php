<?php

$role = $_SESSION["role"] ?? "";

echo '<link rel="stylesheet" href="../../assets/css/dashboard.css?v=1200">';

if ($role == "guest") {
    echo '<link rel="stylesheet" href="../../assets/css/guest.css?v=10">';
} elseif ($role == "receptionist") {
    echo '<link rel="stylesheet" href="../../assets/css/receptionist.css?v=1">';
}

?>