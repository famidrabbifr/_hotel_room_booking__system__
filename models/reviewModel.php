<?php

require_once __DIR__ . "/../config/database.php";

function getAllReviews()
{
    global $conn;

    $sql = "SELECT
                reviews.id,
                reviews.overall_rating,
                reviews.cleanliness_rating,
                reviews.service_rating,
                reviews.review_text,
                reviews.admin_reply,
                reviews.created_at,
                users.name AS guest_name
            FROM reviews
            INNER JOIN users
            ON reviews.guest_id = users.id
            ORDER BY reviews.id DESC";

    return $conn->query($sql);
}

function getReviewById($id)
{
    global $conn;

    $sql = "SELECT * FROM reviews WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}

function updateReviewReply($id, $reply)
{
    global $conn;

    $sql = "UPDATE reviews
            SET admin_reply = ?
            WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $reply, $id);

    try {
        return $stmt->execute();
    } catch (mysqli_sql_exception $e) {
        return false;
    }
}

function deleteReview($id)
{
    global $conn;

    $sql = "DELETE FROM reviews WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    try {
        return $stmt->execute();
    } catch (mysqli_sql_exception $e) {
        return false;
    }
}

?>