<?php
include 'koneksi.php';
include 'auth.php';
if ($_SESSION['role'] != 'admin') {
    header("location:login.php");
    exit;
}
include 'navbar.php';

// nama diambil dari tabel guru / siswa, kalau tidak ada pakai username
$data = mysqli_query($koneksi, "SELECT users.id, users.username, users.role,
    COALESCE(guru.nama, siswa.nama, users.username) AS nama
    FROM users
    LEFT JOIN guru ON guru.user_id = users.id
    LEFT JOIN siswa ON siswa.user_id = users.id
    ORDER BY users.role, users.id");

$cari = '';
if (isset($_GET['q'])) {
    $cari = $_GET['q'];
}
?>

<div class="container mt-4">
    <h2 class="text-center mb-3">Daftar User</h2>

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

    <a href="form_user.php" class="btn btn-primary btn-sm mb-3">Tambah User</a>

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
            <?php while ($d = mysqli_fetch_assoc($data)) { ?>
                <tr>
                    <td><?php echo $d['id']; ?></td>
                    <td><?php echo htmlspecialchars($d['username']); ?></td>
                    <td><?php echo htmlspecialchars($d['role']); ?></td>
                    <td><?php echo htmlspecialchars($d['nama']); ?></td>
                    <td>
                        <a href="form_user.php?id=<?php echo $d['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <form action="aksi_user.php" method="post" style="display:inline"
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

<?php include 'footer.php'; ?>
<script>
    $(document).ready(function () {
        $('#tabel').DataTable({
            order: [],
            search: { search: <?php echo json_encode($cari); ?> },
            language: { url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/id.json' }
        });
    });
</script>