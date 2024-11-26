<?php
include "../config/koneksi.php";
$id = $_GET['id'];

try {
    $query = "DELETE FROM pegawai WHERE id_pegawai = '$id'";
    $result = mysqli_query($koneksi, $query);

    if (!$result) {
        throw new Exception("Data gagal dihapus!");
    } else {
        echo "Data berhasil dihapus!";
        header("Location: http://localhost/sistem_hrm/tabel_pegawai/read.php");
    }
} catch (Exception $e) {
    echo "Invalid operation! Ada data di tabel child yang terhubung!";
}
?>
