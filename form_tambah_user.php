<?php
require_once 'auth.php';
require_once 'koneksi.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

wajibLogin();

if (($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: index.php');
    exit;
}

require_once 'navbar_admin.php';
?>

<div class="container mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">

                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Tambah User</h4>
                </div>

                <div class="card-body">
                    <form action="aksi_tambah_user.php" method="POST">

                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username"
                                   class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password"
                                   class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select" required>
                                <option value="">-- Pilih Role --</option>
                                <option value="admin">Admin</option>
                                <option value="guru">Guru</option>
                                <option value="siswa">Siswa</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="daftar_user.php"
                               class="btn btn-secondary">
                                Kembali
                            </a>

                            <button type="submit" class="btn btn-primary">
                                Simpan
                            </button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
