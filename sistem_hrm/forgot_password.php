<?php
session_start();
include 'config/koneksi.php'; // Database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #F9F7C9;
            padding: 30px;
            border: 2px solid black;
            border-radius: 10px;
            width: 400px;
            text-align: center;
        }
        input[type="email"] {
            padding: 8px;
            margin-bottom: 10px;
            width: 100%;
        }
        a, button {
            display: inline-block;
            padding: 8px 16px;
            text-decoration: none;
            background-color: #80BCBD;
            color: white;
            border-radius: 5px;
            border: none;
        }
        a:hover, button:hover {
            background-color: #AAD9BB;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Forgot Password</h1>
        <form action="forgot_password_action.php" method="POST">
            <input type="email" name="email" placeholder="Enter your email" required>
            <button type="submit">Submit</button>
            <a href="http://localhost/sistem_hrm/index.php" class="button">Kembali</a>
        </form>
    </div>
</body>
</html>
