<?php
include 'koneksi.php';
include 'auth.php';
if ($_SESSION['role'] != 'admin') {
    header("location:login.php");
    exit;
}
include 'navbar.php';

$data = mysqli_query($koneksi, "SELECT mengajar.*, guru.nama AS nama_guru, mapel.nama_mapel
    FROM mengajar
    JOIN guru ON guru.id = mengajar.id_guru
    JOIN mapel ON mapel.id = mengajar.id_mapel
    ORDER BY mengajar.id");
?>

<div class="container mt-4">
    <h2 class="text-center mb-3">Daftar Mengajar</h2>

    <?php if (isset($_GET['pesan'])) { ?>
        <div class="alert alert-info">
            <?php
            if ($_GET['pesan'] == 'tambah') echo "Jadwal berhasil ditambahkan";
            elseif ($_GET['pesan'] == 'edit') echo "Jadwal berhasil diubah";
            elseif ($_GET['pesan'] == 'hapus') echo "Jadwal berhasil dihapus";
            elseif ($_GET['pesan'] == 'kosong') echo "Semua kolom harus diisi";
            elseif ($_GET['pesan'] == 'jam') echo "Jam selesai harus lebih besar dari jam mulai";
            elseif ($_GET['pesan'] == 'bentrok') echo "Jadwal bentrok dengan jadwal guru/kelas lain";
            else echo "Terjadi kesalahan";
            ?>
        </div>
    <?php } ?>

    <a href="form_mengajar.php" class="btn btn-primary btn-sm mb-3">Tambah Mengajar</a>

    <table id="tabel" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Guru</th>
                <th>Mapel</th>
                <th>Kelas</th>
                <th>Hari</th>
                <th>Jam</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            while ($d = mysqli_fetch_assoc($data)) {
            ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo htmlspecialchars($d['nama_guru']); ?></td>
                    <td><?php echo htmlspecialchars($d['nama_mapel']); ?></td>
                    <td><?php echo htmlspecialchars($d['kelas']); ?></td>
                    <td><?php echo $d['hari']; ?></td>
                    <td><?php echo substr($d['jam_mulai'], 0, 5) . " - " . substr($d['jam_selesai'], 0, 5); ?></td>
                    <td>
                        <a href="form_mengajar.php?id=<?php echo $d['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <form action="aksi_mengajar.php" method="post" style="display:inline"
                            onsubmit="return confirm('Yakin hapus jadwal ini?')">
                            <input type="hidden" name="aksi" value="hapus">
                            <input type="hidden" name="id" value="<?php echo $d['id']; ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>
<script>
    $(document).ready(function () {
        $('#tabel').DataTable({
            language: { url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/id.json' }
        });
    });
</script>