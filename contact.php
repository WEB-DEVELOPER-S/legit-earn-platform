<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Swiftearn - Contact Us</title>
    <style>
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

        /* Container for Main Content */
        .container {
            width: 90%;
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
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

        /* Media Queries for Responsiveness */
        @media screen and (max-width: 768px) {
            .header {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .header-title {
                font-size: 1.2em;
            }

            .container {
                width: 95%;
                padding: 15px;
            }

            .breadcrumb {
                margin: 10px;
                font-size: 0.8em;
            }
        }

        @media screen and (max-width: 480px) {
            .header-title {
                font-size: 1em;
            }

            .container {
                width: 95%;
                padding: 10px;
            }

            .breadcrumb {
                font-size: 0.7em;
            }

            .footer {
                font-size: 0.7em;
            }
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <div class="header">
        <a href="javascript:history.back()" class="back-button">☰</a>
        <div class="header-title">SWIFT💲EARN</div>
        <div class="settings-icon">&nbsp;</div>
    </div>

    <!-- Breadcrumb Navigation -->
    <main class="breadcrumb">
        <a href="legit.php" style="font-weight:bold;">Home</a> / <a href="#">proceed</a>
    </main>

    <!-- Main Content Container -->
    <div class="container">
        <div class="contact-card">
            <h3>CONTACT US HERE</h3>
            <h5>EMAIL US</h5>
            <p><a href="mailto:kejooelyse@gmail.com">kejooelyse@gmail.com</a></p>
        </div>
    </div>

    <!-- Footer Section -->
    <div class="footer">
        Copyright © 2025 <a href="legit.php">LEGIT💲EARN</a>. Designed by <a href="#">web developers Limited</a> All rights reserved.
    </div>
</body>
</html>
