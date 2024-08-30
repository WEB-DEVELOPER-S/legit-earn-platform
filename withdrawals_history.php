<?php
include 'db.php';

// Fetch user ID dynamically, for example, through session or request.
$user_id = 1; // This should be dynamic

$sql = "SELECT * FROM withdrawals WHERE user_id = :user_id ORDER BY time DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $user_id]);
$withdrawals = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Withdrawal History - SwiftEarn</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* General Reset and Body Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        /* Header Section */
        .header {
            background-color: #5a2fcf;
            color: #fff;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header img {
            height: 40px;
        }

        .header-title {
            display: flex;
            align-items: center;
            font-size: 1.5em;
        }

        .header .menu-icon,
        .header .settings-icon {
            cursor: pointer;
        }

        .breadcrumb {
            margin: 10px 20px;
            font-size: 0.9em;
            color: #666;
            text-align:center;
        
        }

        .breadcrumb a {
            color: #666;
            text-decoration: none;
            margin-right: 5px;
         
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        /* Container for Main Content */
        .container {
            width: 90%;
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #3a3a3a;
            font-size: 1.5em;
            margin-bottom: 20px;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th, table td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #f8f8f8;
            font-weight: bold;
        }

        .status {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 12px;
            color: #fff;
            font-size: 0.9em;
        }

        .status.paid {
            background-color: #007bff;
        }

        .status.pending {
            background-color: #ffc107;
        }

        .status.failed {
            background-color: #dc3545;
        }
        .back-button{
            color:white;
            text-decoration:none;
        }

        /* Footer Styling */
        .footer {
            text-align: center;
            font-size: 0.8em;
            color: #666;
            padding: 20px 0;
            background-color: #f8f8f8;
            margin-top: 20px;
        }

        .footer a {
            color: #5a2fcf;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        @media (max-width: 600px) {
            .container {
                width: 95%;
                margin: 10px auto;
                padding: 15px;
            }

            table th, table td {
                padding: 10px;
                font-size: 0.9em;
            }
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <div class="header">
    <a href="javascript:history.back()" class="back-button">☰</a>
        <div class="header-title">
            
            SWIFT💲EARN
        </div>
        <div class="settings-icon">&nbsp;</div>
    </div>

    <!-- Breadcrumb Navigation -->
    <main class="breadcrumb">
        <a href="legit.php" style="font-weight:bold;">Home</a> / <a href="#">proceed</a>
    </main>

    <!-- Main Content Container -->
    <div class="container">
        <h1>Withdrawal History</h1>
        <table>
            <thead>
                <tr>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($withdrawals as $withdrawal): ?>
                    <tr>
                        <td>RWF <?= htmlspecialchars($withdrawal['payment']) ?></td>
                        <td><span class="status <?= strtolower($withdrawal['status']) ?>"><?= htmlspecialchars($withdrawal['status']) ?></span></td>
                        <td><?= htmlspecialchars($withdrawal['time']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Footer Section -->
    <div class="footer">
        Copyright © 2025 <a href="legit.php">LEGIT💲EARN</a>. Designed by <a href="#">web developers Limited</a> All rights reserved.
    </div>
</body>
</html>
