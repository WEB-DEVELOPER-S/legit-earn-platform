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
        *{
            overflow:hidden;
        }
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #5A3DAA; /* Matching the color from your image */
            padding: 20px;
            color: white;
            text-align: center;
            position: relative;
        }

        .breadcrumb {
            font-size: 14px;
            color: #e0e0e0;
            margin-top: 5px;
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
        }

        .update-button {
            background-color: #5A3DAA;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .profile-details div {
                flex-direction: column;
                align-items: flex-start;
            }

            .profile-details strong {
                flex: none;
                margin-bottom: 5px;
            }

            .change-password input {
                width: 100%;
            }
        }

        /* Footer Styles */
        .footer {
            background-color: #5A3DAA;
            color: white;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            width: 100%;
            bottom: 0;
        }

        .footer p {
            margin: 0;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>LEGIT💲EARN</h1>
            <div class="breadcrumb">Home / proceed</div>
        </div>

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
        <p>Copyright © 2025 LEGIT 💲 EARN. Designed by Web Developers Limited. All rights reserved.</p>
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
