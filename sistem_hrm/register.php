<?php
include 'config/koneksi.php';

session_start(); // Start session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect data from the registration form
    $nik = isset($_POST['nik']) ? $_POST['nik'] : '';
    $nama = isset($_POST['nama']) ? $_POST['nama'] : '';
    $alamat = isset($_POST['alamat']) ? $_POST['alamat'] : '';
    $tgl_lahir = isset($_POST['tgl_lahir']) ? $_POST['tgl_lahir'] : '';
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password']: ''; 
    $email = isset($_POST['email']) ? $_POST['email'] : '';

    // Check if the username already exists
    $check_query = "SELECT id_user FROM user WHERE username=?";
    $check_stmt = $koneksi->prepare($check_query);
    $check_stmt->bind_param('s', $username);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows == 0) {
        // Insert the new employee into the pegawai table
        $insert_pegawai_query = "INSERT INTO pegawai (nik, nama, alamat, tgl_lahir) VALUES (?, ?, ?, ?)";
        $insert_pegawai_stmt = $koneksi->prepare($insert_pegawai_query);
        $insert_pegawai_stmt->bind_param('ssss', $nik,$nama, $alamat, $tgl_lahir);

        if ($insert_pegawai_stmt->execute()) {
            // Get the new id_pegawai
            $id_pegawai = $insert_pegawai_stmt->insert_id;

            // Insert the new user into the user table with the new id_pegawai
            $insert_user_query = "INSERT INTO user (username, password, email, id_pegawai, hak_akses) VALUES (?, ?, ?, ?, 1)";
            $insert_user_stmt = $koneksi->prepare($insert_user_query);
            $insert_user_stmt->bind_param('sssi', $username, $password, $email, $id_pegawai);

            if ($insert_user_stmt->execute()) {
                // Successful registration, redirect to login page
                echo "Registration successful. Redirecting to login page...";
                header("Refresh: 2; URL=http://localhost/sistem_hrm/index.php");
            } else {
                // Handle query failure
                echo "Error: Could not register user. Please try again.";
            }

            $insert_user_stmt->close();
        } else {
            // Handle query failure for pegawai
            echo "Error: Could not register employee. Please try again.";
        }

        $insert_pegawai_stmt->close();
    } else {
        // If username already exists, show error message
        echo "Username already exists. Please choose a different username.";
    }

    $check_stmt->close();
    mysqli_close($koneksi);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        input{
            width: 250px;
        }
        input[type="submit"]{
            background-color: #80BCBD;
            color: white;
            border-radius: 5px;
            padding: 8px 16px;
        }
        input[type="submit"]:hover{
            background-color: #AAD9BB;
        }

        body{
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container{
            background-color: #F9F7C9;
            padding: 20px 30px;
            border: 2px solid black;
            border-radius: 10px;
        }
        #login {
            cursor: pointer;
            color: #007bff;
            font-size: 14px;
        }
        #login:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <p style="font-size:30px; font-weight:bold;">Register</p>
        <p>Sistem Human Resources Management</p>
        <form method="POST">
            <label for="nik">NIK</label><br/>
            <input type="text" name="nik" id="nik" placeholder="NIK" required><br/>
            <label for="nama">Nama Lengkap</label><br/>
            <input type="text" name="nama" id="nama" placeholder="Nama Lengkap" required><br/>
            <label for="alamat">Alamat</label><br/>
            <input type="text" name="alamat" id="alamat" placeholder="Alamat" required><br/>
            <label for="tgl_lahir">Tanggal Lahir</label><br/>
            <input type="date" name="tgl_lahir" id="tgl_lahir" placeholder="Tanggal Lahir" required><br/>
            <label for="username">Username</label><br/>
            <input type="text" name="username" id="username" placeholder="Username" required><br/>
            <label for="email">Email</label><br/>
            <input type="email" name="email" id="email" placeholder="Email" required><br/>
            <label for="password">Password</label><br/>
            <input type="password" name="password" id="password" placeholder="Password" required><br/><br/>
            <input type="submit" name="submit" value="Register">
            <span id="login">Login</span> 
        </form>
    </div>
    <script>
        // Redirect to the login page when the "Login" text is clicked
        document.getElementById("login").addEventListener("click", function() {
            window.location.href = "http://localhost/sistem_hrm/index.php";
        });
    </script>
</body>
</html>
