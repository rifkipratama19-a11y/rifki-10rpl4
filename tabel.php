<?php
require_once 'koneksi.php';
include "navbar.php";

$query = mysqli_query($koneksi, "SELECT id, username, role FROM users ORDER BY id");
if (!$query) {
    die("Query gagal: " . mysqli_error($koneksi));
}
?>

<div class="container mt-4">
  <div class="row align-items-start">
    <div class="col-lg-2 d-none d-lg-block"></div>
    <div class="col-12 col-lg-8">
      <div class="card">
        <div class="card-header"><b>Tabel</b></div>
        <div class="card-body">

          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Username</th>
                  <th>Role</th>
                  <th>Nama</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $no = 1;
                while ($row = mysqli_fetch_assoc($query)) {
                    $role = strtolower($row['role']);
                    $uid  = (int) $row['id'];
                    $nama = $row['username'];

                    if ($role == 'siswa' || $role == 'guru') {
                        $q2 = mysqli_query($koneksi, "SELECT nama FROM $role WHERE user_id = $uid");
                        $p  = $q2 ? mysqli_fetch_assoc($q2) : null;
                        if ($p && $p['nama'] !== '') {
                            $nama = $p['nama'];
                        }
                    }
                ?>
                <tr>
                  <th><?= $no++ ?></th>
                  <td><?= htmlspecialchars($row['username']) ?></td>
                  <td><?= htmlspecialchars(ucfirst($role)) ?></td>
                  <td><?= htmlspecialchars($nama) ?></td>
                  <td class="text-nowrap">
                    <a href="form_edit_user.php?role=<?= urlencode($role) ?>&id=<?= $uid ?>"
                       class="btn btn-warning btn-sm">Edit</a>
                    <a href="aksi_hapus.php?id=<?= $uid ?>"
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Yakin hapus user ini?')">Hapus</a>
                  </td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>
    <div class="col-lg-2 d-none d-lg-block"></div>
  </div>
</div>

<?php include "script.php"; ?>