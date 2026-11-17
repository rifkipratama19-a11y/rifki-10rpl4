<?php
include 'auth_guru.php';

// akun belum dihubungkan ke data guru
if ($id_guru == 0) {
    header("location:nilai_guru.php?pesan=akun");
    exit;
}

// id = 0 berarti tambah, lebih dari 0 berarti edit
$id = 0;
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
}

$siswa_id = 0;
$mapel_id = 0;
$nilai_kkm = 75;
$pengetahuan = '';
$keterampilan = '';

if ($id > 0) {
    // hanya boleh edit nilai dari mapel milik guru ini
    $query = mysqli_query($koneksi, "SELECT nilai_rapor.* FROM nilai_rapor
        JOIN mapel ON mapel.id = nilai_rapor.mapel_id
        WHERE nilai_rapor.id = $id AND mapel.guru_id = $id_guru");
    $d = mysqli_fetch_assoc($query);
    if (!$d) {
        header("location:nilai_guru.php?pesan=gagal");
        exit;
    }
    $siswa_id = $d['siswa_id'];
    $mapel_id = $d['mapel_id'];
    $nilai_kkm = $d['nilai_kkm'];
    $pengetahuan = $d['nilai_pengetahuan'];
    $keterampilan = $d['nilai_keterampilan'];
}

$siswa = mysqli_query($koneksi, "SELECT id, nis, nama, kelas FROM siswa ORDER BY kelas, nama");
$mapel = mysqli_query($koneksi, "SELECT id, nama_mapel FROM mapel WHERE guru_id = $id_guru ORDER BY nama_mapel");
$jumlah_mapel = mysqli_num_rows($mapel);

include 'navbar_guru.php';
?>

<div class="container mt-4" style="max-width:500px">
    <h3><?php if ($id > 0) echo "Edit"; else echo "Tambah"; ?> Nilai</h3>

    <?php if ($jumlah_mapel == 0) { ?>
        <div class="alert alert-warning">
            Anda belum punya mapel. Minta admin mengisi guru pengampu di menu Mapel.
        </div>
        <a href="nilai_guru.php" class="btn btn-secondary">Kembali</a>
    <?php } else { ?>

    <form action="aksi_nilai_guru.php" method="post">
        <input type="hidden" name="aksi" value="simpan">
        <input type="hidden" name="id" value="<?php echo $id; ?>">

        <div class="mb-3">
            <label>Siswa</label>
            <select name="siswa_id" class="form-select" required>
                <option value="">- pilih siswa -</option>
                <?php while ($s = mysqli_fetch_assoc($siswa)) { ?>
                    <option value="<?php echo $s['id']; ?>" <?php if ($s['id'] == $siswa_id) echo "selected"; ?>>
                        <?php echo htmlspecialchars($s['nis'] . " - " . $s['nama'] . " (" . $s['kelas'] . ")"); ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Mata Pelajaran</label>
            <select name="mapel_id" class="form-select" required>
                <option value="">- pilih mapel -</option>
                <?php while ($m = mysqli_fetch_assoc($mapel)) { ?>
                    <option value="<?php echo $m['id']; ?>" <?php if ($m['id'] == $mapel_id) echo "selected"; ?>>
                        <?php echo htmlspecialchars($m['nama_mapel']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label>KKM</label>
            <input type="number" name="nilai_kkm" class="form-control" min="0" max="100"
                value="<?php echo $nilai_kkm; ?>" required>
        </div>

        <div class="row mb-3">
            <div class="col">
                <label>Nilai Pengetahuan</label>
                <input type="number" name="nilai_pengetahuan" class="form-control" min="0" max="100"
                    value="<?php echo $pengetahuan; ?>" required>
            </div>
            <div class="col">
                <label>Nilai Keterampilan</label>
                <input type="number" name="nilai_keterampilan" class="form-control" min="0" max="100"
                    value="<?php echo $keterampilan; ?>" required>
            </div>
        </div>

        <div class="form-text mb-3">Keterangan terisi otomatis: Tuntas jika rata-rata sama dengan atau di atas KKM.</div>

        <button type="submit" class="btn btn-primary">Simpan Data</button>
        <a href="nilai_guru.php" class="btn btn-secondary">Batal</a>
    </form>

    <?php } ?>
</div>

<?php include 'footer_guru.php'; ?>