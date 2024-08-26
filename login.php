<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'earn');

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username_or_email = $conn->real_escape_string($_POST['username_or_email']);
    $password = $_POST['password'];

    // Fetch user data based on the username or email
    $query = "SELECT * FROM users WHERE (username = '$username_or_email' OR email = '$username_or_email')";
    $result = $conn->query($query);
    $user = $result->fetch_assoc();

    // Check if the user exists
    if ($user) {
        // Check if the password is correct
        if (password_verify($password, $user['password'])) {
            // Check if the account is active or payment is completed
            if ($user['status'] == 'Inactive' || $user['payment_status'] == 'Unpaid') {
                header("Location: payment.php");
                exit();
            } else {
                $_SESSION['user_id'] = $user['id'];
                header("Location: legit.php");
                exit();
            }
        } else {
            $errors[] = "Invalid password.";
        }
    } else {
        // User does not exist, redirect to registration page
        header("Location: register.php");
        exit();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 10px;
            box-sizing: border-box;
        }
        .login-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            box-sizing: border-box;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .error {
            color: red;
            margin-bottom: 20px;
            text-align: center;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 20px;
            box-sizing: border-box;
        }
        button[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            border: none;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            font-size: 16px;
            margin-top: 10px;
        }
        button[type="submit"]:hover {
            background-color: #218838;
        }
        p {
            text-align: center;
            margin-top: 20px;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        @media (max-width: 600px) {
            .login-container {
                padding: 15px;
            }
            input[type="text"],
            input[type="password"],
            button[type="submit"] {
                padding: 8px;
                font-size: 14px;
            }
            h2 {
                font-size: 20px;
                margin-bottom: 15px;
            }
        }
    </style>
</head>
<body>
    
    <div class="login-container">
    <h1 style="text-align:center;">LEGIT💲EARN</h1>
        <h2><br></h2>
        <?php if (!empty($errors)) { ?>
            <div class="error">
                <?php foreach ($errors as $error) { echo $error . "<br>"; } ?>
            </div>
        <?php } ?>
        <form method="POST" action="">
            <label for="username_or_email">Username or Email:</label><br>
            <input type="text" name="username_or_email" id="username_or_email" required><br>

            <label for="password">Password:</label><br>
            <input type="password" name="password" id="password" required><br>

            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register</a></p>
        <h2><br></h2>
    </div>
</body>
</html>
