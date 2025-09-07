<?php
require_once('function.php');

// Jika ada id tamu di URL
if (isset($_GET['id'])) {
    $id_tamu = $_GET['id'];
    // Ambil data tamu yang sesuai dengan id_tamu
    $data = query("SELECT * FROM buku_tamu WHERE id_tamu = '$id_tamu'");
    if ($data) {
        $data = $data[0];
    } else {
        // Jika data tidak ditemukan, redirect ke buku-tamu.php
        header("Location: buku-tamu.php");
        exit;
    }
}

// Jika ada tombol simpan → proses dulu
if (isset($_POST['simpan'])) {
    if (ubah_tamu($_POST) > 0) {
        header("Location: edit-tamu.php?id=" . $_POST['id_tamu'] . "&status=sukses");
        exit;
    } else {
        header("Location: edit-tamu.php?id=" . $_POST['id_tamu'] . "&status=gagal");
        exit;
    }
}
?>

<?php include_once('templates/header.php'); ?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Ubah Data Tamu</h1>

    <?php
    if (isset($_GET['status'])) {
        if ($_GET['status'] == 'sukses') {
            echo '<div class="alert alert-success" role="alert">Data berhasil diubah!</div>';
        } elseif ($_GET['status'] == 'gagal') {
            echo '<div class="alert alert-danger" role="alert">Data gagal diubah!</div>';
        }
    }
    ?>

    <!-- Konten Edit Data Tamu -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6>Data Tamu</h6>
        </div>
        <div class="card-body">
            <form method="post" action="" enctype="multipart/form-data">
                <input type="hidden" name="id_tamu" value="<?= $id_tamu ?>">
                <div class="form-group row">
                    <label for="nama_tamu" class="col-sm-3 col-form-label">Nama Tamu</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="nama_tamu" name="nama_tamu" value="<?= isset($data['nama_tamu']) ? $data['nama_tamu'] : '' ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="alamat" class="col-sm-3 col-form-label">Alamat</label>
                    <div class="col-sm-8">
                        <textarea class="form-control" id="alamat" name="alamat"><?= isset($data['alamat']) ? $data['alamat'] : '' ?></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="no_hp" class="col-sm-3 col-form-label">No. Telepon</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="no_hp" name="no_hp" value="<?= isset($data['no_hp']) ? $data['no_hp'] : '' ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="bertemu" class="col-sm-3 col-form-label">Bertemu dg. </label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="bertemu" name="bertemu" value="<?= isset($data['bertemu']) ? $data['bertemu'] : '' ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="kepentingan" class="col-sm-3 col-form-label">Kepentingan</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="kepentingan" name="kepentingan" value="<?= isset($data['kepentingan']) ? $data['kepentingan'] : '' ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="gambar" class="col-sm-3 col-form-label">Gambar Foto</label>
                    <div class="col-sm-8">
                        <input src="assets/upload_gambar/<?= $data['gambar']; ?>" type="file" class="form-control-file" id="gambar" name="gambar">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-8 offset-sm-3">
                        <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
                        <a href="buku-tamu.php" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.container-fl
uid -->

<?php
include_once('templates/footer.php');
?>