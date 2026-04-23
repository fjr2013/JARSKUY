<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$action = $_POST['action'] ?? '';
$key    = $_POST['key'] ?? '';

if ($action == 'add') {
    $nama  = $_POST['nama'];
    $harga = $_POST['harga'];

    if (!isset($_SESSION['cart'][$key])) {
        $_SESSION['cart'][$key] = [
            'nama' => $nama,
            'harga' => $harga,
            'qty' => 1
        ];
    } else {
        $_SESSION['cart'][$key]['qty']++;
    }
}

if ($action == 'plus') {
    $_SESSION['cart'][$key]['qty']++;
}

if ($action == 'minus') {
    $_SESSION['cart'][$key]['qty']--;

    if ($_SESSION['cart'][$key]['qty'] <= 0) {
        unset($_SESSION['cart'][$key]);
    }
}

echo json_encode($_SESSION['cart']);