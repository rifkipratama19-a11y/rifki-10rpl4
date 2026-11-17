<?php
require_once 'auth.php';
require_once 'koneksi.php';
wajibLogin();

$uid = (int) $_SESSION['user_id'];
$u = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT username, role FROM users WHERE id = $uid"));
$s = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM siswa WHERE user_id = $uid")) ?: [];

$nama = $s['nama'] ?? $u['username'];

include "navbar_siswa.php";
?>

<div class="container mt-3">
  <h4 class="mb-0"><b>Profile</b></h4>
  <small class="text-muted">Informasi akun yang sedang login</small>

  <div class="card mt-3">
    <div class="card-body text-center">
      <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center"
           style="width:64px;height:64px;font-size:28px">
        <?= htmlspecialchars(strtoupper(substr($nama, 0, 1))) ?>
      </div>
      <h6 class="mt-2 mb-1"><b><?= htmlspecialchars($nama) ?></b></h6>
      <span class="badge bg-primary"><?= htmlspecialchars(strtoupper($u['role'])) ?></span>
    </div>
    <hr class="my-0">

    <div class="card-body">
      <h6><b>Informasi Akun</b></h6>
      <div class="row mb-2"><div class="col-5 text-muted small">Username</div><div class="col-7 small"><b><?= htmlspecialchars($u['username']) ?></b></div></div>
      <div class="row"><div class="col-5 text-muted small">Role</div><div class="col-7"><span class="badge bg-primary"><?= htmlspecialchars(ucfirst($u['role'])) ?></span></div></div>
    </div>
    <hr class="my-0">

    <div class="card-body">
      <h6><b>Data Siswa</b></h6>
      <div class="row mb-2"><div class="col-5 text-muted small">Nama</div><div class="col-7 small"><b><?= htmlspecialchars($s['nama'] ?? '-') ?></b></div></div>
      <div class="row mb-2"><div class="col-5 text-muted small">NIS</div><div class="col-7 small"><b><?= htmlspecialchars($s['nis'] ?? '-') ?></b></div></div>
      <div class="row mb-2"><div class="col-5 text-muted small">Kelas</div><div class="col-7 small"><b><?= htmlspecialchars($s['kelas'] ?? '-') ?></b></div></div>
      <div class="row"><div class="col-5 text-muted small">Jenis Kelamin</div><div class="col-7 small"><b><?= htmlspecialchars($s['jenis_kelamin'] ?? '-') ?></b></div></div>
    </div>
    <hr class="my-0">

    <div class="card-body text-end">
      <a href="daftar_siswa.php" class="btn btn-secondary btn-sm">Kembali</a>
      <a href="pengaturan.php" class="btn btn-primary btn-sm">Edit Profile</a>
    </div>
  </div>
</div>

<?php include "footer_siswa.php"; ?>