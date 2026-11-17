<?php
require_once 'auth.php';
require_once 'koneksi.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Hanya admin yang boleh mengedit akun.
if (strtolower($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: daftar_user.php");
    exit;
}

$id       = (int) ($_POST['id'] ?? 0);
$role     = strtolower(trim($_POST['role'] ?? ''));
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

function balik($role, $id, $pesan) {
    header(
        "Location: form_edit_user.php?role=" .
        urlencode($role) .
        "&id=" . (int) $id .
        "&error=" . urlencode($pesan)
    );
    exit;
}

if ($id <= 0 || !in_array($role, ['admin', 'guru', 'siswa'], true)) {
    header("Location: daftar_user.php");
    exit;
}

if ($username === '') {
    balik($role, $id, "Username wajib diisi.");
}

// Pastikan username tidak dipakai akun lain.
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT id FROM users WHERE username = ? AND id <> ?"
);

if (!$stmt) {
    balik($role, $id, "Gagal memeriksa username.");
}

mysqli_stmt_bind_param($stmt, "si", $username, $id);
mysqli_stmt_execute($stmt);

$hasil = mysqli_stmt_get_result($stmt);
$userAda = mysqli_fetch_assoc($hasil);

mysqli_stmt_close($stmt);

if ($userAda) {
    balik($role, $id, "Username sudah dipakai user lain.");
}

// Pastikan akun yang diedit memang ada dan rolenya sesuai.
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT id FROM users WHERE id = ? AND role = ?"
);

if (!$stmt) {
    balik($role, $id, "Gagal memeriksa akun.");
}

mysqli_stmt_bind_param($stmt, "is", $id, $role);
mysqli_stmt_execute($stmt);

$akun = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$akun) {
    balik($role, $id, "Akun tidak ditemukan atau role tidak sesuai.");
}

// Validasi data profil sebelum mulai transaksi.
if ($role === 'siswa') {
    $nis   = trim($_POST['nis'] ?? '');
    $nama  = trim($_POST['nama'] ?? '');
    $kelas = trim($_POST['kelas'] ?? '');
    $jk    = $_POST['jenis_kelamin'] ?? '';

    if ($nis === '' || $nama === '') {
        balik($role, $id, "NIS dan nama siswa wajib diisi.");
    }
}

if ($role === 'guru') {
    $nip  = trim($_POST['nip'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $jk   = $_POST['jenis_kelamin'] ?? '';

    if ($nama === '') {
        balik($role, $id, "Nama guru wajib diisi.");
    }
}

mysqli_begin_transaction($koneksi);

try {
    // Perbarui akun. Password kosong berarti tidak diubah.
    if ($password !== '') {
        $passwordHash = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $stmt = mysqli_prepare(
            $koneksi,
            "UPDATE users SET username = ?, password = ? WHERE id = ?"
        );

        if (!$stmt) {
            throw new Exception("Gagal menyiapkan perubahan akun.");
        }

        mysqli_stmt_bind_param(
            $stmt,
            "ssi",
            $username,
            $passwordHash,
            $id
        );
    } else {
        $stmt = mysqli_prepare(
            $koneksi,
            "UPDATE users SET username = ? WHERE id = ?"
        );

        if (!$stmt) {
            throw new Exception("Gagal menyiapkan perubahan akun.");
        }

        mysqli_stmt_bind_param(
            $stmt,
            "si",
            $username,
            $id
        );
    }

    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Gagal mengubah akun.");
    }

    mysqli_stmt_close($stmt);

    // Perbarui profil siswa tanpa mengubah akun login.
    if ($role === 'siswa') {
        $stmt = mysqli_prepare(
            $koneksi,
            "UPDATE siswa
             SET nis = ?, nama = ?, kelas = ?, jenis_kelamin = ?
             WHERE user_id = ?"
        );

        if (!$stmt) {
            throw new Exception("Gagal menyiapkan perubahan siswa.");
        }

        mysqli_stmt_bind_param(
            $stmt,
            "ssssi",
            $nis,
            $nama,
            $kelas,
            $jk,
            $id
        );

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Gagal mengubah data siswa.");
        }

        mysqli_stmt_close($stmt);

        if (mysqli_affected_rows($koneksi) === 0) {
            // Nol perubahan bisa berarti datanya sama atau profil tidak ada.
            $cek = mysqli_prepare(
                $koneksi,
                "SELECT user_id FROM siswa WHERE user_id = ?"
            );

            mysqli_stmt_bind_param($cek, "i", $id);
            mysqli_stmt_execute($cek);

            $profil = mysqli_fetch_assoc(mysqli_stmt_get_result($cek));
            mysqli_stmt_close($cek);

            if (!$profil) {
                throw new Exception("Profil siswa tidak ditemukan.");
            }
        }
    }

    // Perbarui profil guru tanpa mengubah akun login.
    if ($role === 'guru') {
        $stmt = mysqli_prepare(
            $koneksi,
            "UPDATE guru
             SET nip = ?, nama = ?, jenis_kelamin = ?
             WHERE user_id = ?"
        );

        if (!$stmt) {
            throw new Exception("Gagal menyiapkan perubahan guru.");
        }

        mysqli_stmt_bind_param(
            $stmt,
            "sssi",
            $nip,
            $nama,
            $jk,
            $id
        );

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Gagal mengubah data guru.");
        }

        mysqli_stmt_close($stmt);

        if (mysqli_affected_rows($koneksi) === 0) {
            $cek = mysqli_prepare(
                $koneksi,
                "SELECT user_id FROM guru WHERE user_id = ?"
            );

            mysqli_stmt_bind_param($cek, "i", $id);
            mysqli_stmt_execute($cek);

            $profil = mysqli_fetch_assoc(mysqli_stmt_get_result($cek));
            mysqli_stmt_close($cek);

            if (!$profil) {
                throw new Exception("Profil guru tidak ditemukan.");
            }
        }
    }

    mysqli_commit($koneksi);

    header("Location: daftar_user.php?status=diubah");
    exit;

} catch (Throwable $e) {
    mysqli_rollback($koneksi);
    balik($role, $id, $e->getMessage());
}