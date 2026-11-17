<?php
require_once 'auth.php';
require_once 'koneksi.php';
wajibLogin();

// setiap role masuk ke halamannya sendiri
if ($_SESSION['role'] == 'admin') {
    header("location:daftar_user_admin.php");
    exit;
}
if ($_SESSION['role'] == 'guru') {
    header("location:daftar_guru.php");
    exit;
}

header("location:daftar_siswa.php");
exit;