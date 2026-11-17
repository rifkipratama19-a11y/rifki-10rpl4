<?php
$koneksi = mysqli_connect(
    "127.0.0.1",
    "root",
    "root",
    "rifki_sipn",
    3306
);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
