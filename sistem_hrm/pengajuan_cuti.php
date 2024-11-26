<?php
include 'config/koneksi.php';
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: http://localhost/sistem_hrm/index.php");
    exit();
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer-master/src/Exception.php';
require './PHPMailer-master/src/PHPMailer.php';
require './PHPMailer-master/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_pegawai = $_SESSION['nama'];
    $id_pegawai = $_SESSION['id_pegawai'];
    $jenis_cuti = $_POST['jenis_cuti'];
    $tanggal_awal = $_POST['tanggal_awal'];
    $tanggal_akhir = $_POST['tanggal_akhir'];
    $alasan_cuti = $_POST['alasan_cuti'];

    $sql = "INSERT INTO cuti (id_pegawai, jenis_cuti, tanggal_awal, tanggal_akhir, alasan_cuti) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("issss", $id_pegawai, $jenis_cuti, $tanggal_awal, $tanggal_akhir, $alasan_cuti);

    if ($stmt->execute()) {
        // Kirim email ke admin menggunakan PHPMailer
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'darrenchrist2@gmail.com'; // Replace with your Gmail address
            $mail->Password = 'xitlwwxvtkndsmee'; // Replace with your Gmail password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('darrenchrist2@gmail.com', 'Sistem HRM');
            $mail->addAddress('darrenchrist2@gmail.com');

            $mail->isHTML(true);
            $mail->Subject = 'Notifikasi Pengajuan Cuti';
            $mail->Body    = "$nama_pegawai mengajukan cuti dari $tanggal_awal hingga $tanggal_akhir.<br> Alasan: $alasan_cuti.";

            $mail->send();
        } catch (Exception $e) {
            echo "Email tidak dapat dikirim. Error: {$mail->ErrorInfo}";
        }

        echo "<script>
                alert('Pengajuan cuti berhasil dikirim!');
                window.location.href='http://localhost/sistem_hrm/pengajuan_cuti.php';
              </script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat mengajukan cuti.');</script>";
    }

    $stmt->close();
    $koneksi->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Cuti</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        h1 {
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

        input[type="date"], 
        select, 
        textarea {
            width: 95%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
        }

        textarea {
            height: 100px;
        }

        button[type="submit"], 
        a.button {
            padding: 8px 16px;
            background-color: #80BCBD;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            display: inline-block;
        }

        button[type="submit"]:hover, 
        a.button:hover {
            background-color: #AAD9BB;
        }

        #overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9998;
        }

        #loading {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            text-align: center;
            color: white;
            font-size: 24px;
            padding-top: 20%;
        }
    </style>
    <script>
        function showLoading() {
            document.getElementById('overlay').style.display = 'block';
            document.getElementById('loading').style.display = 'block';
        }
    </script>
</head>
<body>
    <div id="overlay"></div>
    <div id="loading">Processing your request, please wait...</div>
    <h1>Form Pengajuan Cuti</h1>
    <form method="POST" onsubmit="showLoading()">
        <label for="jenis_cuti">Jenis Cuti:</label>
        <select name="jenis_cuti" required>
            <option value="sakit">Sakit</option>
            <option value="izin">Izin</option>
        </select>
        <br>
        <label for="tanggal_awal">Tanggal Awal:</label>
        <input type="date" name="tanggal_awal" required>
        <br>
        <label for="tanggal_akhir">Tanggal Akhir:</label>
        <input type="date" name="tanggal_akhir" required>
        <br>
        <label for="alasan_cuti">Alasan:</label>
        <textarea name="alasan_cuti" required></textarea>
        <br>
        <button type="submit">Kirim</button>
        <a href="http://localhost/sistem_hrm/homePage.php" class="button">Back</a>
    </form>
</body>
</html>
