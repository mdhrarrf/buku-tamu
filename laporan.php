<?php
require_once('function.php');
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}
?>

<?php
include_once('templates/header.php');

if (isset($_POST['tampilkan'])) {
    $p_awal = $_POST['p_awal'];
    $p_akhir = $_POST['p_akhir'];

    $link = "export-laporan.php?cari=true&p_awal=$p_awal&p_akhir=$p_akhir";

    // Query sesuai dengan keyword
    $buku_tamu = query("SELECT * FROM buku_tamu WHERE tanggal BETWEEN '$p_awal' AND '$p_akhir'");
} else {
    // Query ambil semua data buku tamu
    $buku_tamu = query("SELECT * FROM buku_tamu ORDER BY tanggal DESC");
}
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Laporan Tamu</h1>

    <div class="row mx-auto d-flex justify-content-center">

        <!-- Periode Awal -->
        <div class="col-xl-6 col-lg-8 col-md-10 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <form action="" method="post">
                                <div class="font-weight-bold text-primary text-uppercase mb-2">
                                    Periode
                                </div>
                                <div class="d-flex flex-wrap align-items-center">
                                    <div class="mb-2 me-2">
                                        <input type="date" class="form-control" id="p_awal" name="p_awal" required>
                                    </div>
                                    <span class="mx-2">s.d</span>
                                    <div class="mb-2 me-2">
                                        <input type="date" class="form-control" id="p_akhir" name="p_akhir" required>
                                    </div>
                                    <div class="col-auto mb-2">
                                        <button name="tampilkan" type="submit" class="btn btn-primary">Tampilkan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Periode Akhir -->

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a href="<?= isset($_POST['tampilkan']) ? $link : 'export-laporan.php'; ?>" target="_blank" class="btn btn-success btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-file-excel"></i>
                </span>
                <span class="text">Export Laporan</span>
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <td>No</td>
                            <td>Tanggal</td>
                            <td>Nama Tamu</td>
                            <td>Alamat</td>
                            <td>No. Telp</td>
                            <td>Bertemu Dengan</td>
                            <td>Kepentingan</td>
                            <td>Aksi</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // penomoran auto increment
                        $no = 1;

                        foreach ($buku_tamu as $tamu) : ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= $tamu['tanggal']; ?></td>
                                <td><?= $tamu['nama_tamu']; ?></td>
                                <td><?= $tamu['alamat']; ?></td>
                                <td><?= $tamu['no_hp']; ?></td>
                                <td><?= $tamu['bertemu']; ?></td>
                                <td><?= $tamu['kepentingan']; ?></td>
                                <td>
                                    <a class="btn btn-success" href="edit-tamu.php?id=<?= $tamu['id_tamu']; ?>">Edit</a>
                                    <a onclick="return confirm('Yakin mau hapus?')" class="btn btn-danger" href="hapus-tamu.php?id=<?= $tamu['id_tamu']; ?>">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->

<?php
include_once('templates/footer.php');
?>