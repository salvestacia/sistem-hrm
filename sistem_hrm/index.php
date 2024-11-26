<?php
include 'config/koneksi.php';

session_start(); // Mulai session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form login
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Query untuk mencari user dengan username dan password yang sesuai
    $query = "SELECT id_user, id_pegawai, hak_akses, email FROM user WHERE username=? AND password=?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param('ss', $username, $password);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        // Jika data ditemukan, simpan informasi pengguna dalam session
        $stmt->bind_result($id_user, $id_pegawai, $hak_akses, $email);
        $stmt->fetch();

        $_SESSION['username'] = $username; //session membawa data username
        $_SESSION['logged_in'] = true; 
        $_SESSION['id_pegawai'] = $id_pegawai; //session membawa data id_pegawai
        $_SESSION['id_user'] = $id_user; //session membawa data id user

        // Query untuk mengambil data pegawai berdasarkan id_pegawai
        $query_pegawai = "SELECT nama FROM pegawai WHERE id_pegawai = ?";
        $stmt_pegawai = $koneksi->prepare($query_pegawai);
        $stmt_pegawai->bind_param('i', $id_pegawai);
        $stmt_pegawai->execute();
        $result_pegawai = $stmt_pegawai->get_result();

        if ($result_pegawai->num_rows == 1) {
            $data_pegawai = $result_pegawai->fetch_assoc();
            $_SESSION['nama'] = $data_pegawai['nama']; // Simpan nama pegawai dalam session
        }

        // Redirect ke halaman utama
        header("Location: http://localhost/sistem_hrm/homePage.php");
        exit(); // Pastikan untuk menghentikan eksekusi kode setelah melakukan redirect
    } else {
        // Jika data tidak ditemukan, tampilkan pesan error atau redirect kembali ke halaman login
        echo "Username atau password salah.";
    }

    $stmt->close();
    mysqli_close($koneksi);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
        #forgotPassword, #register {
            cursor: pointer;
            color: #007bff;
            font-size: 14px;
        }
        #forgotPassword:hover, #register:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <p style="font-size:30px; font-weight:bold;">Login</p>
        <p>Sistem Human Resources Management</p>
        <form method="POST">
            <label for="username">Username</label><br/>
            <input type="text" name="username" id="username" placeholder="Username" ><br/>
            <label for="password">Password</label><br/>
            <input type="password" name="password" id="password" placeholder="Password"><br/><br/>
            <input type="submit" name="submit" value="Masuk">
            <span id="forgotPassword">Forgot Password</span>
            <span id="register">Register Account</span> 
        </form>
    </div>
    <script>
        //forgot password button
        document.getElementById("forgotPassword").addEventListener("click", function() {
            window.location.href = "http://localhost/sistem_hrm/forgot_password.php";
        });
        //register
        document.getElementById("register").addEventListener("click", function() {
            window.location.href = "http://localhost/sistem_hrm/register.php";
        });
    </script>
</body>
</html>
