<?php
session_start();
include 'koneksi.php';

$username = mysqli_real_escape_string($koneksi, trim($_POST['username']));
$password = $_POST['password'];

$query = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
$d = mysqli_fetch_assoc($query);

$cocok = false;
if ($d) {
    if (password_verify($password, $d['password'])) {
        $cocok = true;
    } elseif ($d['password'] == md5($password) || $d['password'] == $password) {
        // password lama (md5 / teks biasa), langsung diubah ke password_hash
        $cocok = true;
        $hash_baru = password_hash($password, PASSWORD_DEFAULT);
        mysqli_query($koneksi, "UPDATE users SET password = '$hash_baru' WHERE id = " . $d['id']);
    }
}

if ($cocok) {
    // nama diambil dari tabel guru / siswa, admin pakai username
    $nama = $d['username'];
    if ($d['role'] == 'guru') {
        $q = mysqli_query($koneksi, "SELECT nama FROM guru WHERE user_id = " . $d['id']);
        $r = mysqli_fetch_assoc($q);
        if ($r) $nama = $r['nama'];
    } elseif ($d['role'] == 'siswa') {
        $q = mysqli_query($koneksi, "SELECT nama FROM siswa WHERE user_id = " . $d['id']);
        $r = mysqli_fetch_assoc($q);
        if ($r) $nama = $r['nama'];
    }

    session_regenerate_id(true);
    $_SESSION['user_id'] = $d['id'];
    $_SESSION['username'] = $d['username'];
    $_SESSION['nama'] = $nama;
    $_SESSION['role'] = $d['role'];

    // setiap role masuk ke halamannya sendiri
    if ($d['role'] == 'admin') {
        header("location:daftar_user_admin.php");
    } elseif ($d['role'] == 'siswa') {
        header("location:daftar_siswa.php");
    } else {
        header("location:index.php");
    }
} else {
    header("location:login.php?error=" . urlencode("Username atau password salah"));
}