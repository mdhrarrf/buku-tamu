<?php
// Panggil file function.php
require_once 'function.php';

// Jika ada id
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    if (hapus_tamu($id) > 0) {
        //Jika data berhasil di hapus maka akan muncul alert
        echo "<script> alert('Data berhasil di hapus!'); window.location='buku-tamu.php'; </script>";
        // refirect ke halaman buku-tamu.php
        echo "<script>windows.location.href='buku-tamu.php'</script>";
    } else {
        //Jika data gagal di hapus maka akan muncul alert
        echo "<script> alert('Data gagal di hapus!'); window.location='buku-tamu.php'; </script>";
    }
}
?>