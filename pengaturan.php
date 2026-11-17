<?php
require_once 'auth.php';
require_once 'koneksi.php';
wajibLogin();

// halaman ini khusus siswa
if ($_SESSION['role'] != 'siswa') {
    header("location:index.php");
    exit;
}

$uid = (int) $_SESSION['user_id'];
$pesan = '';
$warna = 'success';

// data siswa dan akun yang sedang login
$q = mysqli_query($koneksi, "SELECT * FROM siswa WHERE user_id = $uid");
$s = mysqli_fetch_assoc($q);

// akun belum dihubungkan ke data siswa
if (!$s) {
    header("location:profile.php");
    exit;
}

$q2 = mysqli_query($koneksi, "SELECT username FROM users WHERE id = $uid");
$u = mysqli_fetch_assoc($q2);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nis = trim($_POST['nis']);
    $nama = trim($_POST['nama']);
    $kelas = trim($_POST['kelas']);
    $jk = $_POST['jenis_kelamin'];
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $nis_aman = mysqli_real_escape_string($koneksi, $nis);
    $nama_aman = mysqli_real_escape_string($koneksi, $nama);
    $kelas_aman = mysqli_real_escape_string($koneksi, $kelas);
    $username_aman = mysqli_real_escape_string($koneksi, $username);

    // cek NIS dan username sudah dipakai orang lain atau belum
    $cek_nis = mysqli_query($koneksi, "SELECT id FROM siswa WHERE nis = '$nis_aman' AND user_id != $uid");
    $cek_user = mysqli_query($koneksi, "SELECT id FROM users WHERE username = '$username_aman' AND id != $uid");

    if ($nis == '' || $nama == '' || $kelas == '' || $username == '' || ($jk != 'L' && $jk != 'P')) {
        $pesan = "NIS, nama, kelas, jenis kelamin, dan username harus diisi.";
        $warna = 'danger';
    } elseif (strlen($nis) > 10 || strlen($kelas) > 10) {
        $pesan = "NIS dan kelas maksimal 10 karakter.";
        $warna = 'danger';
    } elseif (mysqli_num_rows($cek_nis) > 0) {
        $pesan = "NIS sudah dipakai siswa lain.";
        $warna = 'danger';
    } elseif (mysqli_num_rows($cek_user) > 0) {
        $pesan = "Username sudah dipakai.";
        $warna = 'danger';
    } elseif ($password != '' && strlen($password) < 6) {
        $pesan = "Password baru minimal 6 karakter.";
        $warna = 'danger';
    } else {
        // simpan data profil
        mysqli_query($koneksi, "UPDATE siswa SET nis = '$nis_aman', nama = '$nama_aman',
            kelas = '$kelas_aman', jenis_kelamin = '$jk' WHERE user_id = $uid");

        // simpan username, dan password kalau diisi
        if ($password != '') {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            mysqli_query($koneksi, "UPDATE users SET username = '$username_aman', password = '$hash' WHERE id = $uid");
        } else {
            mysqli_query($koneksi, "UPDATE users SET username = '$username_aman' WHERE id = $uid");
        }

        // perbarui session dan data di layar
        $_SESSION['nama'] = $nama;
        $_SESSION['username'] = $username;
        $s['nis'] = $nis;
        $s['nama'] = $nama;
        $s['kelas'] = $kelas;
        $s['jenis_kelamin'] = $jk;
        $u['username'] = $username;

        $pesan = "Data berhasil disimpan.";
    }
}

include "navbar_siswa.php";
?>

<div class="container mt-4">
  <div class="card">
    <div class="card-header"><b>Form Edit User (Siswa)</b></div>
    <div class="card-body">

      <?php if ($pesan) { ?>
        <div class="alert alert-<?= $warna ?>"><?= htmlspecialchars($pesan) ?></div>
      <?php } ?>

      <form method="POST">

        <h5>Data Profil User</h5>

        <div class="mb-3">
          <label class="form-label">NIS:</label>
          <input type="text" name="nis" class="form-control" maxlength="10"
                 value="<?= htmlspecialchars($s['nis']) ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Nama Lengkap:</label>
          <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($s['nama']) ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Kelas:</label>
          <input type="text" name="kelas" class="form-control" maxlength="10"
                 value="<?= htmlspecialchars($s['kelas']) ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Jenis Kelamin:</label>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="jenis_kelamin" id="jkL" value="L"
                   <?php if ($s['jenis_kelamin'] == 'L') echo "checked"; ?>>
            <label class="form-check-label" for="jkL">Laki-laki</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="jenis_kelamin" id="jkP" value="P"
                   <?php if ($s['jenis_kelamin'] == 'P') echo "checked"; ?>>
            <label class="form-check-label" for="jkP">Perempuan</label>
          </div>
        </div>

        <hr>

        <h5>Data Kredensial Akun</h5>

        <div class="mb-3">
          <label class="form-label">Username:</label>
          <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($u['username']) ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Password Akun:</label>
          <input type="password" name="password" class="form-control"
                 placeholder="Password baru | jika tidak diubah, kosongkan">
        </div>

        <button type="submit" class="btn btn-primary">Simpan Data</button>
        <a href="profile.php" class="btn btn-secondary">Kembali</a>

      </form>
    </div>
  </div>
</div>

<?php include "footer_siswa.php"; ?>