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

$id_guru = 0;
$id_mapel = 0;
$kelas = '';
$hari = 'Senin';
$jam_mulai = '';
$jam_selesai = '';

if ($id > 0) {
    $query = mysqli_query($koneksi, "SELECT * FROM mengajar WHERE id = $id");
    $d = mysqli_fetch_assoc($query);
    if (!$d) {
        header("location:kelola_mengajar.php?pesan=gagal");
        exit;
    }
    $id_guru = $d['id_guru'];
    $id_mapel = $d['id_mapel'];
    $kelas = $d['kelas'];
    $hari = $d['hari'];
    $jam_mulai = substr($d['jam_mulai'], 0, 5);
    $jam_selesai = substr($d['jam_selesai'], 0, 5);
}

include 'navbar.php';

$guru = mysqli_query($koneksi, "SELECT id, nama FROM guru ORDER BY nama");
$mapel = mysqli_query($koneksi, "SELECT id, nama_mapel FROM mapel ORDER BY nama_mapel");
?>

<div class="container mt-4" style="max-width:500px">
    <h3><?php if ($id > 0) echo "Edit"; else echo "Tambah"; ?> Mengajar</h3>
    <form action="aksi_mengajar.php" method="post">
        <input type="hidden" name="aksi" value="simpan">
        <input type="hidden" name="id" value="<?php echo $id; ?>">

        <div class="mb-3">
            <label>Guru</label>
            <select name="id_guru" class="form-select" required>
                <option value="">- pilih guru -</option>
                <?php while ($g = mysqli_fetch_assoc($guru)) { ?>
                    <option value="<?php echo $g['id']; ?>" <?php if ($g['id'] == $id_guru) echo "selected"; ?>>
                        <?php echo htmlspecialchars($g['nama']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <div class="mb-3">
            <label>Mapel</label>
            <select name="id_mapel" class="form-select" required>
                <option value="">- pilih mapel -</option>
                <?php while ($m = mysqli_fetch_assoc($mapel)) { ?>
                    <option value="<?php echo $m['id']; ?>" <?php if ($m['id'] == $id_mapel) echo "selected"; ?>>
                        <?php echo htmlspecialchars($m['nama_mapel']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <div class="mb-3">
            <label>Kelas</label>
            <input type="text" name="kelas" class="form-control" placeholder="contoh: X-A"
                value="<?php echo htmlspecialchars($kelas); ?>" required>
        </div>
        <div class="mb-3">
            <label>Hari</label>
            <select name="hari" class="form-select" required>
                <option value="Senin" <?php if ($hari == 'Senin') echo "selected"; ?>>Senin</option>
                <option value="Selasa" <?php if ($hari == 'Selasa') echo "selected"; ?>>Selasa</option>
                <option value="Rabu" <?php if ($hari == 'Rabu') echo "selected"; ?>>Rabu</option>
                <option value="Kamis" <?php if ($hari == 'Kamis') echo "selected"; ?>>Kamis</option>
                <option value="Jumat" <?php if ($hari == 'Jumat') echo "selected"; ?>>Jumat</option>
                <option value="Sabtu" <?php if ($hari == 'Sabtu') echo "selected"; ?>>Sabtu</option>
            </select>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label>Jam Mulai</label>
                <input type="time" name="jam_mulai" class="form-control"
                    value="<?php echo $jam_mulai; ?>" required>
            </div>
            <div class="col">
                <label>Jam Selesai</label>
                <input type="time" name="jam_selesai" class="form-control"
                    value="<?php echo $jam_selesai; ?>" required>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="kelola_mengajar.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?php include 'footer.php'; ?>