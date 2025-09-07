<?php
session_start();
session_destroy();
setcookie('remember', '', time() - 3600, '/'); // hapus cookie
header("Location: login.php");
exit;
?>
