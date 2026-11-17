<?php
require_once 'auth.php';
require_once 'koneksi.php';
wajibLogin();

$uid = (int) $_SESSION['user_id'];

$q = mysqli_query($koneksi, "SELECT * FROM siswa WHERE user_id = $uid");
$siswa = mysqli_fetch_assoc($q);

$id_siswa = 0;
$nama_tampil = $_SESSION['nama'];
$nis = '-';
$kelas = '-';
if ($siswa) {
    $id_siswa = (int) $siswa['id'];
    $nama_tampil = $siswa['nama'];
    $nis = $siswa['nis'];
    $kelas = $siswa['kelas'];
}

// daftar nilai dari tabel nilai_rapor
$nilai = mysqli_query($koneksi, "SELECT mapel.nama_mapel, nilai_rapor.nilai_kkm,
    nilai_rapor.nilai_pengetahuan, nilai_rapor.nilai_keterampilan, nilai_rapor.Keterangan
    FROM nilai_rapor
    JOIN mapel ON mapel.id = nilai_rapor.mapel_id
    WHERE nilai_rapor.siswa_id = $id_siswa
    ORDER BY mapel.nama_mapel");

// rata-rata keseluruhan
$q_rata = mysqli_query($koneksi, "SELECT AVG((nilai_pengetahuan + nilai_keterampilan) / 2) AS rata
    FROM nilai_rapor WHERE siswa_id = $id_siswa");
$r_rata = mysqli_fetch_assoc($q_rata);
$rata = 0;
if ($r_rata && $r_rata['rata'] !== null) {
    $rata = round($r_rata['rata'], 1);
}

include "navbar_siswa.php";
?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">

<div class="container mt-4">

  <div class="text-center mb-4">
    <h1>Nilai Siswa</h1>
    <h3><?= htmlspecialchars($nama_tampil) ?></h3>
    <p class="mb-1">NIS: <b><?= htmlspecialchars($nis) ?></b></p>
    <p>Kelas: <b><?= htmlspecialchars($kelas) ?></b></p>
  </div>

  <div class="card mb-4">
    <div class="card-body text-center">
      <h5>Rata-rata Nilai</h5>
      <h1 class="fw-light"><?= $rata ?></h1>
    </div>
  </div>

  <div class="card mb-4">
    <div class="card-body">
      <h4 class="mb-3">Daftar Nilai</h4>

      <table id="tabelNilai" class="table table-striped w-100">
        <thead>
          <tr>
            <th>No</th>
            <th>Mata Pelajaran</th>
            <th>KKM</th>
            <th>Pengetahuan</th>
            <th>Keterampilan</th>
            <th>Rata-rata</th>
            <th>Keterangan</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $no = 1;
          while ($n = mysqli_fetch_assoc($nilai)) {
              $rata_mapel = round(($n['nilai_pengetahuan'] + $n['nilai_keterampilan']) / 2, 1);
          ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($n['nama_mapel']) ?></td>
            <td><?= (int) $n['nilai_kkm'] ?></td>
            <td><?= (int) $n['nilai_pengetahuan'] ?></td>
            <td><?= (int) $n['nilai_keterampilan'] ?></td>
            <td><?= $rata_mapel ?></td>
            <td><?= htmlspecialchars($n['Keterangan']) ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>

</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script>
$(function () {
  $('#tabelNilai').DataTable({
    lengthMenu: [5, 10, 25, 50],
    pageLength: 10,
    scrollX: true,
    language: {
      lengthMenu: "Tampilkan _MENU_ data per halaman",
      search: "Cari:",
      info: "Menampilkan halaman _PAGE_ dari _PAGES_",
      infoEmpty: "Tidak ada data tersedia",
      infoFiltered: "",
      zeroRecords: "Data nilai tidak ditemukan",
      emptyTable: "Data nilai tidak ditemukan",
      paginate: { previous: "Sebelumnya", next: "Selanjutnya" }
    }
  });
});
</script>

<?php include "footer_siswa.php"; ?>