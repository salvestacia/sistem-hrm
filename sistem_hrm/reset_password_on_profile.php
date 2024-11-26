<?php
session_start();
include 'config/koneksi.php';

// Check if the user is logged in
if (!isset($_SESSION['logged_in'])) {
    header("Location: http://localhost/sistem_hrm/index.php");
    exit();
}

$id_user = $_SESSION['id_user'];
$error = '';
$success = '';

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
        // Update the password in the database
        date_default_timezone_set('Asia/Jakarta');
        $query = "UPDATE user SET password = ?, last_change_pass = NOW() WHERE id_user = ?";
        $stmt = $koneksi->prepare($query);
        $stmt->bind_param('si', $new_password, $id_user);

        if ($stmt->execute()) {
            $success = "Password has been successfully updated.";
        } else {
            $error = "Failed to update the password. Please try again.";
        }

        $stmt->close();
    }
}

$koneksi->close();
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
            margin: 0;
        }
        .reset-container {
            background-color: #F9F7C9;
            padding: 30px;
            border: 2px solid black;
            border-radius: 10px;
            width: 400px;
            text-align: center;
        }
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        p {
            font-size: 18px;
            margin: 10px 0;
        }
        input {
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
            width: 300px;
        }
        button, a {
            padding: 10px;
            font-size: 16px;
            background-color: #80BCBD;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        button:hover, a:hover {
            background-color: #AAD9BB;
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
        .success {
            color: green;
            margin-bottom: 10px;
        }
        .toggle-password, #resetPassword {
            cursor: pointer;
            color: #007bff;
            font-size: 14px;
        }
        .toggle-password:hover, #resetPassword:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <h1>Reset Password</h1>
        <?php if ($error): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php elseif ($success): ?>
            <p class="success"><?php echo htmlspecialchars($success); ?></p>
            <?php echo "<script>alert('Password has been successfully updated.');</script>"; ?>
            <script>
                setTimeout(function() {
                    window.location.href = "http://localhost/sistem_hrm/index.php";
                }, 1000); 
            </script>
        <?php endif; ?>
        
        <form method="POST" action="reset_password_on_profile.php">
            <input type="password" id="new_password" name="new_password" class="new_password" placeholder="Enter new password">
            <span id="togglePassword1" class="toggle-password">Show</span>
            <br>
            <br>
            <input type="password" id="confirm_password" name="confirm_password" class="confirm_password" placeholder="Confirm new password">
            <span id="togglePassword2" class="toggle-password">Show</span>
            <br>
            <br>
            <button type="submit">Reset Password</button>
            <a href="http://localhost/sistem_hrm/profile.php">Kembali</a>
        </form>
    </div>
    <script>
    const newPasswordInput = document.getElementById('new_password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    const togglePassword1 = document.getElementById('togglePassword1');
    const togglePassword2 = document.getElementById('togglePassword2');

    togglePassword1.addEventListener('click', function () {
        // Toggle between showing and hiding the password
        const type = newPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        newPasswordInput.setAttribute('type', type);

        // Change the text of the toggle button
        this.textContent = type === 'password' ? 'Show' : 'Hide';
    });

    togglePassword2.addEventListener('click', function () {
        // Toggle between showing and hiding the password
        const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmPasswordInput.setAttribute('type', type);

        // Change the text of the toggle button
        this.textContent = type === 'password' ? 'Show' : 'Hide';
    });
</script>
</body>
</html>
