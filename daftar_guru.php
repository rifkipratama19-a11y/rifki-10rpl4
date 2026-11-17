<?php
include 'auth_guru.php';

$cari = '';
if (isset($_GET['q'])) {
    $cari = $_GET['q'];
}

// satu baris untuk tiap guru dan mapel yang diampu
$data = mysqli_query($koneksi, "SELECT users.username, users.role, guru.nama, mapel.nama_mapel
    FROM guru
    JOIN users ON users.id = guru.user_id
    LEFT JOIN mapel ON mapel.guru_id = guru.id
    ORDER BY guru.id, mapel.id");

include 'navbar_guru.php';
?>

<style>
    #isi-guru { max-width: 430px; margin: auto; font-size: 12px; }
</style>

<div class="container mt-4">
    <h2 class="text-center mb-3">Daftar Guru</h2>

    <div id="isi-guru">
        <table id="tabel" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Nama</th>
                    <th>Mapel</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                while ($d = mysqli_fetch_assoc($data)) {
                ?>
                    <tr>
                        <td><b><?php echo $no++; ?></b></td>
                        <td><?php echo htmlspecialchars($d['username']); ?></td>
                        <td><?php echo htmlspecialchars($d['role']); ?></td>
                        <td><?php echo htmlspecialchars($d['nama']); ?></td>
                        <td><?php if ($d['nama_mapel']) echo htmlspecialchars($d['nama_mapel']); else echo "-"; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer_guru.php'; ?>
<script>
    $(document).ready(function () {
        $('#tabel').DataTable({
            search: { search: <?php echo json_encode($cari); ?> },
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