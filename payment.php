<?php
session_start();

// Replace these with your actual database connection details
$host = 'localhost';  // Replace with your host
$dbname = 'your_database';  // Replace with your database name
$username = 'your_username';  // Replace with your database username
$password = 'your_password';  // Replace with your database password

// Create connection
$conn = new mysqli('localhost', 'root', '', 'earn');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the request is an AJAX call for activation status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'check_activation') {
    $response = array('active' => false);

    // Assuming the user ID is stored in session after login or registration
    $user_id = $_SESSION['user_id'];  // Replace with actual session variable

    // Query to check if the user's account is active
    $sql = "SELECT active FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($active);
    $stmt->fetch();
    $stmt->close();

    if ($active) {
        $response['active'] = true;
    }

    // Send response back to the client-side as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activate Account</title>
    <link rel="stylesheet" href="paymentform.css">
</head>
<body>
    <div class="container">
        <h1><b>LEGIT 💲 EARN</b></h1>
        <h2><u>Activate Account</u></h2>
        <p style=" color: hsl(49, 100%, 50%);"><b>(MTN) MOMO PAY</b></p>
        <ol>
            <li>Kanda <b>*182*8*1*1095054#</b></li>
            <li>Shyiramo Umubare Wamafaranga (Amount) <b>3500Rwf</b></li>
            <li>COMFIRM NAME: <b>antoinette</b></li>
            <li>Enter Pin</li>
        </ol>

        <p style="color:red">AIRTEL MONEY</p>
        <ol>
            <li>Kanda <b>*500*1*2*0789794991#</b></li>
            <li>Shyiramo Umubare Wamafaranga (Amount)<b>3500Rwf</b></li>
            <li>COMFIRM NAME: <b>antoinette</b></li>
            <li>Enter Pin</li> 
        </ol>
        <p>Send transaction screenshot message to: <b>+250789794991</b></p>
        <p>Send the transaction message/screenshot to WhatsApp number <b>0789794991</b></p>
        <button id="proceedButton">PROCEED</button>
        <div id="notification" style="color: red;"></div>
    </div>

    <script>
        // Function to check account activation status
        function checkActivationStatus() {
            fetch('', {  // Empty action, as PHP and HTML are combined
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'action=check_activation'
            })
            .then(response => response.json())
            .then(data => {
                if (data.active) {
                    // Redirect to dashboard if the account is active
                    window.location.href = 'legit.php';
                } else {
                    // Update notification if account is not active
                    alert("account not active");
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('notification').innerText = 'Account is not active . Please follow instructions to be activated.';
            });
        }

        // Add event listener to the "PROCEED" button
        document.getElementById('proceedButton').addEventListener('click', function() {
            // Periodically check the account status
            document.getElementById('notification').innerText = 'Checking activation status...';
            let checkInterval = setInterval(() => {
                checkActivationStatus();
            }, 100); // Check every 0.1 seconds (100 milliseconds)

            // Optionally, you can clear the interval after a certain time to stop checking
            setTimeout(() => {
                clearInterval(checkInterval);
                document.getElementById('notification').innerText = 'Stopped checking after multiple attempts.';
            }, 60000); // Stop checking after 60 seconds (1 minute)
        });
    </script>
</body>
</html>
