

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>SwiftEarn - Claim Bonus</title>
    <style>
  





    </style>




<?php
// Start the session
session_start();

// Include the database connection
include('db_connect.php');

// Check if user_id is set in the session
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not set
    header('Location: login.php'); // Ensure you have a login.php file
    exit;
}

$user_id = $_SESSION['user_id']; // Now safe to access

// Function to check referrals
function check_referrals($user_id, $level, $conn) {
    // Query to count referrals for the given level
    $query = "SELECT COUNT(*) AS referral_count FROM referrals WHERE referrer_id = ? AND level = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $user_id, $level);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['referral_count'];
}

// Initialize message
$message = "";

// Handle form submission to claim the bonus
if (isset($_POST['claim'])) {
    // Check referrals at different levels
    $level1_referrals = check_referrals($user_id, 1, $conn);
    $level2_referrals = check_referrals($user_id, 2, $conn);

    // Check if the user has sufficient referrals
    if ($level1_referrals >= 10 || $level2_referrals >= 10) {
        // Logic for claiming the bonus
        $bonus_amount = 2000; // Set the bonus amount to be claimed
        $query = "UPDATE users SET bonus_claimed = 1, account_balance = account_balance + ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('di', $bonus_amount, $user_id);
        $stmt->execute();

        $message = "Bonus claimed successfully!";
    } else {
        $message = "You need at least 10 referrals at level 1 or 2 to claim the bonus.";
    }
}

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
    font-family: Arial, sans-serif;
    background-color: #f3f4f6;
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
    margin: 50px auto;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 20px;
    text-align: center;
}

        .claim-button {
    background-color: #5e35b1;
    color: #fff;
    border: none;
    padding: 10px 20px;
    font-size: 16px;
    border-radius: 5px;
    cursor: pointer;
}

.claim-button:hover {
    background-color: #7b48c5;
}

.alert {
    padding: 10px;
    color: #856404;
    background-color: #fff3cd;
    border-radius: 5px;
    margin-top: 10px;
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
            
            LEGIT💲EARN
        </div>
        <div class="settings-icon">&nbsp;</div>
    </div>

    <!-- Breadcrumb Navigation -->
    <main class="breadcrumb">
        <a href="legit.php" style="font-weight:bold;">Home</a> / <a href="#">proceed</a>
    </main>

    <!-- Main Content Container -->
    <div class="container">
    <div class="content">
            <h3>Claim Bonus</h3>
            <p>CLAIM YOUR <b>RWF 2000</b> BONUS</p>

            <form method="POST" action="claim_bonus.php">
                <button type="submit" name="claim" class="claim-button">Claim Bonus</button>
            </form>
        </div>
    </div>

    <!-- JavaScript for alert notification -->
    <script>
        <?php if (!empty($message)): ?>
            alert('<?php echo $message; ?>');
        <?php endif; ?>
    </script>

    <!-- Footer Section -->
    <div class="footer">
        Copyright © 2025 <a href="legit.php">LEGIT💲EARN</a>. Designed by <a href="#">web developers Limited</a> All rights reserved.
    </div>
</body>
</html>
