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
$id = $_GET['id'];
$query = "SELECT * FROM pegawai  WHERE id_pegawai= '$id'";
$result = mysqli_query($koneksi, $query);
if(!$result){
    echo "Query gagal!";
}
else{
    $data = mysqli_fetch_assoc($result);
}
if(isset($_POST["submit"])){
    $nik = $_POST['nik'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $tgl_lahir = $_POST['tgl_lahir'];
    $divisi = $_POST['divisi'];
    $gaji_pokok = $_POST['gaji_pokok'];
    $hari_kerja_1_bulan = $_POST['hari_kerja_1_bulan'];
    $jam_kerja_1_hari = $_POST['jam_kerja_1_hari'];
    $query = "UPDATE pegawai SET nik = '$nik', nama ='$nama', alamat = '$alamat', tgl_lahir = '$tgl_lahir', divisi = '$divisi', gaji_pokok = '$gaji_pokok', hari_kerja_1_bulan = '$hari_kerja_1_bulan', jam_kerja_1_hari = '$jam_kerja_1_hari'  WHERE id_pegawai ='$id' ";
    $result = mysqli_query($koneksi, $query);
    if(!$result){
        echo "Data gagal diubah!";
    }
    else{
        echo "Data berhasil diubah!";
        header("Location: http://localhost/sistem_hrm/tabel_pegawai/read.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Data Karyawan</title>
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

        input[type="text"] {
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
    <h1>Ubah Data Karyawan</h1>
    <form method="post">
        <input type="hidden" name="id" value="<?php echo $data['id_pegawai']; ?>">
        <label for="nik">NIK:</label>
        <input type="text" name="nik" id="nik" value="<?php echo $data['nik']; ?>">
        <br>
        <label for="nama">Nama:</label>
        <input type="text" name="nama" id="nama" value="<?php echo $data['nama']; ?>">
        <br>
        <label for="alamat">Alamat:</label>
        <input type="text" name="alamat" id="alamat" value="<?php echo $data['alamat']; ?>">
        <br>
        <label for="tgl_lahir">Tanggal Lahir:</label>
        <input type="text" name="tgl_lahir" id="tgl_lahir" value="<?php echo $data['tgl_lahir']; ?>">
        <br>
        <label for="divisi">Divisi:</label>
        <input type="text" name="divisi" id="divisi" value="<?php echo $data['divisi']; ?>">
        <br>
        <label for="gaji_pokok">Gaji Pokok:</label>
        <input type="text" name="gaji_pokok" id="gaji_pokok" value="<?php echo $data['gaji_pokok']; ?>">
        <br>
        <label for="hari_kerja_1_bulan">Hari_kerja_1_bulan:</label>
        <input type="text" name="hari_kerja_1_bulan" id="hari_kerja_1_bulan" value="<?php echo $data['hari_kerja_1_bulan']; ?>">
        <br>
        <label for="jam_kerja_1_hari">Jam Kerja 1 Hari:</label>
        <input type="text" name="jam_kerja_1_hari" id="jam_kerja_1_hari" value="<?php echo $data['jam_kerja_1_hari']; ?>">
        <br>
        <br>
        <button type="submit" name="submit">Ubah</button>
        <a href="http://localhost/sistem_hrm/tabel_pegawai/read.php" class="button">Kembali</a>
    </form>
    <a href= "http://localhost/sistem_hrm/logout.php" class= "button">logout</a>
</body>
</html>