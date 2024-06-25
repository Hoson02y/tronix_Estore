<?php  include '../ba/header-cust.php' ?>
<?php
session_start();
include "../ba/DBconn.php"; // Including the file for database connection
?>
  
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/style.css">
    <?php include '../ba/head.php' ?>

</head>
<body>
  <br>
  <section class="p-3 p-md-4 p-xl-5">
  <div class="form-floating mb-3">
    <br>
    <br>
    <br>
    <br>
    <div class="h1 mb-4">
    <?php


// التحقق من تسجيل الدخول
if (!isset($_SESSION['username'])|| $_SESSION['user_type'] !='customer') {
    header("Location: login_form.php");
    exit();
}
// <?php
// session_start();

if (isset($_SESSION['cart'])) {
    $cart = $_SESSION['cart'];
} else {
    $cart = [];
}

$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
</head>
<body>
    <h1>Shopping Cart</h1>
    <?php if (!empty($cart)): ?>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cart as $id => $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td><?= htmlspecialchars($item['price']) ?></td>
                    <td><?= htmlspecialchars($item['quantity']) ?></td>
                    <td><?= htmlspecialchars($item['price'] * $item['quantity']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <h3>Total: <?= htmlspecialchars($total) ?></h3>
    <form action="checkout.php" method="POST">
        <input type="submit" name="checkout" value="Confirm Order">
    </form>
<?php else: ?>
    <p>Your shopping cart is empty.</p>
<?php endif; ?>

</body>
</html>

   
     

</section>  
</body>
</html>
 <?php  include '../ba/footer.php' ?>