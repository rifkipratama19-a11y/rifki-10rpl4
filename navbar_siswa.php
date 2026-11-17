<?php
// dipakai halaman siswa. Session sudah dimulai oleh auth.php
$halaman = basename($_SERVER['PHP_SELF']);

$nama_login = '';
if (isset($_SESSION['nama'])) {
    $nama_login = $_SESSION['nama'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIPN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="daftar_siswa.php">Navbar</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuSiswa">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menuSiswa">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?php if ($halaman == 'daftar_siswa.php') echo 'active'; ?>" href="daftar_siswa.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if ($halaman == 'nilai_siswa.php') echo 'active'; ?>" href="nilai_siswa.php">Nilai Siswa</a>
                </li>
            </ul>

            <form class="d-flex me-3" action="daftar_siswa.php" method="get">
                <input class="form-control me-2" type="search" name="q" placeholder="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>

            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <?php echo htmlspecialchars($nama_login); ?> ( siswa )
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                        <li><a class="dropdown-item" href="pengaturan.php">Pengaturan</a></li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>