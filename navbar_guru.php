<?php
// dipakai halaman guru. Variabel $nama_login berasal dari auth_guru.php
$halaman = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIPN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="daftar_guru.php">Navbar</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuGuru">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menuGuru">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?php if ($halaman == 'daftar_guru.php') echo 'active'; ?>" href="daftar_guru.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if ($halaman == 'nilai_guru.php' || $halaman == 'form_nilai_guru.php') echo 'active'; ?>" href="nilai_guru.php">Nilai Siswa</a>
                </li>
            </ul>

            <form class="d-flex me-3" action="daftar_guru.php" method="get">
                <input class="form-control me-2" type="search" name="q" placeholder="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>

            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <?php echo htmlspecialchars($nama_login); ?> ( guru )
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="profile_guru.php">Profile</a></li>
                        <li><a class="dropdown-item" href="pengaturan_guru.php">Pengaturan</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>