<?php
session_start();
include '../ba/DBconn.php';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=products.csv');

$output = fopen("php://output", "w");
fputcsv($output, array('Product ID', 'Product Name', 'Price', 'Description'));

$stmt = $conn->prepare("SELECT id, name, price, description FROM products ORDER BY name ASC");
$stmt->execute();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, $row);
}

fclose($output);
exit();
?>
