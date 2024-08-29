<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Swiftearn - Contact Us</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #5c4ac7;
            color: #ffffff;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .container {
            text-align: center;
            flex: 1;
        }

        .back-button {
            position: absolute;
            top: 20px;
            left: 10px;
            color: #ffffff;
            text-decoration: none;
            font-size: 24px;
        }

        .logo {
            text-align: center;
            margin: 20px 0;
        }

        .logo img {
            width: 150px; /* Adjust the logo size */
        }

        .breadcrumb {
            background-color: transparent;
            padding-left: 0;
            list-style: none;
            display: inline-block;
            margin-bottom: 20px;
        }

        .breadcrumb-item a {
            color: #ffffff;
            text-decoration: none;
        }

        .breadcrumb-item.active {
            color: #ffffff;
        }

        .contact-card {
            background-color: #ffffff;
            color: #333333;
            border-radius: 10px;
            padding: 70px;
            margin: 90px auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 90%;
        }

        .contact-card h3, .contact-card h5 {
            margin-top: 10px;
            text-align: center;
        }

        .contact-card p {
            margin-bottom: 0;
            text-align: center;
        }

        .footer {
            background-color: #4a3aa0;
            padding: 20px 0;
            color: #cccccc;
            text-align: center;
            margin-top: auto;
        }

        .footer a {
            color: #ffffff;
            text-decoration: none;
        }

        @media (min-width: 768px) {
            .breadcrumb {
                display: block;
            }
        }
    </style>
</head>
<body>
    <?php
    $page_title = "LEGIT 💲 EARN";
    $email_contact = "kejooelyse@gmail.com";
    ?>

    <!-- Back Button -->
    <a href="javascript:history.back()" class="back-button">&larr;</a>

    <!-- Logo Section -->
    <div class="logo">
        <h1>LEGIT 💲 EARN</h1>
    </div>
    <hr>

    <!-- Breadcrumb Navigation -->
    <div class="container"><br>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="legit.php"><span style="color:black;">Home </span>/ proceed</a></li>
            </ol>
        </nav>
    </div>

    <!-- Contact Us Section -->
    <div class="container">
        <div class="contact-card">
            <h3>CONTACT US HERE</h3>
            <h5>EMAIL US</h5>
            <p><a href="mailto:<?php echo $email_contact; ?>"><?php echo $email_contact; ?></a></p>
        </div>
    </div>

    <!-- Footer Section -->
    <div class="footer">
        <p>Copyright © 2025 <a href="#"><?php echo $page_title; ?></a>. Designed by 
            <a href="">Web Developers Limited</a>. All rights reserved.</p>
    </div>
</body>
</html>
