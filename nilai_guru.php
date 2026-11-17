<?php
include 'auth_guru.php';

$data = mysqli_query($koneksi, "SELECT nilai_rapor.*, siswa.nis, siswa.nama AS nama_siswa, siswa.kelas, mapel.nama_mapel
    FROM nilai_rapor
    JOIN siswa ON siswa.id = nilai_rapor.siswa_id
    JOIN mapel ON mapel.id = nilai_rapor.mapel_id
    WHERE mapel.guru_id = $id_guru
    ORDER BY siswa.kelas, siswa.nama, mapel.nama_mapel");

include 'navbar_guru.php';
?>

<div class="container mt-4">
    <h2 class="text-center mb-3">Nilai Siswa</h2>

    <?php if (isset($_GET['pesan'])) { ?>
        <div class="alert alert-info">
            <?php
            if ($_GET['pesan'] == 'tambah') echo "Nilai berhasil ditambahkan";
            elseif ($_GET['pesan'] == 'edit') echo "Nilai berhasil diubah";
            elseif ($_GET['pesan'] == 'hapus') echo "Nilai berhasil dihapus";
            elseif ($_GET['pesan'] == 'kosong') echo "Siswa dan mapel harus dipilih";
            elseif ($_GET['pesan'] == 'angka') echo "KKM dan nilai harus berupa angka 0 sampai 100";
            elseif ($_GET['pesan'] == 'kembar') echo "Nilai siswa untuk mapel ini sudah ada, silakan edit saja";
            elseif ($_GET['pesan'] == 'mapel') echo "Mapel yang dipilih bukan mapel Anda";
            elseif ($_GET['pesan'] == 'akun') echo "Akun Anda belum dihubungkan ke data guru. Hubungi admin";
            else echo "Terjadi kesalahan";
            ?>
        </div>
    <?php } ?>

    <?php if ($id_guru == 0) { ?>
        <div class="alert alert-warning">Akun Anda belum dihubungkan ke data guru. Hubungi admin.</div>
    <?php } else { ?>

        <a href="form_nilai_guru.php" class="btn btn-primary btn-sm mb-3">Tambah Nilai</a>

        <table id="tabel" class="table table-striped w-100">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIS</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Mapel</th>
                    <th>KKM</th>
                    <th>Pengetahuan</th>
                    <th>Keterampilan</th>
                    <th>Rata-rata</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                while ($d = mysqli_fetch_assoc($data)) {
                    $rata = round(($d['nilai_pengetahuan'] + $d['nilai_keterampilan']) / 2, 1);
                ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo htmlspecialchars($d['nis']); ?></td>
                        <td><?php echo htmlspecialchars($d['nama_siswa']); ?></td>
                        <td><?php echo htmlspecialchars($d['kelas']); ?></td>
                        <td><?php echo htmlspecialchars($d['nama_mapel']); ?></td>
                        <td><?php echo (int) $d['nilai_kkm']; ?></td>
                        <td><?php echo (int) $d['nilai_pengetahuan']; ?></td>
                        <td><?php echo (int) $d['nilai_keterampilan']; ?></td>
                        <td><?php echo $rata; ?></td>
                        <td><?php echo htmlspecialchars($d['Keterangan']); ?></td>
                        <td class="text-nowrap">
                            <a href="form_nilai_guru.php?id=<?php echo $d['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <form action="aksi_nilai_guru.php" method="post" style="display:inline"
                                onsubmit="return confirm('Yakin hapus nilai ini?')">
                                <input type="hidden" name="aksi" value="hapus">
                                <input type="hidden" name="id" value="<?php echo $d['id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

    <?php } ?>
</div>

<?php include 'footer_guru.php'; ?>
<script>
    $(document).ready(function () {
        if ($('#tabel').length) {
            $('#tabel').DataTable({
                lengthMenu: [5, 10, 25, 50],
                pageLength: 10,
                scrollX: true,
                language: {
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    search: "Cari:",
                    info: "Menampilkan halaman _PAGE_ dari _PAGES_",
                    infoEmpty: "Tidak ada data tersedia",
                    infoFiltered: "",
                    zeroRecords: "Data nilai tidak ditemukan",
                    emptyTable: "Data nilai tidak ditemukan",
                    paginate: { previous: "Sebelumnya", next: "Selanjutnya" }
                }
            });
        }
    });
</script>