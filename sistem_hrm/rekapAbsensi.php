<?php
session_start(); // Mulai session

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['logged_in'])) {
    // Jika belum login, redirect kembali ke halaman login atau halaman lain
    header("Location: http://localhost/sistem_hrm/login.php");
    exit();
}
?>

<?php
include "config/koneksi.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Presensi</title>
    <style>
        *{
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container{
            margin: 20px;
        }

        h2 {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th {
            background-color: #f2f2f2;
        }

        th, td {
            padding: 10px;
            text-align: left;
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

        a.button:hover {
            background-color: #AAD9BB;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Rekap Presensi</h2>

    <a href="http://localhost/sistem_hrm/homePage.php" class="button">Back</a>

    <table border='1'>
        <tr>
            <th>Nama Pegawai</th>
            <th>Tanggal</th>
            <th>Jam</th>
            <th>Status</th>
        </tr>
        <?php
        $query = "SELECT absensi.id_pegawai, absensi.tgl_tap, absensi.status, pegawai.nama 
        FROM absensi 
        INNER JOIN pegawai ON absensi.id_pegawai = pegawai.id_pegawai";

        $result = mysqli_query($koneksi, $query);

        if(!$result){
            echo "query gagal!";
        } 
        else{
            while($row = mysqli_fetch_assoc($result)){
                $tanggal = date('Y-m-d', strtotime($row['tgl_tap']));
                $jam = date('H:i:s', strtotime($row['tgl_tap']));
                echo "<tr>
                        <td>".$row['nama']."</td>
                        <td>".$tanggal."</td>
                        <td>".$jam."</td>
                        <td>".$row['status']."</td>
                      </tr>";
            }
        }
        ?>
    </table>
</div>
</body>
</html>
