<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'earn');

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$errors = [];
$referrer_name = 'Unknown'; // Default referrer name

// Check if a referrer name is provided in the URL
if (isset($_GET['referrer']) && !empty(trim($_GET['referrer']))) {
    $referrer_name = htmlspecialchars(trim($_GET['referrer']));
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone_number = trim($_POST['phone_number']);
    $password = trim($_POST['password']);

    // Validate if fields are empty
    if (empty($username) || empty($email) || empty($phone_number) || empty($password)) {
        $errors[] = "All fields are required.";
    } else {
        // Validate phone number format
        if (!preg_match('/^\+[1-9]\d{1,14}$/', $phone_number)) {
            $errors[] = "Invalid phone number format.";
        } else {
            // Check if username, email, or phone number already exists
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ? OR phone_number = ?");
            $stmt->bind_param('sss', $username, $email, $phone_number);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $errors[] = "Username, email, or phone number already exists.";
            } else {
                // Hash the password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Set default account status
                $status = 'Inactive';
                $payment_status = 'Unpaid';

                // Insert new user
                $query = "INSERT INTO users (username, email, phone_number, password, status, payment_status) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('ssssss', $username, $email, $phone_number, $hashed_password, $status, $payment_status);

                if ($stmt->execute()) {
                    header("Location: payment.php");
                    exit();
                } else {
                    $errors[] = "Error: " . $stmt->error;
                }
            }
            $stmt->close();
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register your account for free // Legit-Earn</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css"/>
    <style>
        body {
            background-color: #f7f7f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 10px;
        }
        .form-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            box-sizing: border-box;
        }
        .center {
            text-align: center;
        }
        button.btn-primary {
            background-color: #28a745;
            border-color: #28a745;
        }
        .btn-primary:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
        .color-blue {
            color: #007bff;
        }
        .alert {
            text-align: center;
        }
        @media (max-width: 576px) {
            .form-container {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
<?php include('include/header.php'); ?>

<div class="container form-container">
    <h2 class="text-center">Register</h2>
    <div class="alert alert-success alert-dismissible fade show center" role="alert">
        You are brought by: <b><?php echo $referrer_name; ?></b>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <?php if (!empty($errors)) { ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error) { echo $error . "<br>"; } ?>
        </div>
    <?php } ?>

    <form method="POST" id="registrationForm">
        <div class="form-group">
            <label for="username">Name</label>
            <input type="text" name="username" class="form-control" id="username" placeholder="Enter your name" required>
        </div>
        <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" name="email" class="form-control" id="email" placeholder="Enter your email" required>
        </div>
        <div class="form-group">
            <label for="phone">Phone Number</label>
            <input type="tel" name="phone_number" class="form-control" id="phone" placeholder="Enter your phone number" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" class="form-control" id="password" placeholder="Enter your password" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Register</button>
        <p class="center">Already have an account? <a href="login.php" class="color-blue">Sign In here</a></p>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
<script>
    $(document).ready(function() {
        const input = document.querySelector("#phone");
        const iti = window.intlTelInput(input, {
            initialCountry: "rw", // Set Rwanda as the default country
            geoIpLookup: function(callback) {
                callback('rw'); // Default to Rwanda
            },
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"
        });

        $('#registrationForm').on('submit', function(e) {
            if (!iti.isValidNumber()) {
                e.preventDefault();
                alert('The phone number is not valid for the selected country.');
            } else {
                // Set the full international number for server-side validation
                input.value = iti.getNumber();
            }
        });
    });
</script>

</body>
</html>
