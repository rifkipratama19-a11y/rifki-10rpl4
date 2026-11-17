<?php
include 'koneksi.php';
include 'auth.php';
if ($_SESSION['role'] != 'admin') {
    header("location:login.php");
    exit;
}

// id = 0 berarti tambah, lebih dari 0 berarti edit
$id = 0;
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
}

$username = '';
$role = 'siswa';

if ($id > 0) {
    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE id = $id");
    $d = mysqli_fetch_assoc($query);
    if (!$d) {
        header("location:daftar_user.php?pesan=gagal");
        exit;
    }
    $username = $d['username'];
    $role = $d['role'];
}

include 'navbar.php';
?>

<div class="container mt-4" style="max-width:500px">
    <h3><?php if ($id > 0) echo "Edit"; else echo "Tambah"; ?> User</h3>
    <form action="aksi_user.php" method="post">
        <input type="hidden" name="aksi" value="simpan">
        <input type="hidden" name="id" value="<?php echo $id; ?>">

        <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" class="form-control"
                value="<?php echo htmlspecialchars($username); ?>" required>
        </div>
        <div class="mb-3">
            <label>Role</label>
            <select name="role" class="form-select" required>
                <option value="admin" <?php if ($role == 'admin') echo "selected"; ?>>admin</option>
                <option value="guru" <?php if ($role == 'guru') echo "selected"; ?>>guru</option>
                <option value="siswa" <?php if ($role == 'siswa') echo "selected"; ?>>siswa</option>
            </select>
            <div class="form-text">Nama guru atau siswa diisi lewat menu Guru atau Siswa.</div>
        </div>
        <div class="mb-3">
            <label>Password <?php if ($id > 0) echo "(kosongkan kalau tidak diganti)"; ?></label>
            <input type="password" name="password" class="form-control" <?php if ($id == 0) echo "required"; ?>>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="daftar_user.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?php include 'footer.php'; ?>