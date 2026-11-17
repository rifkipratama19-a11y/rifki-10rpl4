<?php include "navbar.php"; ?>

<div class="container mt-4">
  <div class="row align-items-start">
    <div class="col-2"></div>

    <div class="col-8">
      <div class="card">
        <div class="card-header">
          <b>Form Tambah User (Siswa)</b>
        </div>

        <div class="card-body">
          <h4>Data Profil User</h4>

          <form action="aksi_tambah_user.php" method="POST">

            <div class="mb-3">
              <label class="form-label">NIS:</label>
              <input type="text" class="form-control" name="nis"
                     placeholder="Contoh: 1234567890" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Nama Lengkap:</label>
              <input type="text" class="form-control" name="nama_lengkap"
                     placeholder="Contoh: Ujang Asep" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Kelas:</label>
              <input type="text" class="form-control" name="kelas"
                     placeholder="Contoh: XI-RPL-1" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Jenis Kelamin:</label><br>
              <input type="radio" name="jenis_kelamin" value="Laki-laki" checked> Laki-laki<br>
              <input type="radio" name="jenis_kelamin" value="Perempuan"> Perempuan
            </div>

            <hr>

            <h4>Data Akun</h4>

            <div class="mb-3">
              <label class="form-label">Username:</label>
              <input type="text" class="form-control" name="username"
                     placeholder="Contoh: ujang123" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Password:</label>
              <input type="password" class="form-control" name="password"
                     placeholder="Contoh: password123" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Role</label>
              <input type="text" class="form-control" value="siswa" readonly>
              <input type="hidden" name="role" value="siswa">
            </div>

            <button type="submit" class="btn btn-primary">Simpan Data</button>
            <a href="tabel.php" class="btn btn-secondary">Kembali</a>

          </form>
        </div>
      </div>
    </div>

    <div class="col-2"></div>
  </div>
</div>

<?php include "script.php"; ?>
