<?php
session_start();
include 'config/koneksi.php';

// Check if the user is logged in
if (!isset($_SESSION['logged_in'])) {
    header("Location: http://localhost/sistem_hrm/index.php");
    exit();
}

$id_user = $_SESSION['id_user'];

// Fetch user profile data from the database
$query = "SELECT username, email, password FROM user WHERE id_user = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param('i', $id_user);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($username, $email, $password);
    $stmt->fetch();
} else {
    echo "User not found.";
    exit();
}

$stmt->close();
$koneksi->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .profile-container {
            background-color: #F9F7C9;
            padding: 30px;
            border: 2px solid black;
            border-radius: 10px;
            width: 500px;
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
        a {
            display: inline-block;
            padding: 8px 16px;
            text-decoration: none;
            background-color: #80BCBD;
            color: white;
            border-radius: 5px;
        }
        a:hover {
            background-color: #AAD9BB;
        }
        .password-field {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
        }
        .password-field p {
            margin: 0;
            margin-right: 10px;
        }
        .password-input {
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
            width: 150px;
        }
        .toggle-password, #resetPassword {
            margin-left: 10px;
            cursor: pointer;
            color: #007bff;
            font-size: 14px;
        }
        .toggle-password:hover, #resetPassword:hover {
            text-decoration: underline;
        }
        #status {
            font-weight: bold;
        }
        .high-risk {
            color: red;
        }
        .secure {
            color: green;
        }

    </style>
</head>
<body>
    <div class="profile-container">
        <h1>Profile Information</h1>
        <p><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>

        <div class="password-field">
            <p><strong>Password:</strong></p>
            <input type="password" id="password" class="password-input" value="<?php echo htmlspecialchars($password); ?>" readonly>
            <span id="togglePassword" class="toggle-password">Show</span>
            <span id="resetPassword">Reset Password</span>
        </div>

        <a href="http://localhost/sistem_hrm/logout.php">Logout</a>
        <a href="http://localhost/sistem_hrm/homePage.php" class="button">Kembali</a>
    </div>

    <script>
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');

        togglePassword.addEventListener('click', function () {
            // Toggle between showing and hiding the password
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // Change the text of the toggle button
            this.textContent = type === 'password' ? 'Show' : 'Hide';
        });

        //reset password button
        document.getElementById("resetPassword").addEventListener("click", function() {
            window.location.href = "http://localhost/sistem_hrm/reset_password_on_profile.php";
        });
    </script>
</body>
</html>
