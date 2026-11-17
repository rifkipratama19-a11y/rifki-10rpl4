<?php
require_once 'auth.php';
require_once 'koneksi.php';

wajibLogin();

if (($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Mengajar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>
<body>

<?php include 'navbar_admin.php'; ?>

<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <b>Data Mengajar</b>
        </div>
        <div class="card-body">
            <p>
                Halaman Mengajar sudah tersedia.
                Untuk menampilkan data guru, mata pelajaran, dan kelas,
                struktur tabel Mengajar perlu disesuaikan dengan database.
            </p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
