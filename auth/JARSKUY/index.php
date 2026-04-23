<?php
session_start();

/* ================= DATA PRODUK ================= */

  $produkList = [
  "signature" => [
    "nama" => "MYKONOS X Set1awanade Glitch",
    "harga" => 120000,
    "deskripsi" => "Top Notes: sweet, smoky, pineapple.",
    "img" => "css/assets/glicth.jpg"
  ],

  "midnight" => [
    "nama" => "MYKONOS X Set1awanade Invade",
    "harga" => 239000,
    "deskripsi" => "Top Notes: pink pepper, lavender, juniper.
Middle Notes: cashmeran, cinnamon, caramel.
Base Notes: amber, vanilla, drywoods.",
    "img" => "css/assets/invade.jpg"
  ],

  "rose" => [
    "nama" => "MYKONOS CALIFORNIA Signature",
    "harga" => 110000,
    "deskripsi" => "Top: Mandarin, Lemon.
Middle: Lavender, Aquatic, Rosemary.
Base: Tonka Bean, Teak Wood, Vetiver.",
    "img" => "css/assets/California .jpg"
  ]
];


/* ================= INIT CART ================= */
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

/* ================= TAMBAH KE CART ================= */
if (isset($_POST['add_cart'])) {
  $key = $_POST['produk_key'];

  if (isset($produkList[$key])) {
    if (!isset($_SESSION['cart'][$key])) {
      $_SESSION['cart'][$key] = [
        'nama'  => $produkList[$key]['nama'],
        'harga' => $produkList[$key]['harga'],
        'qty'   => 1
      ];
    } else {
      $_SESSION['cart'][$key]['qty']++;
    }
  }
  header("Location: index.php#keranjang");
  exit;
}

/* ================= PLUS ================= */
if (isset($_POST['plus'])) {
  $key = $_POST['key'];
  $_SESSION['cart'][$key]['qty']++;
  header("Location: index.php#keranjang");
  exit;
}

