<?php
include 'config/koneksi.php';
session_start(); // Mulai session
// Check if the user is logged in
if (!isset($_SESSION['logged_in'])) {
    header("Location: http://localhost/sistem_hrm/index.php");
    exit();
}

// Fetch user role
$id_user = $_SESSION['id_user'];
$sql_role = "SELECT hak_akses FROM user WHERE id_user = ?";
$stmt_role = $koneksi->prepare($sql_role);
$stmt_role->bind_param("i", $id_user);
$stmt_role->execute();
$result_role = $stmt_role->get_result();
$user = $result_role->fetch_assoc();
$hak_akses = $user['hak_akses'];

// Fetch allowed features for the user role
$sql_access = "SELECT link, fitur FROM hak_akses WHERE id_hak_akses = ?";
$stmt_access = $koneksi->prepare($sql_access);
$stmt_access->bind_param("i", $hak_akses);
$stmt_access->execute();
$result_access = $stmt_access->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
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

        h1, h2{
            text-align: center;
        }
        
        ul{
            display: flex;
            justify-content: space-around;
            align-items: center;
            height: 50vh;
            flex-wrap: wrap; 
            gap: 20px; 
        }

        ul li{
            background-color: #F9F7C9;
            list-style-type: none;
            border: 2px solid black;
            padding: 30px;
            border-radius: 10px;
            width: 25%; 
            text-align: center;
        }

        a {
            display: inline-block;
            padding: 8px 16px;
            text-decoration: none;
            background-color: #80BCBD;
            color: white;
            border-radius: 5px;
        }

        a:hover {
            background-color: #AAD9BB;
        }

    </style>
</head>
<body>
    <div class="container">
        <h1>Sistem Informasi HRM</h1>
        <a href="http://localhost/sistem_hrm/logout.php">Logout</a>
        <ul>
            <?php while($row = $result_access->fetch_assoc()): ?>
            <li>
                <h3><?php echo $row['fitur']; ?></h3>
                <a href='<?php echo $row['link']; ?>'>Masuk</a>
            </li>
            <?php endwhile; ?>
        </ul>
    </div>
</body>
</html>

<?php
$stmt_role->close();
$stmt_access->close();
$koneksi->close();
?>