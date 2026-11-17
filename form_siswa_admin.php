<?php
include 'auth_admin.php';

// id = 0 berarti tambah, lebih dari 0 berarti edit
$id = 0;
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
}

$nis = '';
$nama = '';
$kelas = '';
$jenis_kelamin = 'L';
$user_id = 0;

if ($id > 0) {
    $query = mysqli_query($koneksi, "SELECT * FROM siswa WHERE id = $id");
    $d = mysqli_fetch_assoc($query);
    if (!$d) {
        header("location:daftar_siswa_admin.php?pesan=gagal");
        exit;
    }
    $nis = $d['nis'];
    $nama = $d['nama'];
    $kelas = $d['kelas'];
    $jenis_kelamin = $d['jenis_kelamin'];
    $user_id = $d['user_id'];
}

// akun ber-role siswa yang belum dipakai siswa lain
$akun = mysqli_query($koneksi, "SELECT id, username FROM users
    WHERE role = 'siswa'
    AND id NOT IN (SELECT user_id FROM siswa WHERE user_id IS NOT NULL AND id != $id)
    ORDER BY username");

include 'navbar_admin.php';
?>

<div class="container mt-4" style="max-width:500px">
    <h3><?php if ($id > 0) echo "Edit"; else echo "Tambah"; ?> Siswa</h3>
    <form action="aksi_siswa_admin.php" method="post">
        <input type="hidden" name="aksi" value="simpan">
        <input type="hidden" name="id" value="<?php echo $id; ?>">

        <div class="mb-3">
            <label>NIS</label>
            <input type="text" name="nis" class="form-control" maxlength="10"
                value="<?php echo htmlspecialchars($nis); ?>" required>
        </div>
        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="nama" class="form-control"
                value="<?php echo htmlspecialchars($nama); ?>" required>
        </div>
        <div class="mb-3">
            <label>Jenis Kelamin</label>
            <select name="jenis_kelamin" class="form-select" required>
                <option value="L" <?php if ($jenis_kelamin == 'L') echo "selected"; ?>>Laki-laki</option>
                <option value="P" <?php if ($jenis_kelamin == 'P') echo "selected"; ?>>Perempuan</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Kelas</label>
            <input type="text" name="kelas" class="form-control" maxlength="10" placeholder="contoh: XI-RPL-4"
                value="<?php echo htmlspecialchars($kelas); ?>" required>
        </div>
        <div class="mb-3">
            <label>Akun Login (role siswa)</label>
            <select name="user_id" class="form-select">
                <option value="0">- belum dihubungkan -</option>
                <?php while ($a = mysqli_fetch_assoc($akun)) { ?>
                    <option value="<?php echo $a['id']; ?>" <?php if ($a['id'] == $user_id) echo "selected"; ?>>
                        <?php echo htmlspecialchars($a['username']); ?>
                    </option>
                <?php } ?>
            </select>
            <div class="form-text">Buat dulu akunnya di menu User dengan role siswa.</div>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="daftar_siswa_admin.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?php include 'footer_admin.php'; ?>