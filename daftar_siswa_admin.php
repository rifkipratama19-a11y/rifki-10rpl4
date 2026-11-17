<?php
include 'auth_admin.php';
include 'navbar_siswa.php';

$data = mysqli_query($koneksi, "SELECT siswa.*, users.username
    FROM siswa
    LEFT JOIN users ON users.id = siswa.user_id
    ORDER BY siswa.kelas, siswa.nama");
?>

<div class="container mt-4">
    <h2 class="text-center mb-3">Daftar Siswa</h2>

    <?php if (isset($_GET['pesan'])) { ?>
        <div class="alert alert-info">
            <?php
            if ($_GET['pesan'] == 'tambah') echo "Data siswa berhasil ditambahkan";
            elseif ($_GET['pesan'] == 'edit') echo "Data siswa berhasil diubah";
            elseif ($_GET['pesan'] == 'hapus') echo "Data siswa berhasil dihapus";
            elseif ($_GET['pesan'] == 'kosong') echo "NIS, nama, kelas, dan jenis kelamin harus diisi";
            elseif ($_GET['pesan'] == 'panjang') echo "NIS dan kelas maksimal 10 karakter";
            elseif ($_GET['pesan'] == 'kembar') echo "NIS sudah terdaftar";
            elseif ($_GET['pesan'] == 'akun') echo "Akun yang dipilih tidak valid atau sudah dipakai siswa lain";
            else echo "Terjadi kesalahan, data mungkin masih dipakai";
            ?>
        </div>
    <?php } ?>

    <a href="form_siswa_admin.php" class="btn btn-primary btn-sm mb-3">Tambah Siswa</a>

    <table id="tabel" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>NIS</th>
                <th>Nama</th>
                <th>Jenis Kelamin</th>
                <th>Kelas</th>
                <th>Akun Login</th>
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
                    <td><?php echo htmlspecialchars($d['nis']); ?></td>
                    <td><?php echo htmlspecialchars($d['nama']); ?></td>
                    <td><?php if ($d['jenis_kelamin'] == 'L') echo "Laki-laki"; else echo "Perempuan"; ?></td>
                    <td><?php echo htmlspecialchars($d['kelas']); ?></td>
                    <td><?php if ($d['username']) echo htmlspecialchars($d['username']); else echo "-"; ?></td>
                    <td>
                        <a href="form_siswa_admin.php?id=<?php echo $d['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <form action="aksi_siswa_admin.php" method="post" style="display:inline"
                            onsubmit="return confirm('Yakin hapus siswa ini?')">
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