<?php
// Panggil file koneksi.php
require_once "koneksi.php";

// Membuat query ke / dari database
function query($query) {
    global $koneksi;
    $result = mysqli_query($koneksi, $query);
    $rows = [];
    while ( $row = mysqli_fetch_assoc($result) ) {
        $rows[] = $row;
    }
    return $rows;
}

// Function tambah data tamu
function tambah_tamu($data)
{
    global $koneksi;
    
    $kode       = htmlspecialchars($data["id_tamu"]);
    $tanggal    = date("Y-m-d");
    $nama_tamu  = htmlspecialchars($data["nama_tamu"]);
    $alamat     = htmlspecialchars($data["alamat"]);
    $no_hp      = htmlspecialchars($data["no_hp"]);
    $bertemu    = htmlspecialchars($data["bertemu"]);
    $kepentingan= htmlspecialchars($data["kepentingan"]);


    // Upload Gambar
    $gambar = uploadGambar();
    if( !$gambar) {
        return false;
    }

    // Cek apakah data tamu dengan nama + no_hp sudah ada
    $cek = mysqli_query($koneksi, "SELECT * FROM buku_tamu WHERE nama_tamu='$nama_tamu' AND no_hp='$no_hp'");

    if (mysqli_num_rows($cek) > 0) {
        // Kalau ada → return 0 (anggap gagal)
        return 0;
    }

    // Insert data baru
    $query = "INSERT INTO buku_tamu 
              (id_tamu, tanggal, nama_tamu, alamat, no_hp, bertemu, kepentingan, gambar) 
              VALUES 
              ('$kode', '$tanggal', '$nama_tamu', '$alamat', '$no_hp', '$bertemu', '$kepentingan', '$gambar')";

    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);
}

// Function ubah data tamu
function ubah_tamu($data)
{
    global $koneksi;

    $id = htmlspecialchars($data["id_tamu"]);
    $nama_tamu  = htmlspecialchars($data["nama_tamu"]);
    $alamat     = htmlspecialchars($data["alamat"]);
    $no_hp      = htmlspecialchars($data["no_hp"]);
    $bertemu    = htmlspecialchars($data["bertemu"]);
    $kepentingan= htmlspecialchars($data["kepentingan"]);
    $gambarLama = htmlspecialchars($data["gambarLama"]);

    // Cek apakah user pilih gambar baru atau tidak
    if ($_FILES['gambar']['error'] === 4) {
        $gambar = $gambarLama;
    } else {
        $gambar = uploadGambar();
    }

    $query = "UPDATE buku_tamu SET 
              nama_tamu = '$nama_tamu',
              alamat = '$alamat',
              no_hp = '$no_hp',
              bertemu = '$bertemu',
              kepentingan = '$kepentingan',
              gambar = '$gambar'
              WHERE id_tamu = '$id'";

    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);
}

// Function hapus data tamu
function hapus_tamu($id){

    global $koneksi;

    $query = "DELETE FROM buku_tamu WHERE id_tamu = '$id'";

    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);
}

// Tambah data user
function tambah_user($data) {
    global $koneksi;

    $id_user = htmlspecialchars($data["id_user"]);
    $username = htmlspecialchars($data["username"]);
    $password = htmlspecialchars($data["password"]);
    $user_role = htmlspecialchars($data["user_role"]);

    // Enkripsi password dengan hash
    $password = password_hash($password, PASSWORD_DEFAULT);

    $query = "INSERT INTO users (id_user, username, password, user_role) VALUES ('$id_user', '$username', '$password', '$user_role')";

    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);
}

// Function ubah data user
function ubah_user($data) {
    global $koneksi;

    $kode = htmlspecialchars($data["id_user"]);
    $username = htmlspecialchars($data["username"]);
    $user_role = htmlspecialchars($data["user_role"]);

    $query = "UPDATE users SET username = '$username', user_role = '$user_role' WHERE id_user = '$kode'";

    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);
}

// Function hapus data user
function hapus_user($id) {
    global $koneksi;

    $query = "DELETE FROM users WHERE id_user = '$id'";

    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);
}

// Function ganti password user
function ganti_password($data) {
    global $koneksi;

    $kode = htmlspecialchars($data["id_user"]);
    $password_baru = trim($data["password_baru"] ?? '');
    $password_konfirmasi = trim($data["password_konfirmasi"] ?? '');

    // Cek kalau kosong
    if (empty($password_baru) || empty($password_konfirmasi)) {
        return -2; // gagal: salah satu kosong
    }

    // Cek kalau tidak sama
    if ($password_baru !== $password_konfirmasi) {
        return -1; // gagal: beda
    }

    // Hash password baru
    $password_hash = password_hash($password_baru, PASSWORD_DEFAULT);

    // Update ke database
    $query = "UPDATE users SET password = '$password_hash' WHERE id_user = '$kode'";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        return 1; // sukses
    } else {
        return 0; // query gagal
    }
}

// Function untuk upload gambr
function uploadGambar()
{
    //ambil data file gambar dari variable $_FILES
    $namaFile = $_FILES['gambar']['name'];
    $ukuranFile = $_FILES['gambar']['size'];
    $error = $_FILES['gambar']['error'];
    $tmpName = $_FILES['gambar']['tmp_name'];

    // cek apakah tidak ada gambar yang diunggah
    if ($error === 4) {
        echo "<script>
                alert('pilih gambar terlebih dahulu!');
              </script>";
        return false;
    }

    // cek apakah yang diunggah adalah gambar
    $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
    $ekstensiGambar = explode('.', $namaFile);
    $ekstensiGambar = strtolower(end($ekstensiGambar));
    if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
        echo "<script>
                alert('File yang diunggah harus gambr!');
              </script>";
        return false;
    }

    // Cek jika ukurannya terlalu besar
    if($ukuranFile > 1000000) {
        echo "<script>
                alert('Ukuran gambar terlalu besar!');
              </script>";
        return false;
    }

    // Jika lolos pengecekan, gambar akan diunggah
    // Generate mana gambar baru dengan uniqid()
    $namaFileBaru = uniqid();
    $namaFileBaru .= '.';
    $namaFileBaru .= $ekstensiGambar;

    move_uploaded_file($tmpName,'assets/upload_gambar/'.$namaFileBaru);
    return $namaFileBaru;
}
?>
