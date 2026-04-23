<?php
session_start();
include 'koneksi.php';

/* CEK KERANJANG */
if (empty($_SESSION['cart'])) {
    echo "<script>
        alert('Keranjang kosong');
        window.location.href='index.php#keranjang';
    </script>";
    exit;
}

/* CEK FORM */
if (
    empty($_POST['nama']) ||
    empty($_POST['alamat']) ||
    empty($_POST['metode']) ||
    empty($_POST['ekspedisi'])
) {
    echo "<script>
        alert('Data checkout tidak lengkap');
        window.location.href='index.php#keranjang';
    </script>";
    exit;
}

$nama      = mysqli_real_escape_string($conn, $_POST['nama']);
$alamat    = mysqli_real_escape_string($conn, $_POST['alamat']);
$metode    = mysqli_real_escape_string($conn, $_POST['metode']);
$ekspedisi = mysqli_real_escape_string($conn, $_POST['ekspedisi']);

/* HITUNG TOTAL */
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['harga'] * $item['qty'];
}

/* SIMPAN PESANAN */
$sql = "
INSERT INTO pesanan 
(nama_pembeli, alamat, total, metode_pembayaran, ekspedisi, status_pembayaran)
VALUES
('$nama', '$alamat', '$total', '$metode', '$ekspedisi', 'pending')
";

if (!mysqli_query($conn, $sql)) {
    die('Gagal simpan pesanan: '.mysqli_error($conn));
}

$pesanan_id = mysqli_insert_id($conn);

/* DETAIL PESANAN */
foreach ($_SESSION['cart'] as $item) {
    $produk   = mysqli_real_escape_string($conn, $item['nama']);
    $harga    = (int)$item['harga'];
    $qty      = (int)$item['qty'];
    $subtotal = $harga * $qty;

    mysqli_query($conn, "
        INSERT INTO pesanan_detail
        (pesanan_id, produk, harga, qty, subtotal)
        VALUES
        ('$pesanan_id', '$produk', '$harga', '$qty', '$subtotal')
    ");
}

/* KOSONGKAN CART */
unset($_SESSION['cart']);

echo "<script>
    alert('Checkout berhasil!');
    window.location.href='index.php';
</script>";
exit;
