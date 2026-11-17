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
        <a class="navbar-brand" href="daftar_user_admin.php">Navbar</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="daftar_user_admin.php">Home</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">User</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="daftar_user_admin.php">Daftar User</a></li>
                        <li><a class="dropdown-item" href="form_user_admin.php">Tambah User</a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Siswa</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="daftar_siswa_admin.php">Daftar Siswa</a></li>
                        <li><a class="dropdown-item" href="form_siswa_admin.php">Tambah Siswa</a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Guru</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="daftar_guru_admin.php">Daftar Guru</a></li>
                        <li><a class="dropdown-item" href="form_guru_admin.php">Tambah Guru</a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Mapel</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="daftar_mapel_admin.php">Daftar Mapel</a></li>
                        <li><a class="dropdown-item" href="form_mapel_admin.php">Tambah Mapel</a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Mengajar</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="daftar_mengajar_admin.php">Daftar Mengajar</a></li>
                        <li><a class="dropdown-item" href="form_mengajar_admin.php">Tambah Mengajar</a></li>
                    </ul>
                </li>
            </ul>

            <form class="d-flex me-3" action="daftar_user_admin.php" method="get">
                <input class="form-control me-2" type="search" name="q" placeholder="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>

            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <?php echo ucfirst($user_login['role']); ?> ( <?php echo htmlspecialchars($user_login['username']); ?> )
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="profile_admin.php">Profile</a></li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>