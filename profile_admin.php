<?php
include 'auth_admin.php';
mysqli_report(MYSQLI_REPORT_OFF);

$id = intval($_SESSION['user_id']);
$pesan = '';
$warna = 'info';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // ubah username
    if ($_POST['aksi'] == 'username') {
        $username = trim($_POST['username']);
        $username_aman = mysqli_real_escape_string($koneksi, $username);
        $cek = mysqli_query($koneksi, "SELECT id FROM users WHERE username = '$username_aman' AND id != $id");

        if ($username == '') {
            $pesan = "Username tidak boleh kosong";
            $warna = "danger";
        } elseif (mysqli_num_rows($cek) > 0) {
            $pesan = "Username sudah dipakai";
            $warna = "danger";
        } else {
            mysqli_query($koneksi, "UPDATE users SET username = '$username_aman' WHERE id = $id");
            $_SESSION['username'] = $username;
            $_SESSION['nama'] = $username;
            $user_login['username'] = $username;
            $pesan = "Username berhasil diubah";
            $warna = "success";
        }
    }

    // ganti password
    if ($_POST['aksi'] == 'password') {
        $lama = $_POST['lama'];
        $baru = $_POST['baru'];
        $konfirmasi = $_POST['konfirmasi'];

        $q = mysqli_query($koneksi, "SELECT password FROM users WHERE id = $id");
        $u = mysqli_fetch_assoc($q);

        $lama_benar = password_verify($lama, $u['password']) || $u['password'] == md5($lama) || $u['password'] == $lama;

        if (!$lama_benar) {
            $pesan = "Password lama salah";
            $warna = "danger";
        } elseif (strlen($baru) < 6) {
            $pesan = "Password baru minimal 6 karakter";
            $warna = "danger";
        } elseif ($baru != $konfirmasi) {
            $pesan = "Konfirmasi password tidak sama";
            $warna = "danger";
        } else {
            $hash = password_hash($baru, PASSWORD_DEFAULT);
            mysqli_query($koneksi, "UPDATE users SET password = '$hash' WHERE id = $id");
            $pesan = "Password berhasil diganti";
            $warna = "success";
        }
    }
}

$query = mysqli_query($koneksi, "SELECT * FROM users WHERE id = $id");
$d = mysqli_fetch_assoc($query);

include 'navbar_admin.php';
?>

<div class="container mt-4" style="max-width:600px">
    <h2 class="text-center mb-3">Profile Admin</h2>

    <?php if ($pesan != '') { ?>
        <div class="alert alert-<?php echo $warna; ?>"><?php echo $pesan; ?></div>
    <?php } ?>

    <div class="card mb-3">
        <div class="card-header">Data Akun</div>
        <div class="card-body">
            <p class="mb-1">Username: <b><?php echo htmlspecialchars($d['username']); ?></b></p>
            <p class="mb-0">Role: <b><?php echo htmlspecialchars($d['role']); ?></b></p>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Edit Username</div>
        <div class="card-body">
            <form method="post">
                <input type="hidden" name="aksi" value="username">
                <div class="mb-3">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control"
                        value="<?php echo htmlspecialchars($d['username']); ?>" required>
                </div>
                <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
            </form>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Ganti Password</div>
        <div class="card-body">
            <form method="post">
                <input type="hidden" name="aksi" value="password">
                <div class="mb-3">
                    <label>Password Lama</label>
                    <input type="password" name="lama" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Password Baru</label>
                    <input type="password" name="baru" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Konfirmasi Password Baru</label>
                    <input type="password" name="konfirmasi" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-warning btn-sm">Ganti Password</button>
            </form>
        </div>
    </div>
</div>

<?php include 'footer_admin.php'; ?>