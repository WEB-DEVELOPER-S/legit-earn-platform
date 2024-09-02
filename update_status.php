<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $withdrawal_id = $_POST['id'];
    $status = $_POST['status'];

    $sql = "UPDATE withdrawals SET status = :status WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['status' => $status, 'id' => $withdrawal_id]);

    header('Location: admin_panel.php');
    exit();
}
?>
