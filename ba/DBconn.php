<?php
// بدء الجلسة إذا لم تكن قد بدأت بعد
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// التحقق من وجود الثوابت قبل تعريفها
if (!defined('HOST')) {
    define("HOST", "localhost");
}
if (!defined('DBNAME')) {
    define("DBNAME", "db_electronic_store");
}
if (!defined('USER')) {
    define("USER", "root");
}
if (!defined('PASS')) {
    define("PASS", "");
}

try {
    $conn = new PDO("mysql:host=" . HOST . ";dbname=" . DBNAME, USER, PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
}
?>