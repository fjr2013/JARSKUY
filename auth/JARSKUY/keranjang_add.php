<?php
session_start();
include 'koneksi.php';

$id = $_GET['id'];

// Ambil data produk dari database
$query = mysqli_query($conn, "SELECT * FROM produk WHERE id = '$id'");
$produk = mysqli_fetch_assoc($query);

$nama_produk = $produk['nama_produk'];
$harga       = $produk['harga'];

// Jika produk sudah ada di keranjang → tambah qty
if (isset($_SESSION['keranjang'][$id])) {
    $_SESSION['keranjang'][$id]['qty'] += 1;
} else {
    // ⬇⬇⬇ INI JAWABAN PERTANYAAN KAMU ⬇⬇⬇
    $_SESSION['keranjang'][$id] = [
        'nama'  => $nama_produk,
        'harga' => $harga,
        'qty'   => 1
    ];
}

header("Location: index.php");
