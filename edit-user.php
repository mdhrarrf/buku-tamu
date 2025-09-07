<?php
require_once('function.php');

// Jika ada id user di URL
if (isset($_GET['id'])) {
    $users = $_GET['id'];
    // Ambil data user yang sesuai dengan users
    $data = query("SELECT * FROM users WHERE id_user = '$users'");
    if ($data) {
        $data = $data[0];
    } else {
        // Jika data tidak ditemukan, redirect ke users.php
        header("Location: users.php");
        exit;
    }
}

// Jika ada tombol simpan → proses dulu
if (isset($_POST['simpan'])) {
    if (ubah_user($_POST) > 0) {
        $id_user = trim($_POST['id_user']); // buang spasi & newline
        header("Location: edit-user.php?id=" . urlencode($id_user) . "&status=sukses");
        exit;

    } else {
        $id_user = trim($_POST['id_user']);
        header("Location: edit-user.php?id=" . urlencode($id_user) . "&status=gagal");
        exit;
    }
}
?>

<?php include_once('templates/header.php'); ?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Ubah Data user</h1>

    <?php
    if (isset($_GET['status'])) {
        if ($_GET['status'] == 'sukses') {
            echo '<div class="alert alert-success" role="alert">Data berhasil diubah!</div>';
        } elseif ($_GET['status'] == 'gagal') {
            echo '<div class="alert alert-danger" role="alert">Data gagal diubah!</div>';
        }
    }
    ?>

    <!-- Konten Edit Data user -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6>Data User</h6>
        </div>
        <div class="card-body">
            <form method="post" action="">
                <input type="hidden" name="id_user" id="id_user" value="<?= $data['id_user'] ?>">
                <div class="form-group row">
                    <label for="nama_user" class="col-sm-3 col-form-label">Username</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="username" name="username" value="<?= isset($data['username']) ? $data['username'] : '' ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="user_role" class="col-sm-3 col-form-label">User Role</label>
                    <div class="col-sm-8">
                        <select name="user_role" id="user_role" class="form-control">
                            <option value="admin" <?= $data['user_role'] == 'admin' ? 'selected' : '' ?>>Administrator</option>
                            <option value="operator" <?= $data['user_role'] == 'operator' ? 'selected' : '' ?>>Operator</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-sm-3 col-form-label"></label>
                    <div class="col-sm-8 d-flex justify-content-end">
                        <div>
                            <a href="users.php" class="btn btn-danger btn-icon-split" type="button">
                                <span class="icon text-white-50">
                                    <li class="fas fa-chevron-left"></li>
                                </span>
                                <span class="text">Kembali</span>
                            </a>
                            <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.container-fluid -->

<?php
include_once('templates/footer.php');
?>