<?php
require_once 'auth.php';
require_once 'koneksi.php';

// supaya kegagalan simpan jadi exception dan bisa di-rollback (aman di PHP 7 maupun 8)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$error = '';
$username = '';
$nis = '';
$nama = '';
$kelas = '';
$jk = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $nis      = trim($_POST['nis'] ?? '');
    $nama     = trim($_POST['nama_lengkap'] ?? '');
    $kelas    = trim($_POST['kelas'] ?? '');
    $jk       = $_POST['jenis_kelamin'] ?? '';

    // kelas diseragamkan: xi-rpl-1 jadi XI RPL 1
    $kelas = strtoupper(str_replace('-', ' ', $kelas));

    if ($username === '' || $password === '' || $nis === '' || $nama === '' || $kelas === '' || $jk === '') {
        $error = "Semua kolom wajib diisi.";
    } elseif ($jk !== 'L' && $jk !== 'P') {
        $error = "Jenis kelamin tidak valid.";
    } elseif (strlen($nis) > 10 || strlen($kelas) > 10) {
        $error = "NIS dan kelas maksimal 10 karakter.";
    } elseif (strlen($username) > 50 || strlen($nama) > 100) {
        $error = "Username maksimal 50 karakter dan nama maksimal 100 karakter.";
    } elseif (strlen($password) < 6) {
        $error = "Password minimal 6 karakter.";
    } else {
        try {
            mysqli_begin_transaction($koneksi);

            // cek username sudah dipakai atau belum
            $stmt = mysqli_prepare($koneksi, "SELECT id FROM users WHERE username = ?");
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            $ada_user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

            // cek NIS sudah dipakai atau belum
            $stmt = mysqli_prepare($koneksi, "SELECT id FROM siswa WHERE nis = ?");
            mysqli_stmt_bind_param($stmt, "s", $nis);
            mysqli_stmt_execute($stmt);
            $ada_nis = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

            if ($ada_user) {
                $error = "Username sudah dipakai.";
                mysqli_rollback($koneksi);
            } elseif ($ada_nis) {
                $error = "NIS sudah terdaftar.";
                mysqli_rollback($koneksi);
            } else {
                $role = 'siswa';
                $hash = password_hash($password, PASSWORD_DEFAULT);

                $stmt = mysqli_prepare($koneksi, "INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
                mysqli_stmt_bind_param($stmt, "sss", $username, $hash, $role);
                mysqli_stmt_execute($stmt);
                $uid = mysqli_insert_id($koneksi);

                $stmt = mysqli_prepare($koneksi, "INSERT INTO siswa (user_id, nis, nama, kelas, jenis_kelamin) VALUES (?, ?, ?, ?, ?)");
                mysqli_stmt_bind_param($stmt, "issss", $uid, $nis, $nama, $kelas, $jk);
                mysqli_stmt_execute($stmt);

                mysqli_commit($koneksi);
                header("Location: login.php?pesan=" . urlencode("Registrasi berhasil, silakan login."));
                exit;
            }
        } catch (Exception $e) {
            mysqli_rollback($koneksi);
            $error = "Registrasi gagal.";
        }
    }
}
?>

<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Form Register User (Siswa)</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4 mb-4">
  <div class="row">
    <div class="col-lg-2 d-none d-lg-block"></div>
    <div class="col-12 col-lg-8">

      <div class="card">
        <div class="card-header"><b>Form Register User (Siswa)</b></div>
        <div class="card-body">

          <?php if ($error) { ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
          <?php } ?>

          <h4>Data Profil User</h4>

          <form action="register.php" method="POST">

            <div class="mb-3">
              <label class="form-label">NIS:</label>
              <input type="text" name="nis" class="form-control" maxlength="10"
                     value="<?= htmlspecialchars($nis) ?>"
                     placeholder="Nomor Induk Siswa | Contoh: 1234567890" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Nama Lengkap:</label>
              <input type="text" name="nama_lengkap" class="form-control"
                     value="<?= htmlspecialchars($nama) ?>"
                     placeholder="Nama Lengkap Siswa | Contoh: Ujang Asep" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Kelas:</label>
              <input type="text" name="kelas" class="form-control" maxlength="10"
                     value="<?= htmlspecialchars($kelas) ?>"
                     placeholder="Kelas Siswa | Contoh: XI RPL 1" required>
            </div>

            <label class="form-check-label">Jenis Kelamin:</label><br>
            <input type="radio" name="jenis_kelamin" value="L" class="form-check-input"
                   <?php if ($jk === 'L') echo "checked"; ?> required> Laki-laki<br>
            <input type="radio" name="jenis_kelamin" value="P" class="form-check-input"
                   <?php if ($jk === 'P') echo "checked"; ?>> Perempuan<br><br>

            <hr>

            <h4>Data Kredensial Akun</h4>

            <div class="mb-3">
              <label class="form-label">Username:</label>
              <input type="text" name="username" class="form-control" maxlength="50"
                     value="<?= htmlspecialchars($username) ?>"
                     placeholder="Username Akun | Contoh: ujang123" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Password Akun:</label>
              <input type="password" name="password" class="form-control" minlength="6"
                     placeholder="Password Akun | minimal 6 karakter" required>
            </div>

            <button type="submit" name="simpan" class="btn btn-primary">Simpan Data</button>
            <a href="login.php" class="btn btn-secondary">Kembali</a>

          </form>

        </div>
      </div>

    </div>
    <div class="col-lg-2 d-none d-lg-block"></div>
  </div>
</div>

</body>
</html>