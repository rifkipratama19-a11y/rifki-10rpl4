<?php
include 'auth_guru.php';

// daftar mapel yang diampu, digabung jadi satu tulisan
$daftar_mapel = '';
$q_mapel = mysqli_query($koneksi, "SELECT nama_mapel FROM mapel WHERE guru_id = $id_guru ORDER BY nama_mapel");
while ($m = mysqli_fetch_assoc($q_mapel)) {
    if ($daftar_mapel != '') {
        $daftar_mapel = $daftar_mapel . ", ";
    }
    $daftar_mapel = $daftar_mapel . $m['nama_mapel'];
}
if ($daftar_mapel == '') {
    $daftar_mapel = '-';
}

$nip = '-';
$jk = '-';
if ($guru_login) {
    $nip = $guru_login['nip'];
    if ($guru_login['jenis_kelamin'] == 'L') {
        $jk = 'Laki-laki';
    } else {
        $jk = 'Perempuan';
    }
}

include 'navbar_guru.php';
?>

<div class="container mt-3">
  <h4 class="mb-0"><b>Profile</b></h4>
  <small class="text-muted">Informasi akun yang sedang login</small>

  <div class="card mt-3">
    <div class="card-body text-center">
      <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center"
           style="width:64px;height:64px;font-size:28px">
        <?php echo htmlspecialchars(strtoupper(substr($nama_login, 0, 1))); ?>
      </div>
      <h6 class="mt-2 mb-1"><b><?php echo htmlspecialchars($nama_login); ?></b></h6>
      <span class="badge bg-primary"><?php echo htmlspecialchars(strtoupper($user_login['role'])); ?></span>
    </div>
    <hr class="my-0">

    <div class="card-body">
      <h6><b>Informasi Akun</b></h6>
      <div class="row mb-2"><div class="col-5 text-muted small">Username</div><div class="col-7 small"><b><?php echo htmlspecialchars($user_login['username']); ?></b></div></div>
      <div class="row"><div class="col-5 text-muted small">Role</div><div class="col-7"><span class="badge bg-primary"><?php echo htmlspecialchars(ucfirst($user_login['role'])); ?></span></div></div>
    </div>
    <hr class="my-0">

    <div class="card-body">
      <h6><b>Data Guru</b></h6>
      <div class="row mb-2"><div class="col-5 text-muted small">Nama</div><div class="col-7 small"><b><?php echo htmlspecialchars($nama_login); ?></b></div></div>
      <div class="row mb-2"><div class="col-5 text-muted small">NIP</div><div class="col-7 small"><b><?php echo htmlspecialchars($nip); ?></b></div></div>
      <div class="row mb-2"><div class="col-5 text-muted small">Jenis Kelamin</div><div class="col-7 small"><b><?php echo $jk; ?></b></div></div>
      <div class="row"><div class="col-5 text-muted small">Mapel</div><div class="col-7 small"><b><?php echo htmlspecialchars($daftar_mapel); ?></b></div></div>
    </div>
    <hr class="my-0">

    <div class="card-body text-end">
      <a href="daftar_guru.php" class="btn btn-secondary btn-sm">Kembali</a>
      <a href="pengaturan_guru.php" class="btn btn-primary btn-sm">Edit Profile</a>
    </div>
  </div>
</div>

<?php include 'footer_guru.php'; ?>