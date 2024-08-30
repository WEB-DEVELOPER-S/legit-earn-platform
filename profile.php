<?php
// Start the session
session_start();

$conn = new mysqli ('localhost','root','','earn');

if($conn->connect_error){
    die("connection failed : " . $conn->connect_error);
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

// Get the user's ID from the session
$user_id = $_SESSION['user_id'];

// Prepare a query to fetch user details
$sql = "SELECT username, email, phone_number, balance, status, requested_at FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch user data
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit();
}

// Handle password update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_password'])) {
    $new_password = $_POST['new_password'];
    
    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    
    // Update the user's password in the database
    $update_sql = "UPDATE users SET password = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param('si', $hashed_password, $user_id);
    
    if ($update_stmt->execute()) {
        $success = true;
    } else {
        $success = false;
    }

    $update_stmt->close();
}

// Close the database connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
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
        font-size: 1.2em;
    }

    .header .menu-icon,
    .header .settings-icon {
        cursor: pointer;
    }

    .breadcrumb {
        margin: 10px 20px;
        font-size: 0.9em;
        color: #666;
        text-align: center;
    }

    .breadcrumb a {
        color: #666;
        text-decoration: none;
        margin-right: 5px;
    }

    .breadcrumb a:hover {
        text-decoration: underline;
    }

    .container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
        box-sizing: border-box;
    }

    .profile-box {
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        margin-top: 20px;
    }

    .profile-details div {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        font-size: 16px;
    }

    .profile-details strong {
        color: #333;
        flex: 0 0 120px;
    }

    .status {
        padding: 3px 8px;
        border-radius: 5px;
        color: white;
        font-weight: bold;
    }

    .status.active {
        background-color: #28a745;
    }

    .status.inactive {
        background-color: #dc3545;
    }

    .change-password {
        margin-top: 20px;
    }

    .change-password label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    .change-password input {
        width: calc(100% - 20px);
        padding: 8px;
        margin-bottom: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-sizing: border-box;
    }

    .update-button {
        background-color: #5A3DAA;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 4px;
        cursor: pointer;
    }

    .back-button {
        color: white;
        text-decoration: none;
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

    /* Responsive Design */
    @media (max-width: 768px) {
        .header-title {
            font-size: 1.1em;
        }

        .container {
            width: 90%;
            padding: 15px;
        }

        .profile-details div {
            flex-direction: column;
            align-items: flex-start;
        }

        .profile-details strong {
            flex: 0 0 auto;
        }

        .breadcrumb {
            font-size: 0.8em;
        }
    }

    @media (max-width: 480px) {
        .header {
            padding: 10px;
            flex-direction: column;
            align-items: flex-start;
        }

        .header-title {
            font-size: 1em;
            margin-bottom: 10px;
        }

        .container {
            width: 100%;
            padding: 10px;
        }

        .profile-details div {
            font-size: 14px;
        }

        .update-button {
            width: 100%;
            text-align: center;
        }
    }
</style>

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
    <div class="container">

        <div class="profile-box">
            <h2>PROFILE</h2>
            <div class="profile-details">
                <div><strong>Username</strong> <?php echo htmlspecialchars($user['username']); ?></div>
                <div><strong>Email</strong> <?php echo htmlspecialchars($user['email']); ?></div>
                <div><strong>Phone</strong> <?php echo htmlspecialchars($user['phone_number']); ?></div>
                <div><strong>A/C Balance</strong> <?php echo number_format($user['balance'], 2); ?></div>
                <div><strong>Joined</strong> <?php echo htmlspecialchars($user['requested_at']); ?></div>
                <div><strong>Status</strong> <span class="status <?php echo strtolower($user['status']); ?>"><?php echo htmlspecialchars($user['status']); ?></span></div>
            </div>

            <div class="change-password">
                <form action="" method="post" id="change-password-form">
                    <label for="new-password">Change Password</label>
                    <input type="password" id="new-password" name="new_password" placeholder="New Password" required>
                    <button type="submit" class="update-button">UPDATE</button>
                </form>
            </div>
        </div>
    </div>

    <div class="footer">
    Copyright © 2025 <a href="legit.php">LEGIT💲EARN</a>. Designed by <a href="#">web developers Limited</a> All rights reserved.
    </div>
    <script>
        // Check if there's a success message
        <?php if (isset($success) && $success): ?>
            alert('Password updated successfully.');
        <?php elseif (isset($success) && !$success): ?>
            alert('Password update failed. Please try again.');
        <?php endif; ?>
    </script>
</body>
</html>
