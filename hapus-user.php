<?php
// Panggil file function.php
require_once 'function.php';

// Jika ada id
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    if (hapus_user($id) > 0) {
        //Jika data berhasil di hapus maka akan muncul alert
        echo "<script> alert('Data berhasil di hapus!'); window.location='users.php'; </script>";
        // refirect ke halaman users.php
        echo "<script>windows.location.href='users.php'</script>";
    } else {
        //Jika data gagal di hapus maka akan muncul alert
        echo "<script> alert('Data gagal di hapus!'); window.location='ursers.php'; </script>";
    }
}
?>