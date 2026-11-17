<?php
include 'auth_admin.php';
include 'navbar_admin.php';

$data = mysqli_query($koneksi, "SELECT mapel.*, guru.nama AS nama_guru
    FROM mapel
    LEFT JOIN guru ON guru.id = mapel.guru_id
    ORDER BY mapel.id");
?>

<div class="container mt-4">
    <h2 class="text-center mb-3">Daftar Mata Pelajaran</h2>

    <?php if (isset($_GET['pesan'])) { ?>
        <div class="alert alert-info">
            <?php
            if ($_GET['pesan'] == 'tambah') echo "Mapel berhasil ditambahkan";
            elseif ($_GET['pesan'] == 'edit') echo "Mapel berhasil diubah";
            elseif ($_GET['pesan'] == 'hapus') echo "Mapel berhasil dihapus";
            elseif ($_GET['pesan'] == 'kosong') echo "Kode dan nama mapel harus diisi";
            elseif ($_GET['pesan'] == 'panjang') echo "Kode mapel maksimal 10 karakter";
            elseif ($_GET['pesan'] == 'kembar') echo "Kode mapel sudah dipakai";
            else echo "Terjadi kesalahan, data mungkin masih dipakai";
            ?>
        </div>
    <?php } ?>

    <a href="form_mapel_admin.php" class="btn btn-primary btn-sm mb-3">Tambah Mapel</a>

    <table id="tabel" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Nama Mapel</th>
                <th>Guru Pengampu</th>
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
                    <td><?php echo htmlspecialchars($d['kode_mapel']); ?></td>
                    <td><?php echo htmlspecialchars($d['nama_mapel']); ?></td>
                    <td><?php if ($d['nama_guru']) echo htmlspecialchars($d['nama_guru']); else echo "-"; ?></td>
                    <td>
                        <a href="form_mapel_admin.php?id=<?php echo $d['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <form action="aksi_mapel_admin.php" method="post" style="display:inline"
                            onsubmit="return confirm('Yakin hapus mapel ini?')">
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

<?php include 'footer_admin.php'; ?>
<script>
    $(document).ready(function () {
        $('#tabel').DataTable({
            lengthMenu: [5, 10, 25, 50],
            pageLength: 10,
            language: {
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                search: "Cari:",
                info: "Menampilkan halaman _PAGE_ dari _PAGES_",
                infoEmpty: "Tidak ada data",
                infoFiltered: "",
                zeroRecords: "Data tidak ditemukan",
                paginate: { previous: "Sebelumnya", next: "Selanjutnya" }
            }
        });
    });
</script>