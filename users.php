<?php
require_once('function.php');
session_start();

// pengecekan user role bukan operator maka tidak boleh mengakses halaman
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'operator') {
    echo "<script>alert('Anda tidak memiliki akses ke halaman Users')</script>";
    echo "<script>window.location.href='index.php'</script>";
    exit;
}

// Jika ada tombol simpan → proses dulu
if (isset($_POST['simpan'])) {
    if (tambah_user($_POST) > 0) {
        header("Location: users.php?status=sukses");
        exit;
    } else {
        header("Location: users.php?status=gagal");
        exit;
    }
}
?>

<?php include_once('templates/header.php'); ?>


<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Data User</h1>

    <?php
    // Alert Menampilkan Berhasil/Gagal data disimpan
    if (isset($_GET['status'])) {
        if ($_GET['status'] == 'sukses') {
            echo '<div class="alert alert-success" role="alert">Data berhasil disimpan!</div>';
        } elseif ($_GET['status'] == 'gagal') {
            echo '<div class="alert alert-danger" role="alert">Data gagal disimpan!</div>';
        }
    }

    // Alert menampilkan berhasil gagalnya password diubah
    if (isset($_POST['ganti_password'])) {
        $result = ganti_password($_POST);

        if ($result === 1) {
            echo '<div class="alert alert-success">Password berhasil diubah!</div>';
        } elseif ($result === -1) {
            echo '<div class="alert alert-warning">Password baru dan konfirmasi tidak sama!</div>';
        } elseif ($result === -2) {
            echo '<div class="alert alert-warning">Password tidak boleh kosong!</div>';
        } else {
            echo '<div class="alert alert-danger">Password gagal diubah!</div>';
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
                <span class="text">Data User</span>
            </button>

            <?php
            // Mengambil data barang dari tabel dengan kode terbesar
            $query = mysqli_query($koneksi, "SELECT max(id_user) as kodeTerbesar FROM users");
            $data = mysqli_fetch_array($query);
            $kodeuser = $data['kodeTerbesar'];
            if ($kodeuser === null) $kodeuser = ''; // tambahkan baris ini

            // Mengambil angka dari kode barang terbesar, menggunakan fungsi substr dan diubah ke integer dengan (int)
            $urutan = (int) substr($kodeuser, 2, 3);

            // Nomor yang diambil akan ditambah 1 untuk menentukan nomor urut berikutnya
            $urutan++;

            // Membuat kode barang baru
            // String sprint("%03s", $urutan); berfungsi untuk membuat string menjadi 3 karakter

            // Angka yang diambil tadi akan digabungkan dengan kode huruf yang kita inginkan, misalnya zt
            $huruf = "zt";
            $kodeuser = $huruf . sprintf("%03s", $urutan);
            ?>

            <!-- Modal Tambah -->
            <div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="tambahModalLabel">Data User</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <form method="post" action="">
                                <input type="hidden" name="id_user" value="<?= $kodeuser ?>">
                                <div class="form-group row">
                                    <label for="username" class="col-sm-3 col-form-label">Username</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="username" name="username">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="password" class="col-sm-3 col-form-label">Password</label>
                                    <div class="col-sm-8">
                                        <input type="password" class="form-control" id="password" name="password">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="user_role" class="col-sm-3 col-form-label">User Role</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" id="user_role" name="user_role">
                                            <option value="admin">Administrator</option>
                                            <option value="operator">Operator</option>
                                        </select>
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

            <!-- Modal Ganti -->
            <div class="modal fade" id="gantiPassword" tabindex="-1" aria-labelledby="gantiPasswordLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="gantiPasswordLabel">Ganti Password</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <form method="post" action="">
                                <input type="hidden" name="id_user" id="id_user">

                                <div class="form-group row">
                                    <label for="password_baru" class="col-sm-4 col-form-label">Password Baru</label>
                                    <div class="col-sm-7">
                                        <input type="password" class="form-control" id="password_baru" name="password_baru">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="password_konfirmasi" class="col-sm-4 col-form-label">Konfirmasi Password</label>
                                    <div class="col-sm-7">
                                        <input type="password" class="form-control" id="password_konfirmasi" name="password_konfirmasi">
                                    </div>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                            <button type="submit" name="ganti_password" class="btn btn-primary">Simpan</button>
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
                            <th>Username</th>
                            <th>User Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Penomoran Auto-Increment
                        $no = 1;
                        // Query untuk memanggil semua data dari tabel users
                        $users = query("SELECT * FROM users");
                        foreach ($users as $user) :
                        ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= $user['username'] ?></td>
                                <td><?= $user['user_role'] ?></td>
                                <td>
                                    <button type="button" data-toggle="modal" data-target="#gantiPassword" data-id="<?= $user['id_user'] ?>" class="btn btn-info btn-icon-split">
                                        <span class="text">Ganti Password</span>
                                    </button>
                                    <a href="edit-user.php?id=<?= $user['id_user'] ?>" class="btn btn-success">Edit</a>
                                    <a onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')" href="hapus-user.php?id=<?= $user['id_user'] ?>" class="btn btn-danger">Hapus</a>
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