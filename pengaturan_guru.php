<?php
include 'auth_guru.php';

$uid = intval($_SESSION['user_id']);
$pesan = '';
$warna = 'success';

// akun belum dihubungkan ke data guru
if (!$guru_login) {
    header("location:profile_guru.php");
    exit;
}

$nip = $guru_login['nip'];
$nama = $guru_login['nama'];
$jk = $guru_login['jenis_kelamin'];
$username = $user_login['username'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nip = trim($_POST['nip']);
    $nama = trim($_POST['nama']);
    $jk = $_POST['jenis_kelamin'];
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $nip_aman = mysqli_real_escape_string($koneksi, $nip);
    $nama_aman = mysqli_real_escape_string($koneksi, $nama);
    $username_aman = mysqli_real_escape_string($koneksi, $username);

    // cek NIP dan username sudah dipakai orang lain atau belum
    $cek_nip = mysqli_query($koneksi, "SELECT id FROM guru WHERE nip = '$nip_aman' AND user_id != $uid");
    $cek_user = mysqli_query($koneksi, "SELECT id FROM users WHERE username = '$username_aman' AND id != $uid");

    if ($nip == '' || $nama == '' || $username == '' || ($jk != 'L' && $jk != 'P')) {
        $pesan = "NIP, nama, jenis kelamin, dan username harus diisi.";
        $warna = 'danger';
    } elseif (strlen($nip) > 20) {
        $pesan = "NIP maksimal 20 karakter.";
        $warna = 'danger';
    } elseif (mysqli_num_rows($cek_nip) > 0) {
        $pesan = "NIP sudah dipakai guru lain.";
        $warna = 'danger';
    } elseif (mysqli_num_rows($cek_user) > 0) {
        $pesan = "Username sudah dipakai.";
        $warna = 'danger';
    } elseif ($password != '' && strlen($password) < 6) {
        $pesan = "Password baru minimal 6 karakter.";
        $warna = 'danger';
    } else {
        // simpan data guru
        mysqli_query($koneksi, "UPDATE guru SET nip = '$nip_aman', nama = '$nama_aman',
            jenis_kelamin = '$jk' WHERE user_id = $uid");

        // simpan username, dan password kalau diisi
        if ($password != '') {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            mysqli_query($koneksi, "UPDATE users SET username = '$username_aman', password = '$hash' WHERE id = $uid");
        } else {
            mysqli_query($koneksi, "UPDATE users SET username = '$username_aman' WHERE id = $uid");
        }

        // perbarui session dan nama di navbar
        $_SESSION['nama'] = $nama;
        $_SESSION['username'] = $username;
        $nama_login = $nama;

        $pesan = "Data berhasil disimpan.";
    }
}

include 'navbar_guru.php';
?>

<div class="container mt-4">
  <div class="card">
    <div class="card-header"><b>Form Edit User (Guru)</b></div>
    <div class="card-body">

      <?php if ($pesan) { ?>
        <div class="alert alert-<?php echo $warna; ?>"><?php echo htmlspecialchars($pesan); ?></div>
      <?php } ?>

      <form method="POST">

        <h5>Data Profil User</h5>

        <div class="mb-3">
          <label class="form-label">NIP:</label>
          <input type="text" name="nip" class="form-control" maxlength="20"
                 value="<?php echo htmlspecialchars($nip); ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Nama Lengkap:</label>
          <input type="text" name="nama" class="form-control" value="<?php echo htmlspecialchars($nama); ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Jenis Kelamin:</label>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="jenis_kelamin" id="jkL" value="L"
                   <?php if ($jk == 'L') echo "checked"; ?>>
            <label class="form-check-label" for="jkL">Laki-laki</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="jenis_kelamin" id="jkP" value="P"
                   <?php if ($jk == 'P') echo "checked"; ?>>
            <label class="form-check-label" for="jkP">Perempuan</label>
          </div>
        </div>

        <hr>

        <h5>Data Kredensial Akun</h5>

        <div class="mb-3">
          <label class="form-label">Username:</label>
          <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($username); ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Password Akun:</label>
          <input type="password" name="password" class="form-control"
                 placeholder="Password baru | jika tidak diubah, kosongkan">
        </div>

        <button type="submit" class="btn btn-primary">Simpan Data</button>
        <a href="profile_guru.php" class="btn btn-secondary">Kembali</a>

      </form>
    </div>
  </div>
</div>

<?php include 'footer_guru.php'; ?>