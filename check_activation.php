<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'earn');

// Check if the user is logged in, if not respond with inactive status
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['active' => false]);
    exit();
}

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Check user's activation status from the database
$query = "SELECT status, payment_status FROM users WHERE id = '$user_id'";
$result = $conn->query($query);
$user = $result->fetch_assoc();

// Determine if the account is active and payment is completed
if ($user && $user['status'] == 'Active' && $user['payment_status'] == 'Paid') {
    echo json_encode(['active' => true]);
} else {
    echo json_encode(['active' => false]);
}

$conn->close();
?>
