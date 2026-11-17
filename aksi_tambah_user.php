<?php
require_once 'auth.php';
require_once 'koneksi.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Hanya admin yang boleh menambahkan akun.
if (strtolower($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: form_tambah_user.php");
    exit;
}

function balik($pesan) {
    header("Location: form_tambah_user.php?error=" . urlencode($pesan));
    exit;
}

$role     = strtolower(trim($_POST['role'] ?? ''));
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if (!in_array($role, ['admin', 'guru', 'siswa'], true)) {
    balik("Role tidak valid.");
}

if ($username === '' || $password === '') {
    balik("Username dan password wajib diisi.");
}

// Periksa username yang sudah digunakan.
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT id FROM users WHERE username = ?"
);

if (!$stmt) {
    balik("Gagal memeriksa username.");
}

mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);

$hasil = mysqli_stmt_get_result($stmt);
$sudahAda = mysqli_fetch_assoc($hasil);

mysqli_stmt_close($stmt);

if ($sudahAda) {
    balik("Username sudah dipakai.");
}

// Validasi profil siswa.
if ($role === 'siswa') {
    $nis   = trim($_POST['nis'] ?? '');
    $nama  = trim($_POST['nama_siswa'] ?? '');
    $kelas = trim($_POST['kelas'] ?? '');
    $jk    = $_POST['jk_siswa'] ?? '';

    if ($nis === '' || $nama === '') {
        balik("NIS dan nama siswa wajib diisi.");
    }
}

// Validasi profil guru.
if ($role === 'guru') {
    $nip  = trim($_POST['nip'] ?? '');
    $nama = trim($_POST['nama_guru'] ?? '');
    $jk   = $_POST['jk_guru'] ?? '';

    if ($nama === '') {
        balik("Nama guru wajib diisi.");
    }
}

mysqli_begin_transaction($koneksi);

try {
    // Enkripsi password sebelum disimpan.
    $passwordHash = password_hash(
        $password,
        PASSWORD_DEFAULT
    );

    // Simpan akun pengguna.
    $stmt = mysqli_prepare(
        $koneksi,
        "INSERT INTO users (username, password, role)
         VALUES (?, ?, ?)"
    );

    if (!$stmt) {
        throw new Exception("Gagal menyiapkan data akun.");
    }

    mysqli_stmt_bind_param(
        $stmt,
        "sss",
        $username,
        $passwordHash,
        $role
    );

    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception(
            "Gagal menyimpan akun. Periksa kembali data yang dimasukkan."
        );
    }

    $user_id = mysqli_insert_id($koneksi);
    mysqli_stmt_close($stmt);

    // Simpan profil siswa.
    if ($role === 'siswa') {
        $stmt = mysqli_prepare(
            $koneksi,
            "INSERT INTO siswa
             (user_id, nis, nama, kelas, jenis_kelamin)
             VALUES (?, ?, ?, ?, ?)"
        );

        if (!$stmt) {
            throw new Exception("Gagal menyiapkan data siswa.");
        }

        mysqli_stmt_bind_param(
            $stmt,
            "issss",
            $user_id,
            $nis,
            $nama,
            $kelas,
            $jk
        );

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Gagal menyimpan data siswa.");
        }

        mysqli_stmt_close($stmt);
    }

    // Simpan profil guru.
    if ($role === 'guru') {
        $stmt = mysqli_prepare(
            $koneksi,
            "INSERT INTO guru
             (user_id, nip, nama, jenis_kelamin)
             VALUES (?, ?, ?, ?)"
        );

        if (!$stmt) {
            throw new Exception("Gagal menyiapkan data guru.");
        }

        mysqli_stmt_bind_param(
            $stmt,
            "isss",
            $user_id,
            $nip,
            $nama,
            $jk
        );

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Gagal menyimpan data guru.");
        }

        mysqli_stmt_close($stmt);
    }

    mysqli_commit($koneksi);

    // Kembali ke halaman daftar user.
    header("Location: daftar_user.php?status=berhasil");
    exit;

} catch (Throwable $e) {
    mysqli_rollback($koneksi);
    balik($e->getMessage());
}