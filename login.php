<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login to your account and start earning for free // Legit-Earn</title>
    <link rel="stylesheet" href="style/style.css">
</head>


<body>
<?php include('include/header.php');?>


    <style>
        .form-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f9f9f9;
        }
        .color-blue{
            color: blue;
       }
       @media screen and (max-width: 800px) {
      .container{
      width: 90%;
    }
  }
    </style>
</head>
<body>
    <div class="container form-container">
        <h2 class="text-center">Member Login</h2>
        <form>
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" class="form-control" id="email" placeholder="Enter your email" required>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Login</button>
            <p class="center">Not a member? <b class="color-blue">Register here</b></p>
        </form>
    </div>


</body>
</html>