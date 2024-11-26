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

if (isset($_POST['action'])) {
    $id = $_POST['id'];
    $status = $_POST['action'];

    $sql = "UPDATE cuti SET status = ? WHERE id = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("si", $status, $id);

    if ($stmt->execute()) {
        // Kirim notifikasi email ke karyawan
        $sql_pegawai = "SELECT p.nama, u.username, u.email
                FROM cuti c 
                JOIN pegawai p ON c.id_pegawai = p.id_pegawai 
                JOIN user u ON p.id_pegawai = u.id_pegawai 
                WHERE c.id = ?";
        $stmt_pegawai = $koneksi->prepare($sql_pegawai);
        $stmt_pegawai->bind_param("i", $id);
        $stmt_pegawai->execute();
        $result_pegawai = $stmt_pegawai->get_result();
        $pegawai = $result_pegawai->fetch_assoc();

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
            $mail->addAddress($pegawai['email']);

            $mail->isHTML(true);
            $mail->Subject = 'Status Pengajuan Cuti';
            $mail->Body    = "Halo {$pegawai['nama']},<br> Pengajuan cuti Anda telah di-$status.";

            $mail->send();
        } catch (Exception $e) {
            echo "Email tidak dapat dikirim. Error: {$mail->ErrorInfo}";
        }

        echo "<script>
                alert('Cuti pegawai telah di-$status!!!');
                window.location.href='http://localhost/sistem_hrm/manajemen_cuti.php';
              </script>";
    }
}

$result = $koneksi->query("SELECT p.nama, c.id, c.jenis_cuti, c.tanggal_awal, c.tanggal_akhir, c.alasan_cuti, c.status
                FROM cuti c 
                JOIN pegawai p ON c.id_pegawai = p.id_pegawai");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Cuti</title>
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

        .container {
            margin: 20px;
        }

        h1 {
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

        button {
            padding: 5px 10px;
            background-color: #80BCBD;
            border: none;
            color: white;
            cursor: pointer;
            border-radius: 3px;
        }

        button:hover {
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
    <div class="container">
    <h1>Daftar Pengajuan Cuti</h1>
    <a href="http://localhost/sistem_hrm/homePage.php" class="button">Back</a>
    <table border="1">
        <thead>
            <tr>
                <th>Pegawai</th>
                <th>Jenis</th>
                <th>Periode</th>
                <th>Alasan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['nama']; ?></td>
                <td><?= $row['jenis_cuti']; ?></td>
                <td><?= $row['tanggal_awal']; ?> s/d <?= $row['tanggal_akhir']; ?></td>
                <td><?= $row['alasan_cuti']; ?></td>
                <td><?= $row['status'] ?? 'Menunggu'; ?></td>
                <td>
                    <form method="POST" onsubmit="showLoading()">
                        <input type="hidden" name="id" value="<?= $row['id']; ?>">
                        <button type="submit" name="action" value="approve">Approve</button>
                        <button type="submit" name="action" value="reject">Reject</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    </div>
</body>
</html>
