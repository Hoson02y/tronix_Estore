<?php
session_start();

// التحقق من تسجيل الدخول
if (!isset($_SESSION['username']) || $_SESSION['user_type'] != 'customer') {
    header("Location: ../login_form.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <?php include '../ba/head.php'; ?>
    <title>Product Details</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .product-image {
            max-width: 100%; 
            margin-bottom: 20px;
        }

        .quantity-input {
            width: 70px;
            padding: 8px;
            font-size: 16px;
            text-align: center;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        .btn-primary,
        .btn-modal {
            background-color: #ff7f11; 
            color: white; 
            border: none; 
            padding: 10px 20px; 
            border-radius: 3px; 
            cursor: pointer; 
            transition: background-color 0.3s;
            margin-left: 10px; 
        }

        .btn-primary:hover,
        .btn-modal:hover {
            background-color: #ff5c00; 
        }

        .btn-primary:focus,
        .btn-modal:focus {
            outline: none; 
            box-shadow: 0 0 0 3px rgba(255, 127, 17, 0.4); 
        }
    </style>
    <script>
        function updateTotalPrice() {
            const quantityInput = document.getElementById('quantity');
            const totalPriceElement = document.getElementById('totalPrice');
            const price = parseFloat(totalPriceElement.getAttribute('data-price'));
            const quantity = parseInt(quantityInput.value);
            totalPriceElement.textContent = (price * quantity).toFixed(2);
        }
    </script>
</head>
<body>
<section class="p-3 p-md-4 p-xl-5">
  <div class="form-floating mb-3">
    <?php include '../ba/header-cust.php'; ?>
    <div class="container mt-4">
        <?php
        include '../ba/DBconn.php';

        if (!empty($_GET['id'])) {
            $productId = $_GET['id'];
            $sql = "SELECT * FROM products WHERE id = :productId";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $img = htmlspecialchars($row['image_path']);
                $name = htmlspecialchars($row['name']);
                $brand = htmlspecialchars($row['brand']);
                $desc = htmlspecialchars($row['description']);
                $price = htmlspecialchars($row['price']);
                $quantity_available = htmlspecialchars($row['quantity_available']);

                echo "<h1>Product Details</h1>";
                echo "<div class='row'>";
                echo "<div class='col-md-6'>";
                echo "<img src='../$img' alt='Product Image' class='product-image'>";
                echo "</div>";
                echo "<div class='col-md-6'>";
                echo "<h2>$name</h2>";
                echo "<p><strong>Brand:</strong> $brand</p>";
                echo "<p><strong>Description:</strong> $desc</p>";
                echo "<p><strong>Price:</strong> $$price</p>";
                echo "<form action='addToCart.php' method='post'>";
                echo "<p><strong>Quantity Available:</strong> $quantity_available</p><br>";
                echo "<input type='hidden' name='product_id' value='$productId'>";
                echo "<label for='quantity'>Quantity:</label>";
                echo "<input type='number' id='quantity' name='quantity' value='1' min='1' max='$quantity_available' class='quantity-input' oninput='updateTotalPrice()'>";
                echo "<p>Total Price: $<span id='totalPrice' data-price='$price'>$price</span></p>";
                echo "<button type='submit' name='add_to_cart' class='btn btn-primary'>Add to Cart</button>";
                echo "<a href='checkout.php?productId=$productId' class='btn btn-modal'>Checkout</a>";
                echo "</form>";
                echo "</div>";
                echo "</div>";
            } else {
                echo "<p>Product not found.</p>";
            }
        } else {
            echo "<p>Invalid product ID.</p>";
        }
        ?>
    </div>
  </div>
</section>
<?php include '../ba/footer.php'; ?>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
