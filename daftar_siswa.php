<?php
require_once 'auth.php';
require_once 'koneksi.php';
wajibLogin();

$kelas = 'XI RPL 4';
if (($_SESSION['role'] ?? '') === 'siswa') {
    $stmt = mysqli_prepare($koneksi, "SELECT kelas FROM siswa WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
    mysqli_stmt_execute($stmt);
    $k = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    if ($k && $k['kelas'] !== '') $kelas = $k['kelas'];
}

// kata pencarian dari kotak Search di navbar
$cari = $_GET['q'] ?? '';

$stmt = mysqli_prepare($koneksi,
    "SELECT users.username, users.role, siswa.nama, siswa.kelas
     FROM siswa JOIN users ON users.id = siswa.user_id
     WHERE siswa.kelas = ? ORDER BY siswa.nama");
mysqli_stmt_bind_param($stmt, "s", $kelas);
mysqli_stmt_execute($stmt);
$data = mysqli_stmt_get_result($stmt);

include "navbar_siswa.php";
?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">

<div class="container mt-4">
  <h1 class="text-center mb-4">Daftar Siswa Kelas <?= htmlspecialchars($kelas) ?></h1>

  <div class="table-responsive">
  <table id="tabelSiswa" class="table table-striped w-100">
    <thead>
      <tr><th>No</th><th>Username</th><th>Role</th><th>Nama</th><th>Kelas</th></tr>
    </thead>
    <tbody>
      <?php $no = 1; while ($r = mysqli_fetch_assoc($data)) { ?>
      <tr>
        <td><?= $no++ ?></td>
        <td><?= htmlspecialchars($r['username']) ?></td>
        <td><?= htmlspecialchars($r['role']) ?></td>
        <td><?= htmlspecialchars($r['nama']) ?></td>
        <td><?= htmlspecialchars($r['kelas']) ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script>
$(function () {
  $('#tabelSiswa').DataTable({
    search: { search: <?= json_encode($cari) ?> },
    lengthMenu: [5, 10, 25, 50],
    pageLength: 10,
    language: {
      lengthMenu: "Tampilkan _MENU_ data per halaman",
      search: "Cari:",
      info: "Menampilkan halaman _PAGE_ dari _PAGES_",
      infoEmpty: "Tidak ada data",
      infoFiltered: "",
      zeroRecords: "Data tidak ditemukan",
      paginate: { previous: "Sebelumnya", next: "Selanjutnya" }
    }
  });
});
</script>

<?php include "footer_siswa.php"; ?>