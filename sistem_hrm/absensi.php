<?php
include 'config/koneksi.php';
session_start(); // Mulai session
// Periksa apakah pengguna sudah login
if (!isset($_SESSION['logged_in'])) {
    // Jika belum login, redirect kembali ke halaman login atau halaman lain
    header("Location: http://localhost/sistem_hrm/index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $id_pegawai = $_SESSION['id_pegawai'];
    date_default_timezone_set('Asia/Jakarta');
    $tgl_tap = date('Y-m-d-H:i:s');

    if ($action === 'tap_in') {
        // Masukkan data Tap In ke dalam database
        $sql = "INSERT INTO absensi (id_pegawai, tgl_tap, status) VALUES (?, ?, 'Tap In')";
    } elseif ($action === 'tap_out') {
        // Update data Tap Out di dalam database
        $sql = "INSERT INTO absensi (id_pegawai, tgl_tap, status) VALUES (?, ?, 'Tap Out')";
    }

    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param('is', $id_pegawai, $tgl_tap);
    if ($stmt->execute()) {
        echo "Berhasil melakukan $action.";
    } else {
        echo "Gagal melakukan $action: " . $stmt->error;
    }

    $stmt->close();
}

$koneksi->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Presensi</title>
    <style>
         a, button{
        display: inline-block;
        padding: 8px 16px;
        text-decoration: none;
        border: none;
        background-color: #80BCBD;
        color: white;
        border-radius: 5px;
        }

        a:hover, button:hover {
            background-color: #AAD9BB;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }  
        #container{
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            background-color: #F9F7C9;
            padding: 20px 30px;
            border-radius: 10px;
            border: 2px solid black;
            width: 80%; 
            max-width: 800px; 
            height: auto; 
            box-sizing: border-box; 
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body>
    <div id="container">
    <h1>Tap In / Tap Out System</h1> 
    <div id="clock" style="font-size: 24px; margin-bottom: 20px;"></div>
    <form action="absensi.php" method="post">
        <button type="submit" name="action" value="tap_in">Tap In</button>
        <button type="submit" name="action" value="tap_out">Tap Out</button>
        <a href= "http://localhost/sistem_hrm/homePage.php">Kembali</a>
        <a href= "http://localhost/sistem_hrm/logout.php">logout</a>
    </form>
    <canvas id="rekapChart" width="400" height="200"></canvas>
    </div>
    <script>
        function updateClock() {
            var now = new Date();
            var hours = now.getHours();
            var minutes = now.getMinutes();
            var seconds = now.getSeconds();
            var ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12; // the hour '0' should be '12'
            minutes = minutes < 10 ? '0' + minutes : minutes;
            seconds = seconds < 10 ? '0' + seconds : seconds;
            var strTime = hours + ':' + minutes + ':' + seconds + ' ' + ampm;
            document.getElementById('clock').innerHTML = strTime;
        }
        setInterval(updateClock, 1000);
        updateClock(); // initial call to display the clock immediately

        async function fetchRekapAbsensi() {
            try {
                const response = await fetch('http://localhost/sistem_hrm/get_rekap_absensi.php');
                if (!response.ok) throw new Error('Failed to fetch data');
                const data = await response.json();
                return data;
            } catch (error) {
                console.error('Error:', error);
                return [];
            }
        }

        function renderChart(data) {
            const labels = data.map(item => item.tanggal);
            const durasiKerja = data.map(item => item.durasi_kerja);

            // Fungsi untuk mengonversi durasi dalam format desimal (jam) menjadi "X jam Y menit"
            function formatDuration(durasi) {
                const jam = Math.floor(durasi); // Mengambil bagian jam
                const menit = Math.round((durasi - jam) * 60); // Mengambil bagian menit
                return `${jam} jam ${menit} menit`;
            }

            const ctx = document.getElementById('rekapChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Durasi Kerja (Jam)',
                            data: durasiKerja,
                            backgroundColor: 'rgba(54, 162, 235, 0.6)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'top' },
                        title: { display: true, text: 'Rekap Durasi Kerja Pribadi' },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    // Mengonversi durasi yang ada dalam tooltip menjadi format jam dan menit
                                    const durasi = tooltipItem.raw;
                                    return formatDuration(durasi);
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                autoSkip: false,   
                                maxTicksLimit: 31,      // Menampilkan hingga 31 label
                                rotation: 45,     // Rotasi label untuk menghindari tumpang tindih
                            },
                        },
                        y: {
                            beginAtZero: true,
                            title: { display: true, text: 'Durasi (Jam)' },
                        },
                    },
                },
            });
        }

        async function init() {
            const data = await fetchRekapAbsensi();
            renderChart(data);
        }

        init();
    </script>
</body>
</html>