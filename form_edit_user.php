<?php
require_once 'koneksi.php';

$role = $_GET['role'] ?? '';
$id   = (int) ($_GET['id'] ?? 0);

if (!in_array($role, ['admin', 'guru', 'siswa'], true) || $id <= 0) {
    header("Location: tabel.php");
    exit;
}

function e($v) { return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8'); }

$stmt = mysqli_prepare($koneksi, "SELECT id, username, password FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
if (!$user) {
    die("User dengan ID $id tidak ditemukan!");
}

$profil = [];
if ($role == 'guru' || $role == 'siswa') {
    $stmt = mysqli_prepare($koneksi, "SELECT * FROM $role WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $profil = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt)) ?: [];
}

require_once 'navbar.php';
?>

<div class="container mt-4">
  <div class="row align-items-start">
    <div class="col-2"></div>
    <div class="col-8">
      <div class="card">
        <div class="card-header"><b>Form Edit User (<?= ucfirst($role) ?>)</b></div>
        <div class="card-body">

          <?php if (!empty($_GET['error'])) { ?>
            <div class="alert alert-danger"><?= e($_GET['error']) ?></div>
          <?php } ?>

          <form action="aksi_edit_user.php" method="POST">
            <input type="hidden" name="id" value="<?= (int)$user['id'] ?>">
            <input type="hidden" name="role" value="<?= e($role) ?>">

            <?php if ($role == 'guru') { ?>
              <h4>Data Profil Guru</h4>
              <div class="mb-3">
                <label class="form-label">NIP:</label>
                <input type="text" name="nip" value="<?= e($profil['nip'] ?? '') ?>" class="form-control">
              </div>
              <div class="mb-3">
                <label class="form-label">Nama Lengkap:</label>
                <input type="text" name="nama" value="<?= e($profil['nama'] ?? '') ?>" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label d-block">Jenis Kelamin:</label>
                <div class="form-check">
                  <input type="radio" name="jenis_kelamin" value="L" class="form-check-input" id="jkL"
                         <?= ($profil['jenis_kelamin'] ?? '') == 'L' ? 'checked' : '' ?>>
                  <label class="form-check-label" for="jkL">Laki-laki</label>
                </div>
                <div class="form-check">
                  <input type="radio" name="jenis_kelamin" value="P" class="form-check-input" id="jkP"
                         <?= ($profil['jenis_kelamin'] ?? '') == 'P' ? 'checked' : '' ?>>
                  <label class="form-check-label" for="jkP">Perempuan</label>
                </div>
              </div>
              <hr>

            <?php } elseif ($role == 'siswa') { ?>
              <h4>Data Profil Siswa</h4>
              <div class="mb-3">
                <label class="form-label">NIS:</label>
                <input type="text" name="nis" value="<?= e($profil['nis'] ?? '') ?>" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Nama Lengkap:</label>
                <input type="text" name="nama" value="<?= e($profil['nama'] ?? '') ?>" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Kelas:</label>
                <input type="text" name="kelas" value="<?= e($profil['kelas'] ?? '') ?>" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label d-block">Jenis Kelamin:</label>
                <div class="form-check">
                  <input type="radio" name="jenis_kelamin" value="L" class="form-check-input" id="jkL"
                         <?= ($profil['jenis_kelamin'] ?? '') == 'L' ? 'checked' : '' ?>>
                  <label class="form-check-label" for="jkL">Laki-laki</label>
                </div>
                <div class="form-check">
                  <input type="radio" name="jenis_kelamin" value="P" class="form-check-input" id="jkP"
                         <?= ($profil['jenis_kelamin'] ?? '') == 'P' ? 'checked' : '' ?>>
                  <label class="form-check-label" for="jkP">Perempuan</label>
                </div>
              </div>
              <hr>
            <?php } ?>

            <h4>Data Akun</h4>
            <div class="mb-3">
              <label class="form-label">Username:</label>
              <input type="text" name="username" value="<?= e($user['username']) ?>" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Password:</label>
              <div class="input-group">
                <input type="password" name="password" id="password"
                       value="<?= e($user['password']) ?>" class="form-control">
                <button type="button" class="btn btn-outline-secondary" id="toggle-pw">Tampilkan</button>
              </div>
            </div>

            <button type="submit" name="edit" class="btn btn-primary">Ubah Data</button>
            <a href="tabel.php" class="btn btn-secondary">Kembali</a>
          </form>

        </div>
      </div>
    </div>
    <div class="col-2"></div>
  </div>
</div>

<script>
document.getElementById('toggle-pw').addEventListener('click', function () {
  var i = document.getElementById('password');
  var show = i.type === 'password';
  i.type = show ? 'text' : 'password';
  this.textContent = show ? 'Sembunyikan' : 'Tampilkan';
});
</script>

<?php require_once 'footer.php'; ?>