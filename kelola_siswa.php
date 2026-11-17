<?php
header("location:daftar_siswa_admin.php");
exit;
}
include 'auth_admin.php';
include 'navbar_admin.php';

if (isset($_POST['tambah'])) {
  $nis = $_POST['nis'];
  $nama = $_POST['nama'];
  $kelas = $_POST['kelas'];
  mysqli_query($koneksi, "INSERT INTO tb_siswa (nis, nama, kelas) VALUES ('$nis', '$nama', '$kelas')");
  header("Location: kelola_siswa.php");
}

if (isset($_GET['hapus'])) {
  $id = $_GET['hapus'];
  mysqli_query($koneksi, "DELETE FROM tb_siswa WHERE id='$id'");
  header("Location: kelola_siswa.php");
}

$data = mysqli_query($koneksi, "SELECT * FROM tb_siswa");
?>

<div class="container mt-4">
  <h5>Data Siswa</h5><hr>

  <form method="POST" class="card p-3 mb-4">
    <div class="row g-2">
      <div class="col">
        <input type="text" name="nis" class="form-control" placeholder="NIS" required>
      </div>
      <div class="col">
        <input type="text" name="nama" class="form-control" placeholder="Nama Lengkap" required>
      </div>
      <div class="col">
        <input type="text" name="kelas" class="form-control" placeholder="Kelas" required>
      </div>
      <div class="col">
        <button type="submit" name="tambah" class="btn btn-primary w-100">Tambah</button>
      </div>
    </div>
  </form>

  <table class="table table-bordered">
    <tr class="table-dark">
      <th>No</th>
      <th>NIS</th>
      <th>Nama Siswa</th>
      <th>Kelas</th>
      <th>Aksi</th>
    </tr>
    <?php $no = 1; while ($r = mysqli_fetch_assoc($data)): ?>
    <tr>
      <td><?= $no++ ?></td>
      <td><?= $r['nis'] ?></td>
      <td><?= $r['nama'] ?></td>
      <td><?= $r['kelas'] ?></td>
      <td>
        <a href="?hapus=<?= $r['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</a>
      </td>
    </tr>
    <?php endwhile; ?>
  </table>
</div>

</body>
</html>
