<?php
include 'auth_admin.php';
include 'navbar_admin.php';

// nama diambil dari tabel guru / siswa, kalau tidak ada pakai role (admin)
$data = mysqli_query($koneksi, "SELECT users.id, users.username, users.role,
    COALESCE(guru.nama, siswa.nama, users.role) AS nama
    FROM users
    LEFT JOIN guru ON guru.user_id = users.id
    LEFT JOIN siswa ON siswa.user_id = users.id
    ORDER BY users.id");

$cari = '';
if (isset($_GET['q'])) {
    $cari = $_GET['q'];
}
?>

<style>
    #isi-user { max-width: 430px; margin: auto; font-size: 12px; }
    #isi-user .btn { font-size: 11px; padding: 2px 8px; }
    #isi-user td, #isi-user th { vertical-align: top; }
</style>

<div class="container mt-4">
    <h2 class="text-center mb-3">Daftar User</h2>

    <div id="isi-user">

        <?php if (isset($_GET['pesan'])) { ?>
            <div class="alert alert-info">
                <?php
                if ($_GET['pesan'] == 'tambah') echo "User berhasil ditambahkan";
                elseif ($_GET['pesan'] == 'edit') echo "User berhasil diubah";
                elseif ($_GET['pesan'] == 'hapus') echo "User berhasil dihapus";
                elseif ($_GET['pesan'] == 'kosong') echo "Username dan role harus diisi";
                elseif ($_GET['pesan'] == 'password') echo "Password minimal 6 karakter";
                elseif ($_GET['pesan'] == 'kembar') echo "Username sudah dipakai";
                elseif ($_GET['pesan'] == 'diri') echo "Akun yang sedang dipakai tidak bisa dihapus";
                elseif ($_GET['pesan'] == 'role_diri') echo "Role akun yang sedang dipakai tidak boleh diubah";
                else echo "Terjadi kesalahan, data mungkin masih dipakai";
                ?>
            </div>
        <?php } ?>

        <table id="tabel" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Nama</th>
                    <th>Aksi</th>
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
                        <td>
                            <a href="form_user_admin.php?id=<?php echo $d['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <form action="aksi_user_admin.php" method="post" style="margin-top:3px"
                                onsubmit="return confirm('Yakin hapus user ini?')">
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
</div>

<?php include 'footer_admin.php'; ?>
<script>
    $(document).ready(function () {
        $('#tabel').DataTable({
            order: [[0, 'asc']],
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