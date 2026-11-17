<?php
include 'auth_admin.php';

// id = 0 berarti tambah, lebih dari 0 berarti edit
$id = 0;
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
}

$kode_mapel = '';
$nama_mapel = '';
$guru_id = 0;

if ($id > 0) {
    $query = mysqli_query($koneksi, "SELECT * FROM mapel WHERE id = $id");
    $d = mysqli_fetch_assoc($query);
    if (!$d) {
        header("location:daftar_mapel_admin.php?pesan=gagal");
        exit;
    }
    $kode_mapel = $d['kode_mapel'];
    $nama_mapel = $d['nama_mapel'];
    $guru_id = $d['guru_id'];
}

$guru = mysqli_query($koneksi, "SELECT id, nama FROM guru ORDER BY nama");

include 'navbar_admin.php';
?>

<div class="container mt-4" style="max-width:500px">
    <h3><?php if ($id > 0) echo "Edit"; else echo "Tambah"; ?> Mapel</h3>
    <form action="aksi_mapel_admin.php" method="post">
        <input type="hidden" name="aksi" value="simpan">
        <input type="hidden" name="id" value="<?php echo $id; ?>">

        <div class="mb-3">
            <label>Kode Mapel</label>
            <input type="text" name="kode_mapel" class="form-control" maxlength="10"
                value="<?php echo htmlspecialchars($kode_mapel); ?>" required>
        </div>
        <div class="mb-3">
            <label>Nama Mapel</label>
            <input type="text" name="nama_mapel" class="form-control"
                value="<?php echo htmlspecialchars($nama_mapel); ?>" required>
        </div>
        <div class="mb-3">
            <label>Guru Pengampu</label>
            <select name="guru_id" class="form-select">
                <option value="0">- belum ada -</option>
                <?php while ($g = mysqli_fetch_assoc($guru)) { ?>
                    <option value="<?php echo $g['id']; ?>" <?php if ($g['id'] == $guru_id) echo "selected"; ?>>
                        <?php echo htmlspecialchars($g['nama']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="daftar_mapel_admin.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?php include 'footer_admin.php'; ?>