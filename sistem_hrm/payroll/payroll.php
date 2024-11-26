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
?>

<?php
// Function to calculate salary
function calculateSalary($totalJamKerja, $jamKerjaIdeal, $gajiPokok) {
    return ($totalJamKerja / $jamKerjaIdeal) * $gajiPokok;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_pegawai = $_POST['id_pegawai'];
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    $keterangan = $_POST['keterangan'];

    // Ambil data pegawai
    $sqlPegawai = "SELECT * FROM pegawai WHERE id_pegawai = ?";
    $stmtPegawai = $koneksi->prepare($sqlPegawai);
    $stmtPegawai->bind_param("i", $id_pegawai);
    $stmtPegawai->execute();
    $resultPegawai = $stmtPegawai->get_result();
    $pegawai = $resultPegawai->fetch_assoc();
    $stmtPegawai->close();

    // Menghitung total jam kerja
    $sqlTotalJamKerja = "SELECT 
                SUM(
                    CASE 
                        WHEN status = 'Tap Out' THEN UNIX_TIMESTAMP(tgl_tap) 
                        WHEN status = 'Tap In' THEN -UNIX_TIMESTAMP(tgl_tap) 
                    END
                ) AS totalJamKerjaDetik 
            FROM 
                absensi 
            WHERE 
                id_pegawai = '$id_pegawai' 
                AND DATE(tgl_tap) BETWEEN '$startDate' AND '$endDate'";
    $resultTotalJamKerja = $koneksi->query($sqlTotalJamKerja);

    if ($resultTotalJamKerja->num_rows > 0) {
        $row = $resultTotalJamKerja->fetch_assoc();
        $totalJamKerjaDetik = $row['totalJamKerjaDetik'];
        if ($totalJamKerjaDetik > 0) {
            $totalJamKerja = intval($totalJamKerjaDetik / 3600); 
        } else {
            $totalJamKerja = 0;
        }
    } else {
        echo "Data absensi tidak ditemukan";
        exit;
    }

    //Gaji pokok karyawan
    $gajiPokok = $pegawai['gaji_pokok'];

    //Menghitung jam kerja ideal
    $sqlJamKerjaIdeal = "SELECT hari_kerja_1_bulan * jam_kerja_1_hari AS jam_kerja_ideal FROM pegawai WHERE id_pegawai = ?";
    $stmtJamKerjaIdeal = $koneksi->prepare($sqlJamKerjaIdeal);
    $stmtJamKerjaIdeal->bind_param("i", $id_pegawai);
    $stmtJamKerjaIdeal->execute();
    $resultJamKerjaIdeal = $stmtJamKerjaIdeal->get_result();
    $jamKerjaIdeal = 0;
    while ($row = $resultJamKerjaIdeal->fetch_assoc()) {
        $jamKerjaIdeal += $row['jam_kerja_ideal'];
    }

    //Menghitung gaji
    $gaji = calculateSalary($totalJamKerja, $jamKerjaIdeal, $gajiPokok);

    //input data gaji ke database
    $sqlInsertDataGaji = "INSERT INTO payroll (id_pegawai, periode_awal, periode_akhir, total_jam_kerja, gaji, keterangan) 
    VALUES ('".$pegawai['id_pegawai']."', '$startDate', '$endDate', '$totalJamKerja', '$gaji', '$keterangan')";
    $stmtInsertDataGaji = $koneksi->prepare($sqlInsertDataGaji);
    $stmtInsertDataGaji->execute();
    $resultInsertDataGaji = $stmtInsertDataGaji->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Payroll</title>
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

        form, div {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            max-width: 500px;
            margin: 0 auto;
            border: 2px solid black;
        }

        label{
            display: block;
            margin-bottom: 5px;
        }

        input[type="date"], input[type="text"], select{
            width: 95%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
        }

        input[type="submit"]{
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

        input[type="submit"]:hover, a.button:hover {
            background-color: #AAD9BB;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 2px solid #ddd;
        }

        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
<h1>Payroll</h1>
    <form action="payroll.php" method="post">
        <label for="id_pegawai">Nama Pegawai:</label>
        <select id="id_pegawai" name="id_pegawai" required>
            <?php
            $result = $koneksi->query("SELECT id_pegawai, nama FROM pegawai");
            while ($row = $result->fetch_assoc()) {
                echo "<option value='{$row['id_pegawai']}'>{$row['nama']}</option>";
            }
            ?>
        </select><br>
        
        <label for="start_date">Periode Awal:</label>
        <input type="date" id="start_date" name="start_date" required><br>
        
        <label for="end_date">Periode Akhir:</label>
        <input type="date" id="end_date" name="end_date" required><br>
        
        <label for="keterangan">Keterangan:</label>
        <input type="text" id="keterangan" name="keterangan" required><br>
        
        <input type="submit" value="Hitung Gaji">
        <a href="http://localhost/sistem_hrm/payroll/readPayroll.php" class="button">Back</a>
    </form>
    
    <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
    <div>
    <h2>Hasil Perhitungan Gaji</h2>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>Nama Karyawan</th>
            <td><?php echo $pegawai['nama']; ?></td>
        </tr>
        <tr>
            <th>Periode Awal</th>
            <td><?php echo $startDate; ?></td>
        </tr>
        <tr>
            <th>Periode Akhir</th>
            <td><?php echo $endDate; ?></td>
        </tr>
        <tr>
            <th>Total Jam Kerja</th>
            <td><?php echo $totalJamKerja; ?> jam</td>
        </tr>
        <tr>
            <th>Gaji</th>
            <td>Rp <?php echo number_format($gaji, 0, ',', '.'); ?></td>
        </tr>
    </table>
    </div>
    <?php endif; ?>
</body>
</html>