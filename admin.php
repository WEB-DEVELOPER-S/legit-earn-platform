<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "earn";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Update status or payment status in users table
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_payment_status'])) {
        $user_id = $_POST['user_id'];
        $payment_status = $_POST['payment_status'];

        // Update payment status
        $sql_update_payment = "UPDATE users SET payment_status='$payment_status' WHERE id=$user_id";
        $conn->query($sql_update_payment);

        // Automatically update user status based on payment status
        $status = ($payment_status == 'Paid') ? 'Active' : 'Inactive';
        $sql_update_status = "UPDATE users SET status='$status' WHERE id=$user_id";
        $conn->query($sql_update_status);

        header("Location: admin.php");
    }

    if (isset($_POST['update_withdrawal_status'])) {
        $withdrawal_id = $_POST['withdrawal_id'];
        $withdrawal_status = $_POST['withdrawal_status'];

        // Update withdrawal request status
        $sql_update_withdrawal = "UPDATE withdrawals SET status='$withdrawal_status' WHERE id=$withdrawal_id";
        $conn->query($sql_update_withdrawal);

        // If the status is "Paid", move the request to withdrawal history
        if ($withdrawal_status == 'Paid') {
            $sql_move_to_history = "INSERT INTO withdrawals (user_id, payment, status)
                                    SELECT user_id, payment, status FROM withdrawals WHERE id=$withdrawal_id";
            $conn->query($sql_move_to_history);

            // Delete the request from the original table after moving to history
            $sql_delete_withdrawal = "DELETE FROM withdrawals WHERE id=$withdrawal_id";
            $conn->query($sql_delete_withdrawal);
        }

        header("Location: admin.php");
    }

    if (isset($_POST['delete_user'])) {
        $user_id = $_POST['user_id'];
        
        // Delete user
        $sql_delete_user = "DELETE FROM users WHERE id=$user_id";
        $conn->query($sql_delete_user);
        header("Location: admin.php");
    }

    if (isset($_POST['delete_withdrawal'])) {
        $withdrawal_id = $_POST['withdrawal_id'];

        // Delete withdrawal request
        $sql_delete_withdrawal = "DELETE FROM withdrawals WHERE id=$withdrawal_id";
        $conn->query($sql_delete_withdrawal);
        header("Location: admin.php");
    }
}

// Search functionality
$search_query = '';
if (isset($_GET['search_query'])) {
    $search_query = $_GET['search_query'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }
        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
        }
        header {
            background: #35424a;
            color: #ffffff;
            padding-top: 30px;
            min-height: 70px;
            border-bottom: white 3px solid;
        }
        header h1 {
            margin: 0;
            padding: 0;
            text-align: center;
            color: skyblue;
        }
        .main {
            padding: 20px;
            background: skyblue;
            margin: 20px 0;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            border: 3px dotted black;
            margin-left:120px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        button.update-btn, button.delete-btn {
            border: none;
            padding: 5px 10px;
            color: #fff;
            border-radius: 3px;
            cursor: pointer;
        }
        .btn-green {
            background-color: #4CAF50;
        }
        .btn-red {
            background-color: #f44336;
        }
        .btn-delete {
            background-color: #f44336;
        }
        .search-form {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 10px;
        }
        .search-form input[type="text"] {
            padding: 5px;
            font-size: 16px;
            width: 200px;
            border-radius: 5px 0 0 5px;
            border: 1px solid #ddd;
        }
        .search-form button {
            padding: 5px 10px;
            font-size: 16px;
            border-radius: 0 5px 5px 0;
            border: 1px solid #ddd;
            background-color: #35424a;
            color: #fff;
            cursor: pointer;
        }
        .delete-btn{
            background-color:red;
        }
        .btn-yellow {
    background-color: black; /* Yellow color for Pending status */
    color: #000; /* Black text for better contrast ok  */
       }

    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>Admin Dashboard</h1>
        </div>
    </header>

    <div class="container main">
        <form method="GET" action="" class="search-form">
            <input type="text" name="search_query" value="<?= htmlentities($search_query) ?>" placeholder="Search by username or email...">
            <button type="submit">Search</button>
        </form>

        <h2>Users</h2>
        <table>
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Status</th>
                <th>Payment Status</th>
                <th>Actions</th>
            </tr>
            <?php
            // Fetch users data
            $sql_users = "SELECT * FROM users";
            if ($search_query) {
                $sql_users .= " WHERE username LIKE '%$search_query%' OR email LIKE '%$search_query%'";
            }
            $result_users = $conn->query($sql_users);

            if ($result_users->num_rows > 0) {
                while($row = $result_users->fetch_assoc()) {
                    $status_class = ($row["status"] == 'Active') ? 'btn-green' : 'btn-red';
                    echo "<tr>
                            <td>" . $row["id"]. "</td>
                            <td>" . $row["username"]. "</td>
                            <td>" . $row["email"]. "</td>
                            <td>" . $row["phone_number"]. "</td>
                            <td>
                                <button class='update-btn $status_class'>" . $row["status"] . "</button>
                            </td>
                            <td>
                                <form method='POST' action=''>
                                    <select name='payment_status'>
                                        <option " . ($row["payment_status"] == 'Paid' ? 'selected' : '') . ">Paid</option>
                                        <option " . ($row["payment_status"] == 'Unpaid' ? 'selected' : '') . ">Unpaid</option>
                                    </select>
                                    <input type='hidden' name='user_id' value='" . $row["id"] . "'>
                                    <button type='submit' name='update_payment_status'>Update Payment</button>
                                </form>
                            </td>
                            <td>
                                <form method='POST' action=''>
                                    <input type='hidden' name='user_id' value='" . $row["id"] . "'>
                                    <button type='submit' name='delete_user' class='delete-btn'>Delete User</button>
                                </form>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No users found</td></tr>";
            }
            ?>
        </table>
    </div>

    <div class="container main">
    <h2>Withdrawal Requests</h2>
    <table>
        <tr>
            <th>Withdrawal ID</th>
            <th>User ID</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php
        // Fetch withdrawal requests data
        $sql_withdrawals = "SELECT * FROM withdrawals";
        $result_withdrawals = $conn->query($sql_withdrawals);

        if ($result_withdrawals->num_rows > 0) {
            while($row = $result_withdrawals->fetch_assoc()) {
                $status_button_color = ($row["status"] == 'Paid') ? 'btn-green' : 'btn-yellow';
                echo "<tr>
                        <td>" . $row["id"]. "</td>
                        <td>" . $row["user_id"]. "</td>
                        <td>" . $row["payment"]. "</td>
                        <td>
                            <button class='update-btn $status_button_color'>" . $row["status"] . "</button>
                        </td>
                        <td>
                            <form method='POST' action=''>
                                <select name='withdrawal_status'>
                                    <option " . ($row["status"] == 'Paid' ? 'selected' : '') . ">Paid</option>
                                    <option " . ($row["status"] == 'Pending' ? 'selected' : '') . ">Pending</option>
                                </select>
                                <input type='hidden' name='withdrawal_id' value='" . $row["id"] . "'>
                                <button type='submit' name='update_withdrawal_status'>Update Status</button>
                                <button type='submit' name='delete_withdrawal' class='delete-btn'>Delete</button>
                            </form>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No withdrawal requests found</td></tr>";
        }
        ?>
    </table>
</div>

    <p style="text-align:center">&copy;copyright 2025  <b style="color:green">legit 💲 earn</b></p>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
