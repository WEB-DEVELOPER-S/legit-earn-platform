
<?php
session_start();
include('db_connect.php');

// Define the path to the sample image
$sampleImagePath = 'images/IMG_20220918_114340_0.jpg'; // Path to the sample image

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $views = intval($_POST['views']);
    $user_id = $_SESSION['user_id'];
    
    if ($views <= 0) {
        $message = "The number of views must be a positive integer.";
    } elseif (isset($_FILES['screenshot']) && $_FILES['screenshot']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        $uploadFile = $uploadDir . basename($_FILES['screenshot']['name']);
        
        // Ensure the uploads directory exists and is writable
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        if (move_uploaded_file($_FILES['screenshot']['tmp_name'], $uploadFile)) {
            // Validate that the uploaded image matches the sample image
            if (compareImages($uploadFile, $sampleImagePath)) {
                $reward = $views * 1; // Reward calculation: 1 RWF per view
                
                // Insert data into the database
                $query = "INSERT INTO screenshots (user_id, views, photo_path, reward) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('iisd', $user_id, $views, $uploadFile, $reward);
                $stmt->execute();
                
                $message = "Screenshot submitted successfully! Your reward is " . $reward . " RWF.";
            } else {
                $message = "The screenshot does not match the expected sample image.";
            }
        } else {
            $message = "Failed to upload screenshot.";
        }
    } else {
        $message = "Please select a screenshot to upload.";
    }
}

// Function to compare two images
function compareImages($image1, $image2) {
    // Compare the images by checking if they are the same file
    return basename($image1) === basename($image2);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Withdrawal History - SwiftEarn</title>
    <link rel="stylesheet" href="styles.css">
   <link  rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        /* General Reset and Body Styles */
     
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f5;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            width: 90%;
        }
        h2 {
            color: #4a4a6a;
            text-align: center;
            margin-bottom: 20px;
        }
        p {
            text-align: center;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        label {
            font-weight: bold;
            color: #4a4a6a;
        }
        input[type="number"],
        input[type="file"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        .submit-button {
            background-color: blue;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .submit-button:hover {
            background-color: #e69500;
        } 
        .download-link {
            display: block;
            text-align: center;
            margin: 15px 0;
            color: #007bff;
            text-decoration: none;
        }
        .download-link:hover {
            text-decoration: underline;
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
        .qwert{
            
            background-color:blue;
            color:white;
            font-size:20px;
            border-radius:3px;
            border:none;
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

    
        @media (max-width: 768px) {
            .container {
                width: 100%;
                margin: 20px auto;
                padding: 15px;
            }
            h2 {
                font-size: 22px;
            }
            input[type="number"],
            input[type="file"],
            .submit-button {
                font-size: 14px;
                padding: 8px;
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

    <h2>WhatsApp</h2>
        <p>Download the image and post it on WhatsApp. Then submit the screenshot along with the number of views.</p>
        
        <!-- Example downloadable image link -->
        <a href="images/IMG_20220918_114340_0.jpg" class="download-link" download><button class="qwert"><i class="fa-solid fa-download"></i> Download Image and post</button></a>
        
        <form method="POST" enctype="multipart/form-data">
            <label for="views">Number Of Views</label>
            <input type="number" name="views" placeholder="Number of Views" required>
            
            <label for="screenshot">Photo</label>
            <input type="file" name="screenshot" required>
            
            <button type="submit" class="submit-button">SUBMIT SCREENSHOT</button>
        </form>
        
        <?php if ($message): ?>
            <script>alert('<?php echo htmlspecialchars($message); ?>');</script>
        <?php endif; ?>
    </div>
    <!-- Footer Section -->
    <div class="footer">
        Copyright © 2025 <a href="legit.php">LEGIT💲EARN</a>. Designed by <a href="#">web developers Limited</a> All rights reserved.
    </div>
</body>
</html>
