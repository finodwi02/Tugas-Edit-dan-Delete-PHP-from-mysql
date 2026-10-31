<?php
include 'koneksi.php';

// 1. Hapus data (DELETE) - lewat POST + prepared statement
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['hapus'])) {
    $id = (int) $_POST['hapus'];

    $stmt = mysqli_prepare($koneksi, "DELETE FROM buku WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("Location: dashboard.php?pesan=hapus");
    exit;
}

// 2. Tambah (CREATE) & Edit (UPDATE)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id      = (int) ($_POST['id'] ?? 0);
    $judul   = trim($_POST['judul'] ?? '');
    $penulis = trim($_POST['penulis'] ?? '');
    $terbit  = (int) ($_POST['terbit'] ?? 0);
    $stok    = (int) ($_POST['stok'] ?? 0);

    if ($judul === '' || $penulis === '') {
        header("Location: dashboard.php?pesan=gagal");
        exit;
    }

    if ($id === 0) {
        // ID kosong -> data baru
        $stmt = mysqli_prepare($koneksi, "INSERT INTO buku (judul, penulis, terbit, stok) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssii", $judul, $penulis, $terbit, $stok);
        $pesan = "tambah";
    } else {
        // ID ada -> edit data lama
        $stmt = mysqli_prepare($koneksi, "UPDATE buku SET judul=?, penulis=?, terbit=?, stok=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, "ssiii", $judul, $penulis, $terbit, $stok, $id);
        $pesan = "edit";
    }
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("Location: dashboard.php?pesan=$pesan");
    exit;
}

header("Location: dashboard.php");
exit;
