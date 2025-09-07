<?php
require_once('function.php');
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

// Hanya boleh diakses oleh ADMIN
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Anda tidak memiliki akses ke halaman Buku Tamu');</script>";
    echo "<script>window.location.href='index.php';</script>";
    exit;
}

// Jika ada tombol simpan → proses dulu
if (isset($_POST['simpan'])) {
    if (tambah_tamu($_POST) > 0) {
        header("Location: buku-tamu.php?status=sukses");
        exit;
    } else {
        header("Location: buku-tamu.php?status=gagal");
        exit;
    }
}
?>

<?php include_once('templates/header.php'); ?>


<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Buku Tamu</h1>

    <?php
    if (isset($_GET['status'])) {
        if ($_GET['status'] == 'sukses') {
            echo '<div class="alert alert-success" role="alert">Data berhasil disimpan!</div>';
        } elseif ($_GET['status'] == 'gagal') {
            echo '<div class="alert alert-danger" role="alert">Data gagal disimpan!</div>';
        }
    }
    ?>


    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <button type="button" class="btn btn-primary btn-icon-split" data-toggle="modal" data-target="#tambahModal">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">Data Tamu</span>
            </button>

            <?php
            // Mengambil data barang dari tabel dengan kode terbesar
            $query = mysqli_query($koneksi, "SELECT max(id_tamu) as kodeTerbesar FROM buku_tamu");
            $data = mysqli_fetch_array($query);
            $kodeTamu = $data['kodeTerbesar'];

            // Mengambil angka dari kode barang terbesar, menggunakan fungsi substr dan diubah ke integer dengan (int)
            $urutan = (int) substr($kodeTamu, 2, 3);

            // Nomor yang diambil akan ditambah 1 untuk menentukan nomor urut berikutnya
            $urutan++;

            // Membuat kode barang baru
            // String sprint("%03s", $urutan); berfungsi untuk membuat string menjadi 3 karakter

            // Angka yang diambil tadi akan digabungkan dengan kode huruf yang kita inginkan, misalnya zt
            $huruf = "zt";
            $kodeTamu = $huruf . sprintf("%03s", $urutan);
            ?>

            <!-- Modal Tambah -->
            <div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="tambahModalLabel">Tambah Data Tamu</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <form method="post" action="" enctype="multipart/form-data">
                                <input type="hidden" name="id_tamu" value="<?= $kodeTamu ?>">
                                <div class="form-group row">
                                    <label for="nama_tamu" class="col-sm-3 col-form-label">Nama Tamu</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="nama_tamu" name="nama_tamu">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="alamat" class="col-sm-3 col-form-label">Alamat</label>
                                    <div class="col-sm-8">
                                        <textarea class="form-control" id="alamat" name="alamat"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="no_hp" class="col-sm-3 col-form-label">No. Telepon</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="no_hp" name="no_hp">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="bertemu" class="col-sm-3 col-form-label">Bertemu dg. </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="bertemu" name="bertemu">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="kepentingan" class="col-sm-3 col-form-label">Kepentingan</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="kepentingan" name="kepentingan">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="gambar" class="col-sm-3 col-form-label">Unggah Foto</label>
                                    <div class="custom-file col-sm-8">
                                        <input type="file" class="custom-file-input" id="gambar" name="gambar">
                                        <label class="custom-file-label" for="gambar">Choose File</label>
                                    </div>
                                </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                            <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nama Tamu</th>
                            <th>Alamat</th>
                            <th>No. Telp/HP</th>
                            <th>Bertemu Dengan</th>
                            <th>Kepentingan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Penomoran Auto-Increment
                        $no = 1;
                        $buku_tamu = query("SELECT * FROM buku_tamu");
                        foreach ($buku_tamu as $tamu) :
                        ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= $tamu['tanggal'] ?></td>
                                <td><?= $tamu['nama_tamu'] ?></td>
                                <td><?= $tamu['alamat'] ?></td>
                                <td><?= $tamu['no_hp'] ?></td>
                                <td><?= $tamu['bertemu'] ?></td>
                                <td><?= $tamu['kepentingan'] ?></td>
                                <td>
                                    <a href="edit-tamu.php?id=<?= $tamu['id_tamu'] ?>" class="btn btn-success">Edit</a>
                                    <a onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')" href="hapus-tamu.php?id=<?= $tamu['id_tamu'] ?>" class="btn btn-danger">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
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