/* ================= MINUS ================= */
if (isset($_POST['minus'])) {
  $key = $_POST['key'];
  $_SESSION['cart'][$key]['qty']--;

  if ($_SESSION['cart'][$key]['qty'] <= 0) {
    unset($_SESSION['cart'][$key]);
  }
  header("Location: index.php#keranjang");
  exit;
}
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>JARSKUY PARFUME</title>
<style>
body{font-family:Arial;margin:0;background:#f7f7f7}
header{background:#111;color:#fff;padding:30px}
.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:20px}
.card{background:#fff;padding:18px;border-radius:10px}
.card img{width:100%;height:160px;object-fit:cover}
.btn{background:#111;color:#fff;padding:8px 14px;border-radius:6px;border:none;cursor:pointer}
table{width:100%;border-collapse:collapse}
th,td{border-bottom:1px solid #ddd;padding:8px;text-align:center}
.qty-btn{padding:4px 10px}
/* ===== PAYMENT (ANTI TERPOTONG – FINAL FIX) ===== */
.payment-grid{
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 15px;
  margin-top: 15px;
}

.pay-item{
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 14px;
  border: 1px solid #ddd;
  border-radius: 8px;
  background: #fff;
  cursor: pointer;
  min-height: 80px;
}

/* 🔥 INI YANG MENGALAHKAN .card img */
.payment-grid .pay-item img{
  width: 70px !important;
  height: auto !important;
  max-height: 50px !important;
  object-fit: contain !important;
  display: block;
  flex-shrink: 0;
}
</style>
</head>

</style>
</head>

<body>

<header>
  <h1>JARSKUY PARFUME</h1>
</header>

<main style="max-width:1100px;margin:30px auto">

<!-- KATALOG -->
<section class="card">
<h2>Katalog Produk</h2>
<div class="grid">
<?php foreach ($produkList as $key => $p): ?>
<div class="card">
  <img src="<?= $p['img']; ?>">
  <h3><?= $p['nama']; ?></h3>
  <p><?= nl2br($p['deskripsi']); ?></p>
  <strong>Rp <?= number_format($p['harga'],0,',','.'); ?></strong>
  <form method="post">
    <input type="hidden" name="produk_key" value="<?= $key ?>">
    <button type="submit" name="add_cart" class="btn">Tambah ke Keranjang</button>
  </form>
</div>
<?php endforeach; ?>
</div>
</section>

<!-- KERANJANG -->
<section class="card" id="keranjang" style="margin-top:30px">
<h2>Keranjang Belanja</h2>

<?php if (empty($_SESSION['cart'])): ?>
<p>Keranjang masih kosong</p>
<?php else: ?>

<table>
<tr>
  <th>Produk</th>
  <th>Harga</th>
  <th>Qty</th>
  <th>Subtotal</th>
</tr>

<?php
$total = 0;
foreach ($_SESSION['cart'] as $key => $item):
$sub = $item['harga'] * $item['qty'];
$total += $sub;
?>
<tr>
<td><?= $item['nama']; ?></td>
<td>Rp <?= number_format($item['harga'],0,',','.'); ?></td>
<td>
  <form method="post" style="display:inline">
    <input type="hidden" name="key" value="<?= $key ?>">
    <button name="minus" class="qty-btn">−</button>
  </form>

  <?= $item['qty']; ?>

  <form method="post" style="display:inline">
    <input type="hidden" name="key" value="<?= $key ?>">
    <button name="plus" class="qty-btn">+</button>
  </form>
</td>
<td>Rp <?= number_format($sub,0,',','.'); ?></td>
</tr>
<?php endforeach; ?>

<tr>
<td colspan="3"><strong>Total</strong></td>
<td><strong>Rp <?= number_format($total,0,',','.'); ?></strong></td>
</tr>
</table>

<br>
<form action="checkout.php" method="post">

  <div class="form-group">
    <label>Nama Pembeli</label>
    <input type="text" name="nama" class="form-control" required>
  </div>

  <div class="form-group">
    <label>Alamat</label>
    <textarea name="alamat" class="form-control alamat" required></textarea>
  </div>

  <h3>Pilih Metode Pembayaran</h3>

  <div class="payment-grid">

    <label class="pay-item">
      <input type="radio" name="metode" value="GoPay" required>
      <img src="css/assets/gopay.jpg" alt="GoPay">
      GoPay
    </label>

    <label class="pay-item">
      <input type="radio" name="metode" value="DANA">
      <img src="css/assets/dana.jpg" alt="DANA">
      DANA
    </label>

    <label class="pay-item">
      <input type="radio" name="metode" value="Bank Transfer">
      <img src="css/assets/bank.jpg" alt="Bank Transfer">
      Bank Transfer
    </label>

    <label class="pay-item">
      <input type="radio" name="metode" value="ShopeePay">
      <img src="css/assets/shopeepay.jpg" alt="ShopeePay">
      ShopeePay
    </label>

    <label class="pay-item">
      <input type="radio" name="metode" value="OVO">
      <img src="css/assets/ovo.jpg" alt="OVO">
      OVO
    </label>

  </div>

  <<h3 style="margin-top:30px">Pilih Jasa Ekspedisi</h3>
<div class="payment-grid">
<label class="pay-item"><input type="radio" name="ekspedisi" value="JNE" required><img src="css/assets/jne.jpg">JNE</label>
<label class="pay-item"><input type="radio" name="ekspedisi" value="J&T"><img src="css/assets/jnt.jpg">J&T</label>
<label class="pay-item"><input type="radio" name="ekspedisi" value="Gojek"><img src="css/assets/gojek.jpg">Gojek</label>
<label class="pay-item"><input type="radio" name="ekspedisi" value="Grab"><img src="css/assets/grab.jpg">Grab</label>
</div>

  <br>
  <button type="submit" class="btn">Checkout</button>


</form>


<?php endif; ?>
</section>
</main>
</body>
</html>  