<?php
include "../config/koneksi.php";
$id = $_GET['id'];

try {
    $query = "DELETE FROM payroll WHERE id_payroll = '$id'";
    $result = mysqli_query($koneksi, $query);

    if (!$result) {
        throw new Exception("Data gagal dihapus!");
    } else {
        echo "Data berhasil dihapus!";
        header("Location: http://localhost/sistem_hrm/payroll/readPayroll.php");
    }
} catch (Exception $e) {
    echo "Invalid operation! Ada data di tabel child yang terhubung!";
}
?>
