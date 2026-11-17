<?php
include 'auth_admin.php';
include 'navbar_admin.php';

// hitung jumlah data tiap tabel
$q = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM users");
$r = mysqli_fetch_assoc($q);
$jml_user = $r['total'];

$q = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM guru");
$r = mysqli_fetch_assoc($q);
$jml_guru = $r['total'];

$q = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM siswa");
$r = mysqli_fetch_assoc($q);
$jml_siswa = $r['total'];

$q = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM mapel");
$r = mysqli_fetch_assoc($q);
$jml_mapel = $r['total'];

$q = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM mengajar");
$r = mysqli_fetch_assoc($q);
$jml_mengajar = $r['total'];
?>

<div class="container mt-4">
    <h2 class="text-center mb-2">Dashboard Admin</h2>
    <p class="text-center">Selamat datang, <?php echo htmlspecialchars($user_login['username']); ?></p>

    <div class="row text-center">
        <div class="col-6 col-md-4 mb-3">
            <div class="card"><div class="card-body">
                <h3><?php echo $jml_user; ?></h3>
                <p class="mb-0">User</p>
            </div></div>
        </div>
        <div class="col-6 col-md-4 mb-3">
            <div class="card"><div class="card-body">
                <h3><?php echo $jml_guru; ?></h3>
                <p class="mb-0">Guru</p>
            </div></div>
        </div>
        <div class="col-6 col-md-4 mb-3">
            <div class="card"><div class="card-body">
                <h3><?php echo $jml_siswa; ?></h3>
                <p class="mb-0">Siswa</p>
            </div></div>
        </div>
        <div class="col-6 col-md-4 mb-3">
            <div class="card"><div class="card-body">
                <h3><?php echo $jml_mapel; ?></h3>
                <p class="mb-0">Mapel</p>
            </div></div>
        </div>
        <div class="col-6 col-md-4 mb-3">
            <div class="card"><div class="card-body">
                <h3><?php echo $jml_mengajar; ?></h3>
                <p class="mb-0">Jadwal Mengajar</p>
            </div></div>
        </div>
    </div>
</div>

<?php include 'footer_admin.php'; ?>