logout.php
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* Hapus semua data session */
$_SESSION = [];

/* Hancurkan session */
session_destroy();

/* Redirect ke login */
header("Location: ../auth/login.php");
exit;
