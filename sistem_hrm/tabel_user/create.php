<?php
session_start(); // Mulai session

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['logged_in'])) {
    // Jika belum login, redirect kembali ke halaman login atau halaman lain
    header("Location: http://localhost/sistem_hrm/index.php");
    exit();
}
?>

<?php
include "../config/koneksi.php";
if(isset($_POST['submit'])){
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $id_pegawai = $_POST['id_pegawai'];
    $hak_akses = $_POST['hak_akses'];

    $query = "INSERT INTO user (username, email, password, id_pegawai, hak_akses) VALUES ('$username', '$email', '$password', '$id_pegawai','$hak_akses')";
    $result = mysqli_query($koneksi, $query);

    if(!$result){
        echo "Data gagal ditambahkan!";
    }
    else{
        echo "Data berhasil ditambahkan!";
        header("Location: http://localhost/sistem_hrm/tabel_user/read.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        h1{
            color: #333;
            text-align: center;    
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            max-width: 500px;
            margin: 0 auto;
            border: 2px solid black;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"], select {
            width: 95%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
        }

        button[type="submit"]{
            padding: 8px 16px;
            background-color: #80BCBD;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        a.button {
            display: inline-block;
            padding: 8px 16px;
            text-decoration: none;
            background-color: #80BCBD;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        button[type="submit"]:hover, a.button:hover {
            background-color: #AAD9BB;
        }
    </style>
</head>
<body>
    <h1>Tambah Data User </h1>
    <form method="post">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username">
        <br>
        <label for="email">Email:</label>
        <input type="text" name="email" id="email">
        <br>
        <label for="password">Password:</label>
        <input type="text" name="password" id="password">
        <br>
        <label for="id_pegawai">Nama Pegawai:</label>
        <select id="id_pegawai" name="id_pegawai" required>
            <?php
            $result = $koneksi->query("SELECT id_pegawai, nama FROM pegawai");
            while ($row = $result->fetch_assoc()) {
                echo "<option value='{$row['id_pegawai']}'>{$row['nama']}</option>";
            }
            ?>
        </select>
        <br>
        <label for="hak_akses">Hak Akses:</label>
        <input type="text" name="hak_akses" id="hak_akses">
        <br>
        <br>
        <button type="submit" name="submit">Tambah</button>
        <a href="http://localhost/sistem_hrm/tabel_user/read.php" class="button">Kembali</a>
    </form>
    <a href= "http://localhost/sistem_hrm/logout.php" class= "button">logout</a>
</body>
</html>