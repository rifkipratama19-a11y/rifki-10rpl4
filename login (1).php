<?php
require_once 'auth.php';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container d-flex align-items-center justify-content-center" style="min-height:100vh">
  <div class="card shadow-sm w-100" style="max-width:420px">
    <div class="card-body p-4">

      <h4 class="text-center mb-4">Login</h4>

      <?php if (!empty($_GET['error'])) { ?>
        <div class="alert alert-danger py-2"><?= htmlspecialchars($_GET['error']) ?></div>
      <?php } ?>

      <?php if (!empty($_GET['pesan'])) { ?>
        <div class="alert alert-success py-2"><?= htmlspecialchars($_GET['pesan']) ?></div>
      <?php } ?>

      <form action="aksi_login.php" method="POST">
        <div class="mb-3">
          <label class="form-label">Username</label>
          <input type="text" name="username" class="form-control"
                 placeholder="Masukkan username" required autofocus>
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control"
                 placeholder="Masukkan password" required>
        </div>
        <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
      </form>

      <p class="text-center mt-3 mb-0">
        Belum memiliki akun? <a href="register.php">Register</a>
      </p>

    </div>
  </div>
</div>

</body>
</html>