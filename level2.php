<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'earn');

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Retrieve the logged-in user's ID from the session
$user_id = $_SESSION['user_id'];

// Fetch level 1 referrals to find level 2 referrals
$query_level1 = "SELECT referral_code FROM users WHERE referred_by = (SELECT referral_code FROM users WHERE id = ?)";
$stmt_level1 = $conn->prepare($query_level1);
$stmt_level1->bind_param('i', $user_id);
$stmt_level1->execute();
$result_level1 = $stmt_level1->get_result();

$level1_referrals = [];
while ($row = $result_level1->fetch_assoc()) {
    $level1_referrals[] = $row['referral_code'];
}

// Initialize level 2 referrals array
$users_level2 = [];

// Fetch level 2 referrals using the level 1 referrals
if (!empty($level1_referrals)) {
    $placeholders = implode(',', array_fill(0, count($level1_referrals), '?'));
    $query_level2 = "SELECT id, username, status, requested_at, phone_number FROM users WHERE referred_by IN ($placeholders)";
    $stmt_level2 = $conn->prepare($query_level2);
    $stmt_level2->bind_param(str_repeat('s', count($level1_referrals)), ...$level1_referrals);
    $stmt_level2->execute();
    $result_level2 = $stmt_level2->get_result();

    while ($row = $result_level2->fetch_assoc()) {
        $users_level2[] = $row;
    }
    $stmt_level2->close();
}

$total_earnings_level2 = count($users_level2) * 500; // Calculate total earnings for level 2 referrals

$stmt_level1->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Level 2 Referral Earnings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <style>
        *{
            overflow:hidden;
        }
     /* General Reset and Body Styles */
     body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        body {
            background-color: #f4f6f9; /* Background color of the dashboard */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            margin-top: 20px;
            background-color: skyblue; /* Background color for the container */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h4 {
            color: #1a1a1a; /* Darker color for the text */
            font-weight: bold;
            margin-bottom: 20px;
        }

        /* Table header styling */
        .table thead th {
            background-color: #f1f1f1; /* Light grey background for table headers */
            color: #333; /* Dark grey text color */
            text-align: center;
            vertical-align: middle;
        }

        /* Table body styling */
        .table tbody td {
            text-align: center;
            vertical-align: middle;
        }

        /* Button styling */
        .dt-buttons .btn {
            background-color: #6610f2; /* Purple color for the buttons */
            color: white;
            border: none;
            margin-right: 5px;
            border-radius: 4px;
        }

        .dt-buttons .btn:hover {
            background-color: #540cbe; /* Darker purple for hover effect */
        }

        /* Status badge styling */
        .badge {
            font-size: 10px;
            padding: 5px 8px;
        }

        .badge.bg-success {
            background-color: #28a745 !important; /* Green for ACTIVE status */
        }

        .badge.bg-danger {
            background-color: #dc3545 !important; /* Red for INACTIVE status */
        }

        /* DataTables search box */
        .dataTables_wrapper .dataTables_filter input {
            width: 200px;
            margin-left: 0.5em;
        }

        .dataTables_wrapper .dataTables_filter label {
            font-weight: normal;
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
         /* Custom responsive styles */
         @media (max-width: 576px) {
            .container {
                padding: 0 10px;
            }
            h4 {
                font-size: 18px;
                text-align: center;
                margin-bottom: 15px;
            }
            table.dataTable {
                width: 100%;
                margin: 0 auto;
            }
            .dataTables_wrapper .dataTables_filter {
                float: left;
                text-align: left;
                width: 100%;
                margin-bottom: 10px;
            }
            .dataTables_wrapper .dataTables_length {
                float: left;
                text-align: left;
                width: 100%;
                margin-bottom: 10px;
            }
            .dataTables_wrapper .dataTables_paginate {
                float: left;
                width: 100%;
                text-align: center;
                margin-top: 10px;
            }
            .dataTables_wrapper .dataTables_info {
                float: left;
                width: 100%;
                text-align: left;
                margin-top: 10px;
                margin-bottom: 10px;
            }
            .table-responsive {
                overflow-x: auto;
            }
        }
        
        
    </style>
</head>
<body>
     <!-- Header Section -->
     <div class="header">
    <a href="javascript:history.back()" class="back-button">☰</a>
        <div class="header-title">
            
            LEGIT💲EARN
        </div>
        <div class="settings-icon">&nbsp;</div>
    </div>

    <!-- Breadcrumb Navigation -->
    <main class="breadcrumb">
        <a href="legit.php" style="font-weight:bold;">Home</a> / <a href="#">proceed</a>
    </main>

    <!-- Main Content Container -->
<div class="container mt-5">
    <h4>Total Level 2 Earnings: RWF <?php echo number_format($total_earnings_level2); ?></h4>
    <table id="userTable" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>SN</th>
                <th>USERNAME</th>
                <th>STATUS</th>
                <th>DATE JOINED</th>
                <th>MOBILE</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users_level2 as $index => $user): ?>
                <tr>
                    <td><?php echo sprintf('%02d', $index + 1); ?></td>
                    <td>- <?php echo htmlspecialchars($user['username']); ?></td>
                    <td>
                        <?php if (strtolower($user['status']) == 'active'): ?>
                            <span class="badge bg-success">ACTIVE</span>
                        <?php else: ?>
                            <span class="badge bg-danger">INACTIVE</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($user['requested_at']); ?></td>
                    <td><?php echo htmlspecialchars($user['phone_number']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
 <!-- Footer Section -->
 <div class="footer">
        Copyright © 2025 <a href="legit.php">LEGIT💲EARN</a>. Designed by <a href="#">web developers Limited</a> All rights reserved.
    </div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#userTable').DataTable({
            "lengthMenu": [10, 25, 50, 100],
            "pageLength": 10,
            "searching": true,
            "paging": true,
            "info": true
        });
    });
</script>
</body>
</html>
