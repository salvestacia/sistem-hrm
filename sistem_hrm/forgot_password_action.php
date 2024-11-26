<?php
session_start();
include 'config/koneksi.php'; // Database connection

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer-master/src/Exception.php';
require './PHPMailer-master/src/PHPMailer.php';
require './PHPMailer-master/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the email from the form
    $email = isset($_POST['email']) ? $_POST['email'] : '';

    // Fetch the user's email from the database
    $query = "SELECT id_user, email FROM user WHERE email = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($id, $stored_email);
        $stmt->fetch();

        // Generate a unique reset token and expiration time
        $reset_token = bin2hex(random_bytes(32));
        date_default_timezone_set('Asia/Jakarta');
        $expiry_time = date("Y-m-d H:i:s", strtotime('+1 hour')); // Link valid for 1 hour

        // Update the database with the reset token and expiration time
        $query_update = "UPDATE user SET reset_token = ?, reset_expires = ? WHERE id_user = ?";
        $stmt_update = $koneksi->prepare($query_update);
        $stmt_update->bind_param('ssi', $reset_token, $expiry_time, $id);
        $stmt_update->execute();

        if ($stmt_update->execute()) {
            // Send the reset email using PHPMailer
            $reset_link = "http://localhost/sistem_hrm/reset_password.php?token=" . $reset_token;

            // Use PHPMailer to send the reset email
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'darrenchrist2@gmail.com'; // Replace with your Gmail address
            $mail->Password = 'xitlwwxvtkndsmee'; // Replace with your Gmail password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('darrenchrist2@gmail.com', 'Sistem HRM'); // Set your "From" address
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body    = "Click <a href='" . $reset_link . "'>here</a> to reset your password. This link will expire in 1 hour.";

            if ($mail->send()) {
                echo "<script>
                alert('Password reset email sent!!!');
                window.location.href='http://localhost/sistem_hrm/index.php';
                </script>";
            } else {
                echo "Failed to send email.";
            }
        }
    } else {
        // Email not found in the database
        echo "<script>
        alert('The email entered is not associated with any account.');
        window.location.href='http://localhost/sistem_hrm/forgot_password.php';
        </script>";
    }

    $stmt->close();
    $koneksi->close();
}
?>
