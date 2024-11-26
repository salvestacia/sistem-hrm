<?php
include 'config/koneksi.php';
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['logged_in'])) {
    header("HTTP/1.1 401 Unauthorized");
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$id_pegawai = $_SESSION['id_pegawai'];

// Ambil data absensi berdasarkan id_pegawai dengan durasi kerja
$sql = "SELECT 
        DATE(t1.tgl_tap) as tanggal,
        TIME_TO_SEC(TIMEDIFF(MAX(t2.tgl_tap), MIN(t1.tgl_tap))) / 3600 AS durasi_kerja
    FROM absensi t1
    INNER JOIN absensi t2 
        ON t1.id_pegawai = t2.id_pegawai AND DATE(t1.tgl_tap) = DATE(t2.tgl_tap)
    WHERE t1.id_pegawai = ? 
      AND t1.status = 'Tap In'
      AND t2.status = 'Tap Out'
    GROUP BY DATE(t1.tgl_tap)";

$stmt = $koneksi->prepare($sql);
$stmt->bind_param('i', $id_pegawai);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = [
        'tanggal' => $row['tanggal'],
        'durasi_kerja' => $row['durasi_kerja']
    ];
}

$stmt->close();
$koneksi->close();

header('Content-Type: application/json');
echo json_encode($data);
?>
