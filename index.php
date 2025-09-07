<?php
session_start();

if(!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

include_once('templates/header.php');
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Dashboard Admin</h1>
    <p>Selamat datang, <?= $_SESSION['username']; ?> 👋</p>
    <p>
        Role Anda,
        <?= isset($_SESSION['role']) ? $_SESSION['role'] : 'Tidak ada role'; ?>
    </p>
</div>
<!-- /.container-fluid -->

<?php
include_once('templates/footer.php');
?>