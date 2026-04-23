<?php 
session_start();
include 'koneksi.php';

/* ================= CEK CART ================= */
if (empty($_SESSION['cart'])) {
    echo "<script>
        alert('Keranjang kosong');
        window.location.href='index.php';
    </script>";
    exit;
}

/* ================= TAMPIL FORM ================= */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
?>

<!DOCTYPE html>
<html>
<head>
<title>Checkout</title>

<style>
body{font-family:Arial;background:#f3f4f6;padding:30px}
.container{
  max-width:800px;margin:auto;background:#fff;
  padding:25px;border-radius:10px;
}
table{width:100%;border-collapse:collapse;margin-bottom:20px}
td,th{padding:10px;border-bottom:1px solid #ddd}
.btn{background:black;color:white;padding:10px;border:none;width:100%;cursor:pointer}
input,textarea,select{width:100%;padding:8px}
</style>
</head>

<body>

<div class="container">
<h2>Checkout</h2>

<table>
<tr><th>Produk</th><th>Qty</th><th>Subtotal</th></tr>

<?php
$total = 0;
foreach ($_SESSION['cart'] as $key => $item):

$sub = $item['harga'] * $item['qty'];
$total += $sub;
?>
<tr>
<td><?= $item['nama'] ?></td>
<td><?= $item['qty'] ?></td>
<td>Rp <?= number_format($sub,0,',','.') ?></td>
</tr>
<?php endforeach; ?>

<tr>
<td colspan="2"><b>Total</b></td>
<td><b>Rp <?= number_format($total,0,',','.') ?></b></td>
</tr>
</table>

<form method="post">

<label>Nama Pembeli</label>
<input type="text" name="nama" required>

<br><br>

<label>Alamat</label>
<textarea name="alamat" required></textarea>

<br><br>

<h3>Pembayaran</h3>
<select name="metode" required>
  <option value="GoPay">GoPay</option>
  <option value="DANA">DANA</option>
  <option value="Bank Transfer">Bank Transfer</option>
</select>

<br><br>

<h3>Ekspedisi</h3>
<select name="ekspedisi" required>
  <option value="JNE">JNE</option>
  <option value="J&T">J&T</option>
  <option value="Gojek">Gojek</option>
</select>

<br><br>

<button class="btn">Checkout</button>

</form>

</div>
</body>
</html>

<?php
exit;
}

/* ================= PROSES CHECKOUT ================= */

$nama      = mysqli_real_escape_string($conn, $_POST['nama']);
$alamat    = mysqli_real_escape_string($conn, $_POST['alamat']);
$metode    = mysqli_real_escape_string($conn, $_POST['metode']);
$ekspedisi = mysqli_real_escape_string($conn, $_POST['ekspedisi']);

/* ================= MAP KEY (ANTI ERROR SIGNATURE) ================= */
$map = [
  'signature' => 1,
  'midnight'  => 2,
  'rose'      => 3
];

/* ================= VALIDASI STOCK ================= */
foreach ($_SESSION['cart'] as $key => $item) {

    // 🔥 konversi key ke ID database
    $id = is_numeric($key) ? $key : ($map[$key] ?? 0);

    if($id == 0){
        die("Produk tidak valid");
    }

    $cek = mysqli_query($conn, "SELECT stock FROM produk WHERE id='$id'");
    $data = mysqli_fetch_assoc($cek);

    if(!$data){
        die("Produk tidak ditemukan di database");
    }

    if($data['stock'] < $item['qty']){
        echo "<script>
            alert('Stock tidak cukup untuk ".$item['nama']."');
            window.location.href='index.php';
        </script>";
        exit;
    }
}

/* ================= HITUNG TOTAL ================= */
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['harga'] * $item['qty'];
}

/* ================= SIMPAN PESANAN ================= */
mysqli_query($conn, "
INSERT INTO pesanan 
(nama_pembeli, alamat, total, metode_pembayaran, ekspedisi, status_pembayaran)
VALUES
('$nama','$alamat','$total','$metode','$ekspedisi','pending')
");

$pesanan_id = mysqli_insert_id($conn);

/* ================= DETAIL + UPDATE STOCK ================= */
foreach ($_SESSION['cart'] as $key => $item) {

    // 🔥 konversi lagi
    $id = is_numeric($key) ? $key : ($map[$key] ?? 0);

    $produk   = mysqli_real_escape_string($conn, $item['nama']);
    $harga    = (int)$item['harga'];
    $qty      = (int)$item['qty'];
    $subtotal = $harga * $qty;

    mysqli_query($conn, "
    INSERT INTO pesanan_detail
    (pesanan_id, produk, harga, qty, subtotal)
    VALUES
    ('$pesanan_id','$produk','$harga','$qty','$subtotal')
    ");

    mysqli_query($conn, "
    UPDATE produk 
    SET stock = stock - $qty 
    WHERE id = '$id'
    ");
}

/* ================= CLEAR CART ================= */
unset($_SESSION['cart']);

/* ================= REDIRECT ================= */
echo "<script>
    alert('Checkout berhasil! Stok otomatis berkurang');
    window.location.href='index.php';
</script>";
exit;