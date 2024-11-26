<?php
session_start();

$_SESSION = array();
session_destroy();
header("Location: http://localhost/sistem_hrm/index.php");
exit();
?>