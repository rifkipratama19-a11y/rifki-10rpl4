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

$q = mysqli_query($koneksi, "SELECT * FROM siswa WHERE user_id = $uid");
$s = mysqli_fetch_assoc($q);

// akun belum dihubungkan ke data siswa
if (!$s) {
    header("location:profile.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = trim($_POST['nama']);
    $jk = $_POST['jenis_kelamin'];

    if ($nama == '' || ($jk != 'L' && $jk != 'P')) {
        $pesan = "Nama dan jenis kelamin harus diisi.";
        $warna = 'danger';
    } else {
        $stmt = mysqli_prepare($koneksi, "UPDATE siswa SET nama = ?, jenis_kelamin = ? WHERE user_id = ?");
        mysqli_stmt_bind_param($stmt, "ssi", $nama, $jk, $uid);
        mysqli_stmt_execute($stmt);

        $_SESSION['nama'] = $nama;
        $s['nama'] = $nama;
        $s['jenis_kelamin'] = $jk;
        $pesan = "Profile berhasil diperbarui.";
    }
}

include "navbar_siswa.php";
?>

<div class="container mt-4">
  <div class="card">
    <div class="card-header"><b>Edit Profile</b></div>
    <div class="card-body">
      <?php if ($pesan) { ?><div class="alert alert-<?= $warna ?>"><?= htmlspecialchars($pesan) ?></div><?php } ?>
      <form method="POST">
        <div class="mb-3">
          <label class="form-label">Nama</label>
          <input type="text" name="nama" class="form-control"
                 value="<?= htmlspecialchars($s['nama']) ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">NIS</label>
          <input type="text" class="form-control" value="<?= htmlspecialchars($s['nis']) ?>" disabled>
        </div>
        <div class="mb-3">
          <label class="form-label">Kelas</label>
          <input type="text" class="form-control" value="<?= htmlspecialchars($s['kelas']) ?>" disabled>
        </div>
        <div class="mb-3">
          <label class="form-label">Jenis Kelamin</label>
          <select name="jenis_kelamin" class="form-select" required>
            <option value="L" <?php if ($s['jenis_kelamin'] == 'L') echo "selected"; ?>>Laki-laki</option>
            <option value="P" <?php if ($s['jenis_kelamin'] == 'P') echo "selected"; ?>>Perempuan</option>
          </select>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="profile.php" class="btn btn-secondary">Kembali</a>
      </form>
    </div>
  </div>
</div>

<?php include "footer_siswa.php"; ?>