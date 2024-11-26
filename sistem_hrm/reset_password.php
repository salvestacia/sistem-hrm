<?php
session_start();
include 'config/koneksi.php'; // Database connection

$error = '';
$success = '';

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    // Check if the token is valid and not expired
    $query = "SELECT id_user FROM user WHERE reset_token = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($user_id);
        $stmt->fetch();
    } else {
        echo "Invalid or expired token.";
        exit();
    }

    // Check if form was submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // Validate password fields
        if (empty($new_password) || empty($confirm_password)) {
            $error = "Both password fields are required.";
        } elseif ($new_password !== $confirm_password) {
            $error = "Passwords do not match.";
        } elseif (strlen($new_password) < 6) {
            $error = "Password must be at least 6 characters long.";
        } else {
            // Update password in the database
            date_default_timezone_set('Asia/Jakarta');
            $query = "UPDATE user SET password = ?, reset_token = NULL, reset_expires = NULL, last_change_pass = NOW() WHERE id_user = ?";
            $stmt = $koneksi->prepare($query);
            $stmt->bind_param('si', $new_password, $user_id);

            if ($stmt->execute()) {
                echo "<script>
                    alert('Password successfully updated!'); 
                    window.location.href='http://localhost/sistem_hrm/index.php';
                    </script>";
            } else {
                $error = "Failed to update the password. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
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
        input[type="password"], input[type="text"] {
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
            width: 300px;
        }
        button {
            display: inline-block;
            padding: 8px 16px;
            text-decoration: none;
            background-color: #80BCBD;
            color: white;
            border-radius: 5px;
            border: none;
        }
        button:hover {
            background-color: #AAD9BB;
        }
        .toggle-password{
            cursor: pointer;
            color: #007bff;
            font-size: 14px;
        }
        .toggle-password:hover{
            text-decoration: underline;
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
        .success {
            color: green;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Reset Password</h1>
        <?php if ($error): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        
        <form method="POST">
            <input type="password" name="new_password" id="password" placeholder="Enter new password" required>
            <span id="togglePassword" class="toggle-password">Show</span>
            <br><br>
            <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm new password" required>
            <span id="toggleConfirmPassword" class="toggle-password">Show</span>
            <br><br>
            <button type="submit">Reset Password</button>
        </form>
    </div>

    <script>
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('confirm_password');
        const togglePassword = document.getElementById('togglePassword');
        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');

        togglePassword.addEventListener('click', function () {
            // Toggle between showing and hiding the password
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // Change the text of the toggle button
            this.textContent = type === 'password' ? 'Show' : 'Hide';
        });

        toggleConfirmPassword.addEventListener('click', function () {
            // Toggle between showing and hiding the password
            const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPasswordInput.setAttribute('type', type);

            // Change the text of the toggle button
            this.textContent = type === 'password' ? 'Show' : 'Hide';
        });
    </script>
</body>
</html>
