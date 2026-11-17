<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// dipanggil di halaman yang butuh login
function wajibLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }
}